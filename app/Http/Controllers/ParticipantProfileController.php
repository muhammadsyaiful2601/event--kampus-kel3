<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Mail\OtpMail;
use App\Models\OtpVerification;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ParticipantProfileController extends Controller
{
    public function edit()
    {
        return view('profile.peserta', ['user' => Auth::user()]);
    }

    // Receive profile update request and send OTP(s)
    public function sendOtp(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $pending = [];
        $now = Carbon::now();
        $expires = $now->copy()->addMinutes(15);

        // If email changed, send OTP to new email
        if ($data['email'] !== $user->email) {
            $otp = random_int(100000, 999999);
            OtpVerification::create([
                'email' => $data['email'],
                'otp_code' => $otp,
                'expired_at' => $expires,
            ]);
            Mail::to($data['email'])->send(new OtpMail($otp, 'verify'));
            $pending['email'] = $data['email'];
        }

        // If password provided, send OTP to current (existing) email
        if (!empty($data['password'])) {
            $otpPass = random_int(100000, 999999);
            OtpVerification::create([
                'email' => $user->email,
                'otp_code' => $otpPass,
                'expired_at' => $expires,
            ]);
            Mail::to($user->email)->send(new OtpMail($otpPass, 'reset'));
            $pending['password'] = $data['password'];
        }

        if (!empty($data['name']) && $data['name'] !== $user->name) {
            $pending['name'] = $data['name'];
        }

        if (empty($pending)) {
            return back()->with('info', 'Tidak ada perubahan yang terdeteksi.');
        }

        // store pending changes in session for verification step
        session(['profile_update' => $pending]);

        return redirect()->route('peserta.profile.verify')->with('status', 'OTP telah dikirim. Periksa email untuk kode verifikasi.');
    }

    public function showVerify()
    {
        return view('profile.verify');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'otp' => ['required'],
        ]);

        $pending = session('profile_update');
        if (empty($pending)) {
            return redirect()->route('peserta.profile')->with('error', 'Tidak ada perubahan yang perlu diverifikasi.');
        }

        $code = $request->input('otp');
        $now = Carbon::now();

        // find matching OTP record for either the email change target or current user email
        $emails = [];
        if (!empty($pending['email'])) {
            $emails[] = $pending['email'];
        }
        if (!empty($pending['password'])) {
            $emails[] = Auth::user()->email;
        }

        $otpRecord = OtpVerification::whereIn('email', $emails)
            ->where('otp_code', $code)
            ->where('expired_at', '>', $now)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid atau sudah kedaluwarsa.']);
        }

        $user = Auth::user();

        // Apply name change if present
        if (!empty($pending['name'])) {
            $user->name = $pending['name'];
        }

        // If OTP was for new email, and matches that email, apply
        if (!empty($pending['email']) && $otpRecord->email === $pending['email']) {
            $user->email = $pending['email'];
        }

        // If OTP was sent to current email for password change
        if (!empty($pending['password']) && $otpRecord->email === Auth::user()->email) {
            $user->password = Hash::make($pending['password']);
        }

        $user->save();

        // clear session and remove used otp record(s)
        session()->forget('profile_update');
        // delete all OTPs with same code and email to avoid reuse
        OtpVerification::where('otp_code', $code)->whereIn('email', $emails)->delete();

        return redirect()->route('peserta.profile')->with('status', 'Profil berhasil diperbarui.');
    }
}
