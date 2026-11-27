<?php

namespace App\Http\Middleware;

use App\Services\BookingService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

/**
 * Membatalkan booking yang kedaluwarsa (24 jam) tanpa perlu cron.
 * Dijalankan paling sering sekali per menit, saat ada yang membuka aplikasi,
 * sehingga tetap bekerja di hosting tanpa penjadwal (mis. Vercel).
 */
class RunBookingMaintenance
{
    public function handle(Request $request, Closure $next): Response
    {
        try {
            if (Cache::add('browly:cek-kedaluwarsa', 1, 60)) {
                app(BookingService::class)->cancelExpiredBookings();
            }
        } catch (\Throwable $e) {
            report($e); // jangan sampai pengecekan ini merusak halaman
        }

        return $next($request);
    }
}
