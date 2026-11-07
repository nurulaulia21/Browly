<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Review;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, Booking $booking): RedirectResponse
    {
        $user = Auth::user();

        if ($booking->penyewa_id !== $user->id) {
            abort(403);
        }

        if ($booking->status !== 'SELESAI') {
            return back()->with('error', 'Review hanya dapat diberikan setelah transaksi dinyatakan SELESAI.');
        }

        if ($booking->review) {
            return back()->with('error', 'Anda telah memberikan review untuk transaksi ini.');
        }

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'komentar' => ['required', 'string', 'max:1000'],
        ]);

        Review::create([
            'booking_id' => $booking->id,
            'barang_id' => $booking->barang_id,
            'penyewa_id' => $user->id,
            'pemilik_id' => $booking->pemilik_id,
            'rating' => $validated['rating'],
            'komentar' => $validated['komentar'],
        ]);

        return redirect()->route('booking.show', $booking->booking_code)
            ->with('success', 'Terima kasih atas ulasan dan rating Anda!');
    }
}

