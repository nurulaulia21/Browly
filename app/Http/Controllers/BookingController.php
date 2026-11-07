<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Booking;
use App\Services\BookingService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BookingController extends Controller
{
    public function __construct(
        protected BookingService $bookingService
    ) {}

    public function store(Request $request, Barang $barang): RedirectResponse
    {
        $user = Auth::user();

        if ($barang->user_id === $user->id) {
            return back()->with('error', 'Anda tidak dapat menyewa barang milik Anda sendiri.');
        }

        $validated = $request->validate([
            'tanggal_mulai' => ['required', 'date', 'after_or_equal:today'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'metode_pengambilan' => ['required', 'in:ambil_sendiri,diantar'],
            'alamat_pengantaran' => ['required_if:metode_pengambilan,diantar', 'nullable', 'string', 'max:500'],
            'jarak_km' => ['required_if:metode_pengambilan,diantar', 'nullable', 'numeric', 'min:0.5', 'max:50'],
            'metode_pembayaran' => ['required', 'in:qris,cash'],
            'catatan_penyewa' => ['nullable', 'string', 'max:500'],
        ]);

        // Validasi ulang di server agar pilihan yang dikirim sesuai pengaturan pemilik.
        if (! in_array($barang->metode_pengambilan, ['both', $validated['metode_pengambilan']], true)) {
            return back()->withInput()->with('error', 'Metode pengambilan tersebut tidak disediakan oleh pemilik barang.');
        }

        if (! in_array($barang->metode_pembayaran, ['both', $validated['metode_pembayaran']], true)) {
            return back()->withInput()->with('error', 'Metode pembayaran tersebut tidak tersedia untuk barang ini.');
        }

        try {
            $booking = $this->bookingService->createBooking($barang, $user, $validated);

            return redirect()->route('booking.show', $booking->booking_code)
                ->with('success', 'Booking berhasil diajukan! Menunggu persetujuan dari pemilik dalam 24 jam.');
        } catch (Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(string $code): View
    {
        $booking = Booking::with(['barang.kategori', 'barang.lokasi', 'penyewa', 'pemilik', 'pembayaran', 'pengembalian.dendas', 'review'])
            ->where('booking_code', $code)
            ->firstOrFail();

        $user = Auth::user();

        if ($user->id !== $booking->penyewa_id && $user->id !== $booking->pemilik_id && ! $user->isAdmin()) {
            abort(403, 'Anda tidak berhak melihat transaksi ini.');
        }

        return view('booking.show', compact('booking'));
    }
}

