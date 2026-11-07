<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Kategori;
use App\Models\Lokasi;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BarangController extends Controller
{
    public function index(Request $request): View
    {
        $kategoris = Kategori::all();
        $lokasis = Lokasi::orderBy('kota')->orderBy('kecamatan')->get();

        $query = Barang::with(['kategori', 'lokasi', 'user', 'reviews'])
            ->where('status', 'tersedia');

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_barang', 'like', "%{$keyword}%")
                    ->orWhere('deskripsi', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('slug', $request->kategori);
            });
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_id', $request->lokasi_id);
        } elseif ($request->filled('kota')) {
            $query->whereHas('lokasi', function ($q) use ($request) {
                $q->where('kota', $request->kota);
            });
        }

        if ($request->filled('pengambilan')) {
            $metode = $request->pengambilan;
            if ($metode === 'ambil_sendiri') {
                $query->whereIn('metode_pengambilan', ['ambil_sendiri', 'both']);
            } elseif ($metode === 'diantar') {
                $query->whereIn('metode_pengambilan', ['diantar', 'both']);
            }
        }

        if ($request->filled('min_harga')) {
            $query->where('harga_sewa_per_hari', '>=', (int) $request->min_harga);
        }
        if ($request->filled('max_harga')) {
            $query->where('harga_sewa_per_hari', '<=', (int) $request->max_harga);
        }

        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'price_low' => $query->orderBy('harga_sewa_per_hari', 'asc'),
            'price_high' => $query->orderBy('harga_sewa_per_hari', 'desc'),
            default => $query->latest(),
        };

        $barangs = $query->paginate(12)->withQueryString();

        return view('barangs.index', compact('barangs', 'kategoris', 'lokasis'));
    }

    public function show(string $slug): View
    {
        $barang = Barang::with(['kategori', 'lokasi', 'user', 'blockedDates', 'reviews.penyewa'])
            ->where('slug', $slug)
            ->firstOrFail();

        $activeBookings = $barang->bookings()
            ->whereIn('status', ['MENUNGGU_PERSETUJUAN', 'DISETUJUI', 'MENUNGGU_PEMBAYARAN', 'DIBAYAR', 'AKTIF'])
            ->select('id', 'tanggal_mulai', 'tanggal_selesai', 'status')
            ->get();

        $blockedDates = $barang->blockedDates()
            ->select('id', 'start_date', 'end_date', 'alasan')
            ->get();

        $similarBarangs = Barang::where('kategori_id', $barang->kategori_id)
            ->where('id', '!=', $barang->id)
            ->where('status', 'tersedia')
            ->take(4)
            ->get();

        return view('barangs.show', compact('barang', 'activeBookings', 'blockedDates', 'similarBarangs'));
    }

    public function checkAvailability(Request $request, Barang $barang): JsonResponse
    {
        $request->validate([
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'metode_pengambilan' => ['required', 'in:ambil_sendiri,diantar'],
            'jarak_km' => ['nullable', 'numeric', 'min:0'],
        ]);

        $startDate = Carbon::parse($request->tanggal_mulai)->toDateString();
        $endDate = Carbon::parse($request->tanggal_selesai)->toDateString();

        $isAvailable = $barang->isAvailableForRange($startDate, $endDate);
        $days = (int) max(1, Carbon::parse($startDate)->diffInDays(Carbon::parse($endDate)) + 1);
        $biayaSewa = $barang->calculateRentalPrice($days);

        $ongkir = 0;
        if ($request->metode_pengambilan === 'diantar') {
            $jarakKm = (float) ($request->jarak_km ?? 5);
            $ongkir = $barang->calculateDeliveryFee($jarakKm);
        }

        $totalBiaya = $biayaSewa + $ongkir;

        return response()->json([
            'available' => $isAvailable,
            'message' => $isAvailable ? 'Barang tersedia untuk tanggal tersebut.' : 'Maaf, tanggal tersebut telah dibooking atau diblokir oleh pemilik.',
            'jumlah_hari' => $days,
            'biaya_sewa' => $biayaSewa,
            'ongkir' => $ongkir,
            'total_biaya' => $totalBiaya,
            'biaya_sewa_formatted' => 'Rp' . number_format($biayaSewa, 0, ',', '.'),
            'ongkir_formatted' => 'Rp' . number_format($ongkir, 0, ',', '.'),
            'total_biaya_formatted' => 'Rp' . number_format($totalBiaya, 0, ',', '.'),
        ]);
    }
}

