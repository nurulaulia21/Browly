<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Services\Payments\CashPaymentHandler;
use App\Services\Payments\PaymentMethodInterface;
use App\Services\Payments\QrisPaymentHandler;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PembayaranController extends Controller
{
    public function show(string $code): View|RedirectResponse
    {
        $booking = Booking::with(['barang', 'pemilik', 'penyewa'])
            ->where('booking_code', $code)
            ->firstOrFail();

        $user = Auth::user();

        if ($booking->penyewa_id !== $user->id && ! $user->isAdmin()) {
            abort(403, 'Hanya penyewa yang dapat melakukan pembayaran.');
        }

        if ($booking->status !== 'MENUNGGU_PEMBAYARAN') {
            return redirect()->route('booking.show', $booking->booking_code)
                ->with('info', 'Status booking ini tidak dalam status menunggu pembayaran.');
        }

        return view('pembayaran.show', compact('booking'));
    }

    public function process(Request $request, string $code): RedirectResponse
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();
        $user = Auth::user();

        if ($booking->penyewa_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        if ($booking->status !== 'MENUNGGU_PEMBAYARAN') {
            return redirect()->route('booking.show', $booking->booking_code)
                ->with('error', 'Status booking tidak valid untuk dibayar.');
        }

        /** @var PaymentMethodInterface $paymentHandler */
        $paymentHandler = match ($booking->metode_pembayaran) {
            'qris' => new QrisPaymentHandler(),
            default => new CashPaymentHandler(),
        };

        $paymentHandler->processPayment($booking, $request->all());

        return redirect()->route('booking.show', $booking->booking_code)
            ->with('success', 'Pembayaran berhasil dikonfirmasi! Ruang chat dengan pemilik sekarang telah aktif.');
    }
}

