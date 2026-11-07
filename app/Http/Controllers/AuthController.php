<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Selamat datang kembali, Admin!');
            }

            // Pengguna memilih dulu mau masuk sebagai Penyewa atau Pemilik
            return redirect()->route('mode.pilih');
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan tidak sesuai.',
        ])->onlyInput('email');
    }

    public function showRegister(): View
    {
        $lokasis = Lokasi::orderBy('kota')->orderBy('kecamatan')->get();

        return view('auth.register', compact('lokasis'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'alamat_lengkap' => ['nullable', 'string', 'max:500'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'role' => 'pengguna',
            'lokasi_id' => $validated['lokasi_id'],
            'alamat_lengkap' => $validated['alamat_lengkap'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('mode.pilih')->with('success', 'Pendaftaran berhasil! Selamat datang di Browly.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Anda telah berhasil keluar.');
    }

    public function quickLogin(string $role): RedirectResponse
    {
        // Hanya 2 jenis akun: admin & pengguna (2 akun demo pengguna: Budi & Siti)
        $email = match ($role) {
            'admin' => 'admin@borrowly.test',
            'budi' => 'pemilik@borrowly.test',
            default => 'penyewa@borrowly.test',
        };

        $user = User::where('email', $email)->first();
        if (! $user) {
            return redirect()->route('login')->with('error', 'Akun demo tidak ditemukan.');
        }

        Auth::login($user);
        request()->session()->regenerate();

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard')->with('info', 'Masuk cepat sebagai Admin Browly.');
        }

        return redirect()->route('mode.pilih')->with('info', 'Masuk cepat sebagai ' . $user->name . '. Silakan pilih mode.');
    }
}
