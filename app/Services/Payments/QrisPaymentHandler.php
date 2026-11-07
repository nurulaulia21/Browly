<?php

namespace App\Services\Payments;

use App\Models\Booking;
use App\Models\Pembayaran;
use Carbon\Carbon;
use Illuminate\Support\Str;

class QrisPaymentHandler implements PaymentMethodInterface
{
    public function getMethodName(): string
    {
        return 'qris';
    }

    public function processPayment(Booking $booking, array $payload = []): Pembayaran
    {
        $pembayaran = Pembayaran::create([
            'booking_id' => $booking->id,
            'nomor_transaksi' => 'QRIS-' . strtoupper(Str::random(10)),
            'metode' => 'qris',
            'nominal' => $booking->total_biaya,
            'status' => 'berhasil',
            'bukti_bayar' => $payload['bukti_bayar'] ?? 'simulasi_qris_paid.png',
            'waktu_bayar' => Carbon::now(),
            'keterangan' => 'Pembayaran simulasi QRIS berhasil diverifikasi sistem.',
        ]);

        $booking->update(['status' => 'DIBAYAR']);

        if ($booking->chatRoom) {
            $booking->chatRoom->update(['is_active' => true]);
        }

        return $pembayaran;
    }
}

