<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

       public function login(Request $request)
    {

        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);


        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/peserta/dashboard');
        }


        return back()->withErrors([
            'email' => __('messages.invalid_login'),
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in database
        \App\Models\OtpVerification::create([
            'email' => $data['email'],
            'otp_code' => $otp,
            'expired_at' => now()->addMinutes(10),
        ]);

        // Send OTP to email
        \Illuminate\Support\Facades\Mail::to($data['email'])->send(new \App\Mail\OtpMail($otp));

        // Store user data in session temporarily
        session(['registration_data' => $data]);

        return redirect()->route('register.otp')->with('email', $data['email']);
    }

    public function showOtpVerification()
    {
        if (!session('registration_data')) {
            return redirect()->route('register');
        }

        $email = session('registration_data')['email'];
        $cooldownUntil = \Illuminate\Support\Facades\Cache::get("otp_cooldown_until_{$email}", 0);
        $remaining = max(0, $cooldownUntil - now()->timestamp);

        return view('auth.otp', compact('remaining'));
    }

    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $registrationData = session('registration_data');
        if (!$registrationData) {
            return redirect()->route('register')->with('error', __('messages.registration_session_expired'));
        }

        $otpRecord = \App\Models\OtpVerification::where('email', $registrationData['email'])
            ->where('otp_code', $request->otp_code)
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->with('error', __('messages.otp_error'));
        }

        // Create the user
        $user = User::create([
            'name' => $registrationData['name'],
            'email' => $registrationData['email'],
            'password' => Hash::make($registrationData['password']),
            'role' => 'peserta',
        ]);

        // Login the user
        Auth::login($user);

        // Clear session and delete OTP
        session()->forget('registration_data');
        $otpRecord->delete();

        // Clear resend cooldown cache
        \Illuminate\Support\Facades\Cache::forget("otp_resend_count_{$user->email}");
        \Illuminate\Support\Facades\Cache::forget("otp_cooldown_until_{$user->email}");

        return redirect()->route('peserta.dashboard')->with('status', __('messages.otp_verified_success'));
    }

    public function showForgotPassword()
    {
        return view('auth.lupa_password');
    }

    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => __('messages.email_not_registered'),
        ]);

        $otp = rand(100000, 999999);

        \App\Models\OtpVerification::create([
            'email' => $request->email,
            'otp_code' => $otp,
            'expired_at' => now()->addMinutes(10),
        ]);

        \Illuminate\Support\Facades\Mail::to($request->email)->send(new \App\Mail\OtpMail($otp, 'reset'));

        session(['reset_email' => $request->email]);

        return redirect()->route('password.reset.otp')->with('status', __('messages.otp_sent_success'));
    }

    public function showResetOtpForm()
    {
        if (!session('reset_email')) {
            return redirect()->route('forgot-password');
        }

        $email = session('reset_email');
        $cooldownUntil = \Illuminate\Support\Facades\Cache::get("otp_cooldown_until_{$email}", 0);
        $remaining = max(0, $cooldownUntil - now()->timestamp);

        return view('auth.reset_otp', compact('remaining'));
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('forgot-password');
        }

        $otpRecord = \App\Models\OtpVerification::where('email', $email)
            ->where('otp_code', $request->otp_code)
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->with('error', __('messages.otp_error'));
        }

        session(['otp_verified' => true]);
        $otpRecord->delete();

        // Clear resend cooldown cache
        \Illuminate\Support\Facades\Cache::forget("otp_resend_count_{$email}");
        \Illuminate\Support\Facades\Cache::forget("otp_cooldown_until_{$email}");

        return redirect()->route('password.reset.form');
    }

    public function showResetPasswordForm()
    {
        if (!session('otp_verified') || !session('reset_email')) {
            return redirect()->route('forgot-password');
        }
        return view('auth.reset_password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('reset_email');
        if (!session('otp_verified') || !$email) {
            return redirect()->route('forgot-password');
        }

        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        session()->forget(['reset_email', 'otp_verified']);

        return redirect()->route('login')->with('status', __('messages.password_reset_success'));
    }

    public function resendOtp(Request $request)
    {
        $registrationData = session('registration_data');
        if (!$registrationData) {
            return redirect()->route('register')->with('error', __('messages.registration_session_expired'));
        }

        $email = $registrationData['email'];
        return $this->handleOtpResend($email, 'registration');
    }

    public function resendResetOtp(Request $request)
    {
        $email = session('reset_email');
        if (!$email) {
            return redirect()->route('forgot-password')->with('error', __('messages.registration_session_expired'));
        }

        return $this->handleOtpResend($email, 'reset');
    }

    private function handleOtpResend($email, $type)
    {
        $cacheKeyCount = "otp_resend_count_{$email}";
        $cacheKeyTime = "otp_cooldown_until_{$email}";

        $count = \Illuminate\Support\Facades\Cache::get($cacheKeyCount, 0);
        $cooldownUntil = \Illuminate\Support\Facades\Cache::get($cacheKeyTime, 0);

        if (now()->timestamp < $cooldownUntil) {
            $remaining = $cooldownUntil - now()->timestamp;
            return back()->with('error', __('messages.otp_resend_error', ['seconds' => $remaining]))
                         ->with('cooldown', $remaining);
        }

        $count++;
        $cooldownSeconds = $count * 30; // 30, 60, 90, 120...

        \Illuminate\Support\Facades\Cache::put($cacheKeyCount, $count, now()->addHours(1));
        \Illuminate\Support\Facades\Cache::put($cacheKeyTime, now()->addSeconds($cooldownSeconds)->timestamp, now()->addHours(1));

        // Generate OTP
        $otp = rand(100000, 999999);

        // Store OTP in database
        \App\Models\OtpVerification::create([
            'email' => $email,
            'otp_code' => $otp,
            'expired_at' => now()->addMinutes(10),
        ]);

        // Send OTP to email
        \Illuminate\Support\Facades\Mail::to($email)->send(new \App\Mail\OtpMail($otp, $type === 'reset' ? 'reset' : null));

        return back()->with('status', __('messages.otp_sent'))
                     ->with('cooldown', $cooldownSeconds);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
