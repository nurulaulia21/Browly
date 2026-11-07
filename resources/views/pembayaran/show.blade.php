@extends('layouts.app')

@section('title', 'Simulasi Pembayaran Transaksi ' . $booking->booking_code . ' - Browly')

@section('content')
<div class="relative max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-10 sm:py-12 overflow-hidden">
    <!-- Ambient Background -->
    <div class="pointer-events-none absolute top-10 left-1/2 -translate-x-1/2 w-[500px] h-[300px] bg-gradient-to-tr from-emerald-400/20 via-blue-400/15 to-teal-300/15 blur-3xl rounded-full opacity-60"></div>

    <div class="text-center mb-8 relative z-10">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-blue-100/80 text-blue-800 border border-blue-200/60 mb-3 shadow-xs">
            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-blue-600"></i>
            <span>Simulasi Pembayaran Transaksi</span>
        </span>
        <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Checkout Pembayaran Sewa</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-1">
            Kode Transaksi: <strong class="text-slate-800 font-mono bg-slate-100 px-2 py-0.5 rounded-md">{{ $booking->booking_code }}</strong>
        </p>
    </div>

    <div class="bg-white/95 backdrop-blur-xl rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/70 overflow-hidden relative z-10">
        <!-- Top Bill Amount Header -->
        <div class="bg-gradient-to-br from-slate-950 via-slate-900 to-slate-950 p-6 sm:p-7 text-white flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-slate-800">
            <div>
                <span class="text-[10px] font-extrabold uppercase tracking-wider text-slate-400 block">Total Tagihan Pembayaran</span>
                <div class="text-3xl sm:text-4xl font-black text-emerald-400 mt-1">
                    Rp{{ number_format($booking->total_biaya, 0, ',', '.') }}
                </div>
            </div>
            <div class="sm:text-right text-xs text-slate-400">
                <span class="block text-slate-200 font-bold text-sm">{{ $booking->barang->nama_barang }}</span>
                <span class="inline-block mt-0.5 bg-slate-800 px-2 py-0.5 rounded text-[11px] text-slate-300">{{ $booking->jumlah_hari }} Hari Durasi Sewa</span>
            </div>
        </div>

        <div class="p-6 sm:p-8 space-y-6">
            @if($booking->metode_pembayaran === 'qris')
                <div class="text-center space-y-5">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-emerald-50 text-emerald-800 font-bold text-xs border border-emerald-200/80">
                        <i data-lucide="qr-code" class="w-4 h-4 text-emerald-600"></i>
                        <span>Metode: QRIS Pembayaran Instan</span>
                    </div>

                    <!-- QR Scanner Aesthetic Box -->
                    <div class="p-6 bg-slate-50/80 rounded-3xl border border-slate-200/80 max-w-xs mx-auto shadow-inner flex flex-col items-center">
                        <div class="text-[10px] font-black text-slate-500 tracking-wider uppercase mb-3 flex items-center gap-1.5">
                            <i data-lucide="scan" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>SCAN QRIS BROWLY</span>
                        </div>

                        <div class="p-4 bg-white rounded-2xl shadow-sm border border-slate-200/90 relative group">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=BROWLY-PAY-{{ $booking->booking_code }}-{{ $booking->total_biaya }}"
                                 alt="QRIS Code" class="w-48 h-48 mx-auto rounded-lg">
                        </div>

                        <span class="text-[10px] text-slate-400 mt-3 font-mono">NMID: ID102026RPLO0001</span>
                    </div>

                    <!-- Instructions Card -->
                    <div class="p-4 rounded-2xl bg-blue-50/80 border border-blue-100 text-xs text-blue-900 text-left space-y-1">
                        <div class="font-bold flex items-center gap-1.5 text-blue-800">
                            <i data-lucide="info" class="w-4 h-4 text-blue-600 shrink-0"></i>
                            <span>Petunjuk Pembayaran Simulasi:</span>
                        </div>
                        <p class="text-[11px] leading-relaxed text-blue-800/90 font-normal">
                            Sistem ini menggunakan simulasi transaksi perancangan RPLO. Klik tombol di bawah untuk mensimulasikan pembayaran lunas dan membuka ruang chat koordinasi secara otomatis.
                        </p>
                    </div>

                    <!-- Action Button -->
                    <form action="{{ route('pembayaran.process', $booking->booking_code) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-sm shadow-lg shadow-emerald-600/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span>Simulasikan Bayar QRIS Berhasil (Lunas)</span>
                        </button>
                    </form>
                </div>
            @else
                <div class="space-y-5 text-center">
                    <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full bg-amber-50 text-amber-800 font-bold text-xs border border-amber-200/80">
                        <i data-lucide="banknote" class="w-4 h-4 text-amber-600"></i>
                        <span>Metode: Pembayaran Tunai (Cash on Delivery / Pickup)</span>
                    </div>

                    <div class="p-6 bg-slate-50/80 rounded-3xl border border-slate-200 text-left space-y-3.5 text-xs text-slate-700">
                        <h4 class="font-black text-sm text-slate-900 flex items-center gap-2">
                            <i data-lucide="handshake" class="w-4 h-4 text-indigo-600"></i>
                            <span>Serah Terima Pembayaran Tunai</span>
                        </h4>
                        <p class="font-normal text-slate-600">
                            Anda memilih pembayaran tunai langsung kepada pemilik (<strong>{{ $booking->pemilik->name }}</strong>) sejumlah:
                        </p>
                        <div class="text-2xl font-black text-slate-900 bg-white p-4 rounded-2xl border border-slate-200 text-center shadow-2xs">
                            Rp{{ number_format($booking->total_biaya, 0, ',', '.') }}
                        </div>
                        <ul class="list-disc list-inside space-y-1 text-slate-500 text-[11px] font-normal">
                            <li>Uang tunai diserahkan saat pengambilan atau barang tiba di tujuan.</li>
                            <li>Klik tombol konfirmasi di bawah untuk mengaktifkan ruang chat resmi untuk koordinasi titik temu.</li>
                        </ul>
                    </div>

                    <form action="{{ route('pembayaran.process', $booking->booking_code) }}" method="POST">
                        @csrf
                        <button type="submit"
                                class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-black text-sm shadow-lg shadow-emerald-600/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i data-lucide="check-circle" class="w-4 h-4"></i>
                            <span>Konfirmasi Pembayaran Tunai & Buka Chat</span>
                        </button>
                    </form>
                </div>
            @endif

            <div class="text-center pt-2">
                <a href="{{ route('booking.show', $booking->booking_code) }}"
                   class="text-xs font-bold text-slate-400 hover:text-slate-600 transition flex items-center justify-center gap-1">
                    <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
                    <span>Kembali ke Rincian Booking</span>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
