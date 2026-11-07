<?php

namespace App\Services;

use App\Models\Barang;
use App\Models\Booking;
use App\Models\ChatRoom;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Str;
use InvalidArgumentException;

class BookingService
{
    public function createBooking(Barang $barang, User $penyewa, array $data): Booking
    {
        $startDate = Carbon::parse($data['tanggal_mulai'])->toDateString();
        $endDate = Carbon::parse($data['tanggal_selesai'])->toDateString();

        if (Carbon::parse($startDate)->lessThan(Carbon::today())) {
            throw new InvalidArgumentException('Tanggal sewa tidak boleh sebelum hari ini.');
        }

        if (Carbon::parse($endDate)->lessThan(Carbon::parse($startDate))) {
            throw new InvalidArgumentException('Tanggal selesai tidak boleh lebih awal dari tanggal mulai.');
        }

        if (! $barang->isAvailableForRange($startDate, $endDate)) {
            throw new InvalidArgumentException('Barang tidak tersedia pada rentang tanggal yang dipilih. Silakan pilih tanggal lain.');
        }

        $days = (int) max(1, Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1);
        $biayaSewa = $barang->calculateRentalPrice($days);

        $metodePengambilan = $data['metode_pengambilan'];
        $ongkir = 0;
        $jarakKm = null;
        $alamatPengantaran = null;

        if ($metodePengambilan === 'diantar') {
            $jarakKm = (float) ($data['jarak_km'] ?? 5);
            $ongkir = $barang->calculateDeliveryFee($jarakKm);
            $alamatPengantaran = $data['alamat_pengantaran'] ?? null;
        }

        $totalBiaya = $biayaSewa + $ongkir;
        $bookingCode = 'BRW-' . date('Ymd') . '-' . strtoupper(Str::random(4));

        $booking = Booking::create([
            'booking_code' => $bookingCode,
            'barang_id' => $barang->id,
            'penyewa_id' => $penyewa->id,
            'pemilik_id' => $barang->user_id,
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'jumlah_hari' => $days,
            'biaya_sewa' => $biayaSewa,
            'metode_pengambilan' => $metodePengambilan,
            'alamat_pengantaran' => $alamatPengantaran,
            'jarak_km' => $jarakKm,
            'ongkir' => $ongkir,
            'total_biaya' => $totalBiaya,
            'metode_pembayaran' => $data['metode_pembayaran'],
            'status' => 'MENUNGGU_PERSETUJUAN',
            'expires_at_owner' => Carbon::now()->addHours(24),
            'catatan_penyewa' => $data['catatan_penyewa'] ?? null,
        ]);

        ChatRoom::create([
            'booking_id' => $booking->id,
            'penyewa_id' => $penyewa->id,
            'pemilik_id' => $barang->user_id,
            'is_active' => false,
        ]);

        return $booking;
    }

    public function approveBooking(Booking $booking, User $pemilik): void
    {
        if ($booking->pemilik_id !== $pemilik->id) {
            throw new InvalidArgumentException('Anda tidak berhak memproses booking ini.');
        }

        if ($booking->status !== 'MENUNGGU_PERSETUJUAN') {
            throw new InvalidArgumentException('Status booking tidak valid untuk disetujui.');
        }

        $booking->update([
            'status' => 'MENUNGGU_PEMBAYARAN',
            'expires_at_payment' => Carbon::now()->addHours(24),
        ]);
    }

    public function rejectBooking(Booking $booking, User $pemilik, string $reason): void
    {
        if ($booking->pemilik_id !== $pemilik->id) {
            throw new InvalidArgumentException('Anda tidak berhak memproses booking ini.');
        }

        $booking->update([
            'status' => 'DITOLAK',
            'alasan_penolakan' => $reason,
        ]);
    }

    public function cancelExpiredBookings(): int
    {
        $now = Carbon::now();
        $count = 0;

        $expiredOwner = Booking::where('status', 'MENUNGGU_PERSETUJUAN')
            ->whereNotNull('expires_at_owner')
            ->where('expires_at_owner', '<', $now)
            ->get();

        foreach ($expiredOwner as $booking) {
            $booking->update([
                'status' => 'DIBATALKAN',
                'alasan_pembatalan' => 'Otomatis dibatalkan: Pemilik tidak memberikan respon dalam 24 jam.',
            ]);
            $count++;
        }

        $expiredPayment = Booking::where('status', 'MENUNGGU_PEMBAYARAN')
            ->whereNotNull('expires_at_payment')
            ->where('expires_at_payment', '<', $now)
            ->get();

        foreach ($expiredPayment as $booking) {
            $booking->update([
                'status' => 'DIBATALKAN',
                'alasan_pembatalan' => 'Otomatis dibatalkan: Penyewa tidak menyelesaikan pembayaran dalam 24 jam.',
            ]);
            $count++;
        }

        return $count;
    }
}

