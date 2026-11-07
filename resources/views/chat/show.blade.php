@extends('layouts.app')

@section('title', 'Chat Transaksi ' . $booking->booking_code . ' - Browly')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <div class="bg-white rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/60 overflow-hidden flex flex-col h-[78vh]">
        <!-- Chat Header -->
        <div class="p-4 sm:p-5 bg-gradient-to-r from-slate-950 via-slate-900 to-slate-950 text-white flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-3">
                <a href="{{ route('booking.show', $booking->booking_code) }}"
                   class="p-2.5 rounded-xl bg-slate-800 hover:bg-slate-700 text-slate-300 transition duration-150">
                    <i data-lucide="arrow-left" class="w-4 h-4"></i>
                </a>
                <div class="w-10 h-10 rounded-2xl bg-gradient-to-br from-emerald-500 to-teal-600 text-white font-black flex items-center justify-center text-sm shadow-xs">
                    {{ strtoupper(substr($otherUser->name, 0, 2)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h2 class="text-sm font-black text-white">
                            {{ $otherUser->name }}
                        </h2>
                        <span class="text-[10px] font-bold uppercase px-2 py-0.5 rounded-full {{ $otherUser->id === $booking->pemilik_id ? 'bg-indigo-500/20 text-indigo-300 border border-indigo-500/30' : 'bg-emerald-500/20 text-emerald-300 border border-emerald-500/30' }}">
                            {{ $otherUser->id === $booking->pemilik_id ? 'Pemilik Barang' : 'Penyewa' }}
                        </span>
                    </div>
                    <span class="text-xs text-slate-400 block font-normal mt-0.5 truncate max-w-xs sm:max-w-md">
                        Transaksi: <span class="font-mono text-slate-300">{{ $booking->booking_code }}</span> • {{ $booking->barang->nama_barang }}
                    </span>
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-1.5 text-xs text-emerald-400 bg-emerald-500/10 px-3.5 py-1.5 rounded-xl border border-emerald-500/20 font-bold">
                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                <span>Chat Aktif & Resmi</span>
            </div>
        </div>

        <!-- Chat Subheader Notice -->
        <div class="bg-emerald-50/80 px-4 sm:px-6 py-2.5 border-b border-emerald-100 flex items-center justify-between text-xs text-emerald-900">
            <div class="flex items-center gap-2 font-medium">
                <i data-lucide="shield-check" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                <span class="text-[11px] sm:text-xs">Gunakan ruang obrolan ini untuk menyepakati lokasi & waktu serah terima barang sewaan.</span>
            </div>
            <a href="{{ route('booking.show', $booking->booking_code) }}" class="font-extrabold text-emerald-700 hover:underline text-xs whitespace-nowrap ml-2">
                Rincian Sewa &rarr;
            </a>
        </div>

        <!-- Messages Stream -->
        <div id="messageStream" class="flex-grow p-4 sm:p-6 overflow-y-auto space-y-4 bg-slate-50/60">
            @forelse($messages as $msg)
                @php
                    $isMe = $msg->sender_id === auth()->id();
                @endphp
                <div class="flex flex-col {{ $isMe ? 'items-end' : 'items-start' }}">
                    <div class="max-w-xs sm:max-w-md px-4 py-3 rounded-2xl shadow-xs text-xs sm:text-sm {{ $isMe ? 'bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-br-2xs' : 'bg-white text-slate-800 border border-slate-200/90 rounded-bl-2xs' }}">
                        <p class="leading-relaxed whitespace-pre-wrap font-normal">{{ $msg->pesan }}</p>
                    </div>
                    <div class="text-[10px] text-slate-400 mt-1 flex items-center gap-1 font-medium px-1">
                        <span>{{ $msg->created_at->format('H:i') }}</span>
                        @if($isMe)
                            <i data-lucide="check-check" class="w-3.5 h-3.5 text-emerald-500"></i>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-16 text-slate-400">
                    <div class="w-14 h-14 rounded-2xl bg-white border border-slate-200 text-slate-300 flex items-center justify-center mx-auto mb-3 shadow-xs">
                        <i data-lucide="message-square" class="w-7 h-7"></i>
                    </div>
                    <p class="text-sm font-bold text-slate-600">Belum ada percakapan</p>
                    <p class="text-xs text-slate-400 mt-0.5">Kirim pesan pertama untuk memulai koordinasi serah terima barang.</p>
                </div>
            @endforelse
        </div>

        <!-- Chat Input Bar -->
        <div class="p-3 sm:p-4 bg-white border-t border-slate-200/80">
            <form action="{{ route('chat.send', $booking->booking_code) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <input type="text" name="pesan" required autocomplete="off"
                       placeholder="Ketik pesan untuk koordinasi serah terima..."
                       class="flex-grow text-xs sm:text-sm px-4 py-3 rounded-2xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50 focus:bg-white font-medium transition duration-150">
                <button type="submit"
                        class="px-5 sm:px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white text-xs sm:text-sm font-extrabold shadow-md shadow-emerald-600/25 transition-all duration-200 flex items-center gap-1.5 shrink-0 hover:-translate-y-0.5">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    <span class="hidden sm:inline">Kirim</span>
                </button>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const stream = document.getElementById('messageStream');
    if (stream) {
        stream.scrollTop = stream.scrollHeight;
    }
</script>
@endpush
@endsection
