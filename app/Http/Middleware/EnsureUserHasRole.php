<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login')->with('error', 'Silakan masuk terlebih dahulu.');
        }

        $user = auth()->user();

        if ($user->isAdmin()) {
            return $next($request);
        }

        // Pengguna boleh masuk ke area penyewa maupun pemilik (mode dipilih setelah login)
        if ($user->isPengguna() && array_intersect($roles, ['pengguna', 'penyewa', 'pemilik'])) {
            return $next($request);
        }

        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Anda tidak memiliki hak akses untuk halaman tersebut.');
    }
}

