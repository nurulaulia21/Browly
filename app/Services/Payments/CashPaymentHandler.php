<?php

namespace App\Services\Payments;

use App\Models\Booking;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Str;

class CashPaymentHandler implements PaymentMethodInterface
{
    public function getMethodName(): string
    {
        return 'cash';
    }

    public function processPayment(Booking $booking, array $payload = []): Pembayaran
    {
        $pembayaran = Pembayaran::create([
            'booking_id' => $booking->id,
            'nomor_transaksi' => 'CSH-' . strtoupper(Str::random(10)),
            'metode' => 'cash',
            'nominal' => $booking->total_biaya,
            'status' => 'berhasil',
            'bukti_bayar' => null,
            'waktu_bayar' => Carbon::now(),
            'keterangan' => 'Pembayaran tunai (Cash on Delivery/Pickup) dikonfirmasi.',
        ]);

        $booking->update(['status' => 'DIBAYAR']);

        if ($booking->chatRoom) {
            $booking->chatRoom->update(['is_active' => true]);
        }

        return $pembayaran;
    }
}

