<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Booking;
use App\Models\Denda;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DashboardAdminController extends Controller
{
    public function index(Request $request): View
    {
        $stats = [
            'total_users' => User::count(),
            'total_pengguna' => User::where('role', '!=', 'admin')->count(),
            'total_pemilik' => User::whereHas('barangs')->count(),
            'total_barangs' => Barang::count(),
            'total_bookings' => Booking::count(),
            'booking_aktif' => Booking::whereIn('status', ['DIBAYAR', 'AKTIF'])->count(),
            'total_omset' => Pembayaran::where('status', 'berhasil')->sum('nominal'),
            'total_denda' => Denda::sum('nominal'),
        ];

        $tab = $request->get('tab', 'transaksi');

        $users = User::with('lokasi')->latest()->paginate(15);
        $barangs = Barang::with(['kategori', 'lokasi', 'user'])->latest()->paginate(15);
        $bookings = Booking::with(['barang', 'penyewa', 'pemilik', 'pembayaran'])->latest()->paginate(15);
        $dendas = Denda::with(['booking.penyewa', 'booking.barang'])->latest()->paginate(15);
        $kategoris = Kategori::withCount('barangs')->get();
        $lokasis = Lokasi::withCount(['barangs', 'users'])->get();

        return view('dashboard.admin', compact('stats', 'tab', 'users', 'barangs', 'bookings', 'dendas', 'kategoris', 'lokasis'));
    }

    public function storeKategori(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:100'],
            'icon' => ['nullable', 'string', 'max:50'],
            'deskripsi' => ['nullable', 'string', 'max:255'],
        ]);

        $validated['slug'] = Str::slug($validated['nama']);
        Kategori::create($validated);

        return redirect()->route('admin.dashboard', ['tab' => 'kategori'])
            ->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    public function storeLokasi(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'kota' => ['required', 'string', 'max:100'],
            'kecamatan' => ['required', 'string', 'max:100'],
        ]);

        Lokasi::create($validated);

        return redirect()->route('admin.dashboard', ['tab' => 'lokasi'])
            ->with('success', 'Lokasi baru berhasil ditambahkan.');
    }

    public function deleteUser(User $user): RedirectResponse
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Tidak dapat menghapus akun admin yang sedang aktif.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus.');
    }

    public function toggleBarangStatus(Barang $barang): RedirectResponse
    {
        $newStatus = $barang->status === 'tersedia' ? 'nonaktif' : 'tersedia';
        $barang->update(['status' => $newStatus]);

        return back()->with('success', "Status barang diubah menjadi {$newStatus}.");
    }
}

