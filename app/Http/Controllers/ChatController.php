<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function show(string $code): View|RedirectResponse
    {
        $booking = Booking::with(['barang', 'penyewa', 'pemilik', 'chatRoom.messages.sender'])
            ->where('booking_code', $code)
            ->firstOrFail();

        $user = Auth::user();

        if ($user->id !== $booking->penyewa_id && $user->id !== $booking->pemilik_id && ! $user->isAdmin()) {
            abort(403, 'Anda tidak memiliki akses ke ruang chat transaksi ini.');
        }

        if (! $booking->isChatActive()) {
            return redirect()->route('booking.show', $booking->booking_code)
                ->with('error', 'Ruang chat transaksi belum aktif. Sesuai aturan Browly, komunikasi chat hanya dapat dibuka setelah pembayaran transaksi dikonfirmasi.');
        }

        $chatRoom = $booking->chatRoom ?? ChatRoom::firstOrCreate([
            'booking_id' => $booking->id,
            'penyewa_id' => $booking->penyewa_id,
            'pemilik_id' => $booking->pemilik_id,
        ], ['is_active' => true]);

        ChatMessage::where('chat_room_id', $chatRoom->id)
            ->where('sender_id', '!=', $user->id)
            ->whereNull('read_at')
            ->update(['read_at' => Carbon::now()]);

        $messages = $chatRoom->messages()->with('sender')->oldest()->get();
        $otherUser = $user->id === $booking->penyewa_id ? $booking->pemilik : $booking->penyewa;

        return view('chat.show', compact('booking', 'chatRoom', 'messages', 'otherUser'));
    }

    public function sendMessage(Request $request, string $code): RedirectResponse
    {
        $booking = Booking::where('booking_code', $code)->firstOrFail();
        $user = Auth::user();

        if ($user->id !== $booking->penyewa_id && $user->id !== $booking->pemilik_id && ! $user->isAdmin()) {
            abort(403);
        }

        if (! $booking->isChatActive()) {
            return back()->with('error', 'Chat belum aktif karena pembayaran belum selesai.');
        }

        $request->validate([
            'pesan' => ['required', 'string', 'max:1000'],
        ]);

        $chatRoom = $booking->chatRoom;
        if (! $chatRoom) {
            $chatRoom = ChatRoom::create([
                'booking_id' => $booking->id,
                'penyewa_id' => $booking->penyewa_id,
                'pemilik_id' => $booking->pemilik_id,
                'is_active' => true,
            ]);
        }

        ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'sender_id' => $user->id,
            'pesan' => trim($request->pesan),
        ]);

        return redirect()->route('chat.show', $booking->booking_code)
            ->with('success', 'Pesan terkirim.');
    }
}

