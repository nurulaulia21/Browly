<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ModeController extends Controller
{
    /** Halaman pilihan: masuk sebagai Penyewa atau Pemilik. */
    public function pilih(): View|RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return view('mode.pilih');
    }

    /** Simpan mode aktif lalu arahkan ke dashboard yang sesuai. */
    public function set(string $mode): RedirectResponse
    {
        if (Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        abort_unless(in_array($mode, ['penyewa', 'pemilik'], true), 404);

        session(['mode' => $mode]);

        return $mode === 'pemilik'
            ? redirect()->route('pemilik.dashboard')->with('success', 'Anda sekarang berada di mode Pemilik.')
            : redirect()->route('penyewa.dashboard')->with('success', 'Anda sekarang berada di mode Penyewa.');
    }
}
