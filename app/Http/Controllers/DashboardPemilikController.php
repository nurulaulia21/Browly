<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangBlockedDate;
use App\Models\Booking;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Services\BookingService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class DashboardPemilikController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function index(Request $request): View
    {
        $user = Auth::user();

        $stats = [
            'total_barang' => Barang::where('user_id', $user->id)->count(),
            'booking_masuk' => Booking::where('pemilik_id', $user->id)->where('status', 'MENUNGGU_PERSETUJUAN')->count(),
            'sewa_aktif' => Booking::where('pemilik_id', $user->id)->whereIn('status', ['DIBAYAR', 'AKTIF'])->count(),
            'pengembalian_pending' => Booking::where('pemilik_id', $user->id)->where('status', 'DIKEMBALIKAN')->count(),
            'total_pendapatan' => Booking::where('pemilik_id', $user->id)->whereIn('status', ['DIBAYAR', 'AKTIF', 'SELESAI'])->sum('biaya_sewa'),
        ];

        $tab = $request->get('tab', 'booking_masuk');

        $barangs = Barang::with(['kategori', 'lokasi', 'blockedDates'])
            ->where('user_id', $user->id)
            ->latest()
            ->get();

        $bookingsMasuk = Booking::with(['barang', 'penyewa'])
            ->where('pemilik_id', $user->id)
            ->where('status', 'MENUNGGU_PERSETUJUAN')
            ->latest()
            ->get();

        $sewaAktif = Booking::with(['barang', 'penyewa', 'pembayaran'])
            ->where('pemilik_id', $user->id)
            ->whereIn('status', ['DISETUJUI', 'MENUNGGU_PEMBAYARAN', 'DIBAYAR', 'AKTIF'])
            ->latest()
            ->get();

        $pengembalianList = Booking::with(['barang', 'penyewa', 'pengembalian'])
            ->where('pemilik_id', $user->id)
            ->whereIn('status', ['DIKEMBALIKAN', 'SELESAI'])
            ->latest()
            ->get();

        return view('dashboard.pemilik', compact('stats', 'tab', 'barangs', 'bookingsMasuk', 'sewaAktif', 'pengembalianList'));
    }

    public function approveBooking(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();

        try {
            $this->bookingService->approveBooking($booking, $user);

            return back()->with('success', "Booking {$booking->booking_code} berhasil disetujui. Penyewa memiliki waktu 24 jam untuk melakukan pembayaran.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function rejectBooking(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();

        $request->validate([
            'alasan_penolakan' => ['required', 'string', 'max:255'],
        ]);

        try {
            $this->bookingService->rejectBooking($booking, $user, $request->alasan_penolakan);

            return back()->with('info', "Booking {$booking->booking_code} telah ditolak.");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function createBarang(): View
    {
        $kategoris = Kategori::all();
        $lokasis = Lokasi::orderBy('kota')->orderBy('kecamatan')->get();

        return view('pemilik.barang-create', compact('kategoris', 'lokasis'));
    }

    public function storeBarang(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:255'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'harga_sewa_per_hari' => ['required', 'integer', 'min:5000'],
            'kondisi' => ['required', 'string', 'max:50'],
            'metode_pembayaran' => ['required', 'in:qris,cash,both'],
            'metode_pengambilan' => ['required', 'in:ambil_sendiri,diantar,both'],
            'tarif_pengantaran_per_km' => ['nullable', 'integer', 'min:0'],
            'foto_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'foto' => ['nullable', 'string', 'max:500'],
            'deskripsi' => ['required', 'string'],
        ]);

        if ($request->hasFile('foto_file')) {
            $path = $request->file('foto_file')->store('barangs', 'public');
            $validated['foto'] = $path;
        } elseif (empty($validated['foto'])) {
            $validated['foto'] = 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=800&q=80';
        }

        unset($validated['foto_file']);

        $validated['user_id'] = $user->id;
        $validated['tarif_pengantaran_per_km'] = $validated['tarif_pengantaran_per_km'] ?? 5000;
        $validated['status'] = 'tersedia';

        Barang::create($validated);

        return redirect()->route('pemilik.dashboard', ['tab' => 'barang_saya'])
            ->with('success', 'Barang sewaan berhasil ditambahkan ke katalog!');
    }

    public function editBarang(Barang $barang): View
    {
        if ($barang->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $kategoris = Kategori::all();
        $lokasis = Lokasi::orderBy('kota')->orderBy('kecamatan')->get();

        return view('pemilik.barang-edit', compact('barang', 'kategoris', 'lokasis'));
    }

    public function updateBarang(Request $request, Barang $barang): RedirectResponse
    {
        if ($barang->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $validated = $request->validate([
            'nama_barang' => ['required', 'string', 'max:255'],
            'kategori_id' => ['required', 'exists:kategoris,id'],
            'lokasi_id' => ['required', 'exists:lokasis,id'],
            'harga_sewa_per_hari' => ['required', 'integer', 'min:5000'],
            'kondisi' => ['required', 'string', 'max:50'],
            'metode_pembayaran' => ['required', 'in:qris,cash,both'],
            'metode_pengambilan' => ['required', 'in:ambil_sendiri,diantar,both'],
            'tarif_pengantaran_per_km' => ['nullable', 'integer', 'min:0'],
            'foto_file' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:5120'],
            'foto' => ['nullable', 'string', 'max:500'],
            'status' => ['required', 'in:tersedia,tidak_tersedia,nonaktif'],
            'deskripsi' => ['required', 'string'],
        ]);

        if ($request->hasFile('foto_file')) {
            $oldRaw = $barang->getRawOriginal('foto');
            if ($oldRaw && ! str_starts_with($oldRaw, 'http') && Storage::disk('public')->exists($oldRaw)) {
                Storage::disk('public')->delete($oldRaw);
            }
            $validated['foto'] = $request->file('foto_file')->store('barangs', 'public');
        } elseif (empty($validated['foto'])) {
            unset($validated['foto']);
        }

        unset($validated['foto_file']);

        $barang->update($validated);

        return redirect()->route('pemilik.dashboard', ['tab' => 'barang_saya'])
            ->with('success', 'Data barang berhasil diperbarui!');
    }

    public function destroyBarang(Barang $barang): RedirectResponse
    {
        if ($barang->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $barang->delete();

        return redirect()->route('pemilik.dashboard', ['tab' => 'barang_saya'])
            ->with('success', 'Barang berhasil dihapus.');
    }

    public function blockDate(Request $request, Barang $barang): RedirectResponse
    {
        if ($barang->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $request->validate([
            'start_date' => ['required', 'date', 'after_or_equal:today'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'alasan' => ['nullable', 'string', 'max:255'],
        ]);

        BarangBlockedDate::create([
            'barang_id' => $barang->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'alasan' => $request->alasan ?? 'Digunakan pribadi oleh pemilik',
        ]);

        return back()->with('success', 'Tanggal berhasil diblokir pada kalender ketersediaan.');
    }

    public function deleteBlockedDate(BarangBlockedDate $blockedDate): RedirectResponse
    {
        if ($blockedDate->barang->user_id !== Auth::id() && ! Auth::user()->isAdmin()) {
            abort(403);
        }

        $blockedDate->delete();

        return back()->with('success', 'Tanggal blokir berhasil dihapus. Tanggal kembali tersedia.');
    }
}

