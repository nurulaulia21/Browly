<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Lupa password tanpa server email:
 * pengguna membuktikan identitas dengan email + nomor telepon yang terdaftar,
 * lalu diarahkan ke form password baru memakai token reset bawaan Laravel.
 */
class PasswordResetController extends Controller
{
    public function showForgot(): View
    {
        return view('auth.forgot-password');
    }

    public function verify(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'phone' => ['required', 'string', 'max:20'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'phone.required' => 'Nomor telepon wajib diisi.',
        ]);

        $user = User::where('email', $data['email'])->first();
        $phone = $this->normalizePhone($data['phone']);

        // Pesan sengaja sama untuk semua kegagalan agar tidak membocorkan akun yang terdaftar.
        // Akun admin tidak bisa direset mandiri (hubungi pengelola sistem).
        if (! $user || $user->isAdmin() || $phone === '' || $this->normalizePhone($user->phone) !== $phone) {
            return back()->withErrors([
                'email' => 'Email dan nomor telepon tidak cocok dengan data akun kami.',
            ])->onlyInput('email');
        }

        $token = Password::broker()->createToken($user);

        return redirect()->route('password.reset', ['token' => $token, 'email' => $user->email]);
    }

    public function showReset(Request $request, string $token): View
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak sama.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => $password,
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil diubah. Silakan masuk dengan password baru Anda.');
        }

        return redirect()->route('password.request')->withErrors([
            'email' => 'Tautan reset tidak valid atau sudah kedaluwarsa. Silakan verifikasi ulang.',
        ]);
    }

    /** Samakan format: 0812..., +62812..., 62812... dianggap sama. */
    private function normalizePhone(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone);

        if (str_starts_with($digits, '62')) {
            $digits = '0' . substr($digits, 2);
        }

        return $digits;
    }
}
