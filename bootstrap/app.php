<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use App\Http\Middleware\EnsureUserHasRole;
use App\Http\Middleware\RunBookingMaintenance;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Di Vercel aplikasi berada di belakang proxy HTTPS; percayai header-nya
        // agar alamat CSS, gambar, dan tautan memakai https:// (bukan http://).
        $middleware->trustProxies(at: '*');

        // Batalkan booking kedaluwarsa secara berkala tanpa cron.
        $middleware->web(append: [RunBookingMaintenance::class]);

        // Daftarkan alias role yang digunakan pada routes/web.php.
        $middleware->alias([
            'role' => EnsureUserHasRole::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->expectsJson(),
        );
    })->create();
