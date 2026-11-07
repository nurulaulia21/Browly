<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Denda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardPenyewaController extends Controller
{
    public function index(Request $request): View
    {
        $user = Auth::user();

        $bookingsQuery = Booking::with(['barang.lokasi', 'pemilik', 'pembayaran', 'pengembalian', 'review', 'dendas'])
            ->where('penyewa_id', $user->id);

        $tab = $request->get('tab', 'semua');

        match ($tab) {
            'menunggu_persetujuan' => $bookingsQuery->where('status', 'MENUNGGU_PERSETUJUAN'),
            'menunggu_pembayaran' => $bookingsQuery->whereIn('status', ['DISETUJUI', 'MENUNGGU_PEMBAYARAN']),
            'aktif' => $bookingsQuery->whereIn('status', ['DIBAYAR', 'AKTIF']),
            'pengembalian' => $bookingsQuery->where('status', 'DIKEMBALIKAN'),
            'selesai' => $bookingsQuery->where('status', 'SELESAI'),
            'dibatalkan' => $bookingsQuery->whereIn('status', ['DITOLAK', 'DIBATALKAN']),
            default => null,
        };

        $bookings = $bookingsQuery->latest()->paginate(10)->withQueryString();

        $stats = [
            'menunggu_persetujuan' => Booking::where('penyewa_id', $user->id)->where('status', 'MENUNGGU_PERSETUJUAN')->count(),
            'menunggu_pembayaran' => Booking::where('penyewa_id', $user->id)->whereIn('status', ['DISETUJUI', 'MENUNGGU_PEMBAYARAN'])->count(),
            'aktif' => Booking::where('penyewa_id', $user->id)->whereIn('status', ['DIBAYAR', 'AKTIF'])->count(),
            'selesai' => Booking::where('penyewa_id', $user->id)->where('status', 'SELESAI')->count(),
            'total_denda' => Denda::whereHas('booking', fn ($q) => $q->where('penyewa_id', $user->id))->sum('nominal'),
        ];

        return view('dashboard.penyewa', compact('bookings', 'stats', 'tab'));
    }
}

