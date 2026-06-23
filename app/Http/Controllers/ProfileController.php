<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OtpVerification;
use App\Mail\OtpMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;

class ProfileController extends Controller
{
    /**
     * Show the profile update page.
     */
    public function show()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }

    /**
     * Submit profile update and trigger OTP.
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ];

        $request->validate($rules);

        // Store pending changes in session
        $pendingChanges = [
            'name' => $request->name,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $pendingChanges['password'] = Hash::make($request->password);
        }

        session(['profile_update_data' => $pendingChanges]);

        // Generate and send OTP
        $otp = rand(100000, 999999);
        OtpVerification::create([
            'email' => $request->email, // Send OTP to the target email
            'otp_code' => $otp,
            'expired_at' => now()->addMinutes(10),
        ]);

        Mail::to($request->email)->send(new OtpMail($otp, 'profile_update'));

        return redirect()->route('profile.otp')->with('status', 'OTP telah dikirim ke email Anda untuk verifikasi perubahan.');
    }

    /**
     * Show OTP verification form for profile update.
     */
    public function showOtpForm()
    {
        if (!session('profile_update_data')) {
            return redirect()->route('profile.show');
        }

        $email = session('profile_update_data')['email'];
        $cooldownUntil = Cache::get("otp_cooldown_until_{$email}", 0);
        $remaining = max(0, $cooldownUntil - now()->timestamp);

        return view('profile.otp', compact('remaining', 'email'));
    }

    /**
     * Verify OTP and apply profile changes.
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $pendingData = session('profile_update_data');
        if (!$pendingData) {
            return redirect()->route('profile.show')->with('error', 'Sesi update profil kedaluwarsa.');
        }

        $otpRecord = OtpVerification::where('email', $pendingData['email'])
            ->where('otp_code', $request->otp_code)
            ->where('expired_at', '>', now())
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->with('error', 'Kode OTP tidak valid atau sudah kedaluwarsa.');
        }

        // Apply changes
        $user = User::find(Auth::id());
        $user->update($pendingData);

        // Cleanup
        session()->forget('profile_update_data');
        $otpRecord->delete();
        Cache::forget("otp_resend_count_{$user->email}");
        Cache::forget("otp_cooldown_until_{$user->email}");

        return redirect()->route('profile.show')->with('success', 'Profil Anda berhasil diperbarui.');
    }

    /**
     * Resend OTP for profile update.
     */
    public function resendOtp()
    {
        $pendingData = session('profile_update_data');
        if (!$pendingData) {
            return redirect()->route('profile.show');
        }

        $email = $pendingData['email'];
        $cacheKeyCount = "otp_resend_count_{$email}";
        $cacheKeyTime = "otp_cooldown_until_{$email}";

        $count = Cache::get($cacheKeyCount, 0);
        $cooldownUntil = Cache::get($cacheKeyTime, 0);

        if (now()->timestamp < $cooldownUntil) {
            $remaining = $cooldownUntil - now()->timestamp;
            return back()->with('error', "Harap tunggu {$remaining} detik sebelum meminta kode baru.");
        }

        $count++;
        $cooldownSeconds = $count * 30;

        Cache::put($cacheKeyCount, $count, now()->addHours(1));
        Cache::put($cacheKeyTime, now()->addSeconds($cooldownSeconds)->timestamp, now()->addHours(1));

        $otp = rand(100000, 999999);
        OtpVerification::create([
            'email' => $email,
            'otp_code' => $otp,
            'expired_at' => now()->addMinutes(10),
        ]);

        Mail::to($email)->send(new OtpMail($otp, 'profile_update'));

        return back()->with('status', 'Kode OTP baru telah dikirim.')->with('cooldown', $cooldownSeconds);
    }
}
