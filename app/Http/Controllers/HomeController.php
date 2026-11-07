<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Booking;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\User;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $kategoris = Kategori::withCount('barangs')->get();
        $lokasis = Lokasi::orderBy('kota')->orderBy('kecamatan')->get();

        $featuredBarangs = Barang::with(['kategori', 'lokasi', 'user', 'reviews'])
            ->where('status', 'tersedia')
            ->latest()
            ->take(8)
            ->get();

        $stats = [
            'total_barang' => Barang::count(),
            'total_user' => User::count(),
            'total_transaksi' => Booking::whereIn('status', ['DIBAYAR', 'AKTIF', 'SELESAI'])->count(),
        ];

        return view('home', compact('kategoris', 'lokasis', 'featuredBarangs', 'stats'));
    }
}

