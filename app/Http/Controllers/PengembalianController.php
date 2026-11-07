<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Denda;
use App\Models\Pengembalian;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PengembalianController extends Controller
{
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();

        if ($booking->penyewa_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        if (! in_array($booking->status, ['DIBAYAR', 'AKTIF'])) {
            return back()->with('error', 'Booking tidak dalam status yang dapat dikembalikan.');
        }

        $validated = $request->validate([
            'tanggal_pengembalian_aktual' => ['required', 'date'],
            'kondisi_barang' => ['required', 'in:sesuai_awal,rusak_ringan,rusak_berat'],
            'catatan' => ['nullable', 'string', 'max:500'],
        ]);

        $actualReturnDate = Carbon::parse($validated['tanggal_pengembalian_aktual']);
        $expectedEndDate = Carbon::parse($booking->tanggal_selesai)->endOfDay();

        $lateDays = 0;
        if ($actualReturnDate->greaterThan($expectedEndDate)) {
            $lateDays = (int) ceil($actualReturnDate->diffInDays($expectedEndDate));
        }

        $pengembalian = Pengembalian::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'tanggal_pengembalian_aktual' => $actualReturnDate,
                'kondisi_barang' => $validated['kondisi_barang'],
                'catatan' => $validated['catatan'] ?? null,
                'hari_keterlambatan' => $lateDays,
                'status' => 'diajukan',
            ]
        );

        $booking->update(['status' => 'DIKEMBALIKAN']);

        return redirect()->route('booking.show', $booking->booking_code)
            ->with('success', 'Pengajuan pengembalian barang berhasil dikirim. Menunggu konfirmasi pemilik barang.');
    }

    public function confirm(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();

        if ($booking->pemilik_id !== $user->id && ! $user->isAdmin()) {
            abort(403);
        }

        $pengembalian = $booking->pengembalian;
        if (! $pengembalian) {
            return back()->with('error', 'Data pengembalian belum diajukan oleh penyewa.');
        }

        $request->validate([
            'kondisi_barang_verifikasi' => ['required', 'in:sesuai_awal,rusak_ringan,rusak_berat'],
            'denda_keterlambatan' => ['nullable', 'integer', 'min:0'],
            'denda_kerusakan' => ['nullable', 'integer', 'min:0'],
            'deskripsi_kerusakan' => ['nullable', 'string', 'max:500'],
        ]);

        if ($request->filled('denda_keterlambatan') && $request->denda_keterlambatan > 0) {
            Denda::create([
                'booking_id' => $booking->id,
                'pengembalian_id' => $pengembalian->id,
                'jenis_denda' => 'keterlambatan',
                'nominal' => $request->denda_keterlambatan,
                'deskripsi' => "Denda keterlambatan pengembalian selama {$pengembalian->hari_keterlambatan} hari.",
                'status_pembayaran' => 'belum_dibayar',
            ]);
        }

        if ($request->filled('denda_kerusakan') && $request->denda_kerusakan > 0) {
            Denda::create([
                'booking_id' => $booking->id,
                'pengembalian_id' => $pengembalian->id,
                'jenis_denda' => 'kerusakan',
                'nominal' => $request->denda_kerusakan,
                'deskripsi' => $request->deskripsi_kerusakan ?? 'Denda penggantian/perbaikan kerusakan barang.',
                'status_pembayaran' => 'belum_dibayar',
            ]);
        }

        $hasDenda = ($request->denda_keterlambatan > 0) || ($request->denda_kerusakan > 0);

        $pengembalian->update([
            'kondisi_barang' => $request->kondisi_barang_verifikasi,
            'status' => $hasDenda ? 'ada_denda' : 'dikonfirmasi',
        ]);

        $booking->update(['status' => 'SELESAI']);

        return redirect()->route('booking.show', $booking->booking_code)
            ->with('success', 'Pengembalian barang telah dikonfirmasi dan transaksi dinyatakan SELESAI.');
    }
}

