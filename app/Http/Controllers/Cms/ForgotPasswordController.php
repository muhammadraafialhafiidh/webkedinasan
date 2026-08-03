<?php

namespace App\Http\Controllers\Cms;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use App\Mail\ResetPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email tidak ditemukan.',
        ]);

        $user = User::where('email', $request->email)->first();

        $token = Str::random(60);

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $request->email],
            ['token' => Hash::make($token), 'created_at' => now()]
        );

        ActivityLog::record('forgot_password', 'Autentikasi', "Permintaan reset password untuk email {$request->email}.");

        $resetUrl = route('cms.reset-password.form', ['token' => $token, 'email' => $request->email]);

        try {
            Mail::to($user->email)->send(new ResetPasswordMail($resetUrl, $user));
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email reset password: ' . $e->getMessage());
        }

        return back()->with('success', 'Link reset password telah berhasil dikirim ke email Anda.');
    }

    public function showExpiredLink()
    {
        return view('auth.reset-password-expired');
    }

    public function showResetPassword(Request $request, $token)
    {
        $email = $request->query('email', '');
        $resetRecord = DB::table('password_reset_tokens')->where('email', $email)->first();

        $expireMinutes = 15; // Batas masa berlaku link (15 menit)
        $isExpired = $resetRecord && \Carbon\Carbon::parse($resetRecord->created_at)->addMinutes($expireMinutes)->isPast();

        if (!$resetRecord || !Hash::check($token, $resetRecord->token) || $isExpired) {
            return redirect()->route('cms.reset-password.expired');
        }

        return view('auth.reset-password', [
            'token' => $token,
            'email' => $email
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->first();

        $expireMinutes = 15; // Batas masa berlaku link reset password (15 menit)
        $isExpired = $resetRecord && \Carbon\Carbon::parse($resetRecord->created_at)->addMinutes($expireMinutes)->isPast();

        if (!$resetRecord || !Hash::check($request->token, $resetRecord->token) || $isExpired) {
            return redirect()->route('cms.reset-password.expired');
        }

        $user = User::where('email', $request->email)->first();
        $user->update(['password' => Hash::make($request->password)]);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        ActivityLog::record('reset_password', 'Autentikasi', "Password untuk user {$user->name} berhasil diperbarui.");

        return redirect()->route('cms.login')->with('success', 'Password Anda berhasil diperbarui. Silakan login kembali.');
    }
}
