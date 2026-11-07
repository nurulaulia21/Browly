@extends('layouts.app')

@section('title', 'Sewa Saya - Dashboard Penyewa Browly')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <!-- Header -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100/70 text-emerald-800">
                    <i data-lucide="user-check" class="w-3.5 h-3.5"></i> Dashboard Penyewa
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">
                Halo, {{ auth()->user()->name }} 👋
            </h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Pantau pengajuan booking, selesaikan pembayaran, koordinasi via chat resmi, dan kelola pengembalian barang sewaan Anda.
            </p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('barangs.index') }}"
               class="px-4 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all duration-200 flex items-center gap-2 hover:-translate-y-0.5">
                <i data-lucide="search" class="w-4 h-4"></i>
                <span>Cari Barang Lainnya</span>
            </a>
        </div>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <!-- Menunggu Persetujuan -->
        <div class="p-5 bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:border-amber-300 transition duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Menunggu Respon</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-amber-500 mt-2">{{ $stats['menunggu_persetujuan'] }}</div>
            <span class="text-[10px] text-amber-700 font-semibold block mt-1">Batas pemilik: 24 jam</span>
        </div>

        <!-- Menunggu Pembayaran -->
        <div class="p-5 bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:border-blue-300 transition duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Perlu Dibayar</span>
                <div class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <i data-lucide="credit-card" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-blue-600 mt-2">{{ $stats['menunggu_pembayaran'] }}</div>
            <span class="text-[10px] text-blue-700 font-semibold block mt-1">Batas bayar: 24 jam</span>
        </div>

        <!-- Sewa Aktif -->
        <div class="p-5 bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:border-emerald-300 transition duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Sewa Aktif</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                    <i data-lucide="package" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-emerald-600 mt-2">{{ $stats['aktif'] }}</div>
            <span class="text-[10px] text-emerald-700 font-semibold block mt-1">Sedang berlangsung</span>
        </div>

        <!-- Selesai -->
        <div class="p-5 bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:border-teal-300 transition duration-200">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Selesai</span>
                <div class="w-8 h-8 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <i data-lucide="check-circle" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-teal-700 mt-2">{{ $stats['selesai'] }}</div>
            <span class="text-[10px] text-slate-400 font-medium block mt-1">Transaksi tuntas</span>
        </div>

        <!-- Total Denda -->
        <div class="p-5 bg-white rounded-3xl border border-slate-200/90 shadow-2xs hover:border-rose-300 transition duration-200 col-span-2 lg:col-span-1">
            <div class="flex items-center justify-between">
                <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Total Denda</span>
                <div class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                    <i data-lucide="alert-triangle" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="text-xl sm:text-2xl font-black text-rose-600 mt-2">
                Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}
            </div>
            <span class="text-[10px] text-rose-700 font-semibold block mt-1">Keterlambatan/rusak</span>
        </div>
    </div>

    <!-- Filter Tab Bar -->
    <div class="flex items-center gap-2 overflow-x-auto pb-3 mb-6 border-b border-slate-200/80 text-xs font-bold">
        <a href="{{ route('penyewa.dashboard', ['tab' => 'semua']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $tab == 'semua' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <span>Semua Transaksi</span>
        </a>

        <a href="{{ route('penyewa.dashboard', ['tab' => 'menunggu_persetujuan']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $tab == 'menunggu_persetujuan' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <span>Menunggu Respon</span>
            @if($stats['menunggu_persetujuan'] > 0)
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-amber-500 text-white font-extrabold">{{ $stats['menunggu_persetujuan'] }}</span>
            @endif
        </a>

        <a href="{{ route('penyewa.dashboard', ['tab' => 'menunggu_pembayaran']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $tab == 'menunggu_pembayaran' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <span>Perlu Bayar</span>
            @if($stats['menunggu_pembayaran'] > 0)
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-blue-600 text-white font-extrabold">{{ $stats['menunggu_pembayaran'] }}</span>
            @endif
        </a>

        <a href="{{ route('penyewa.dashboard', ['tab' => 'aktif']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $tab == 'aktif' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <span>Sewa Berjalan</span>
            @if($stats['aktif'] > 0)
                <span class="px-1.5 py-0.5 rounded-full text-[10px] bg-emerald-600 text-white font-extrabold">{{ $stats['aktif'] }}</span>
            @endif
        </a>

        <a href="{{ route('penyewa.dashboard', ['tab' => 'pengembalian']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $tab == 'pengembalian' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <span>Pengembalian</span>
        </a>

        <a href="{{ route('penyewa.dashboard', ['tab' => 'selesai']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $tab == 'selesai' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <span>Selesai</span>
        </a>

        <a href="{{ route('penyewa.dashboard', ['tab' => 'dibatalkan']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 whitespace-nowrap {{ $tab == 'dibatalkan' ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-600 hover:bg-slate-100' }}">
            <span>Dibatalkan</span>
        </a>
    </div>

    <!-- Booking List -->
    @if($bookings->count() > 0)
        <div class="space-y-5">
            @foreach($bookings as $booking)
                <div class="bg-white rounded-3xl border border-slate-200/90 p-5 sm:p-6 shadow-xs hover:border-emerald-300 transition-all duration-200">
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-4 border-b border-slate-100">
                        <div class="flex items-center gap-3">
                            <span class="font-mono text-xs font-black text-slate-800 bg-slate-100 px-3 py-1 rounded-xl">
                                {{ $booking->booking_code }}
                            </span>
                            <span class="text-xs text-slate-400 font-medium">
                                Diajukan {{ $booking->created_at->format('d M Y, H:i') }}
                            </span>
                        </div>

                        <!-- Status Badge -->
                        <div>
                            @php
                                $badgeStyle = match($booking->status) {
                                    'MENUNGGU_PERSETUJUAN' => 'bg-amber-100 text-amber-800 border-amber-200',
                                    'DISETUJUI', 'MENUNGGU_PEMBAYARAN' => 'bg-blue-100 text-blue-800 border-blue-200',
                                    'DIBAYAR', 'AKTIF' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                    'DIKEMBALIKAN' => 'bg-purple-100 text-purple-800 border-purple-200',
                                    'SELESAI' => 'bg-teal-100 text-teal-800 border-teal-200',
                                    'DITOLAK', 'DIBATALKAN' => 'bg-rose-100 text-rose-800 border-rose-200',
                                    default => 'bg-slate-100 text-slate-800 border-slate-200',
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-xl text-xs font-bold border {{ $badgeStyle }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $booking->status_label }}
                            </span>
                        </div>
                    </div>

                    <!-- Booking Body -->
                    <div class="mt-4 grid grid-cols-1 md:grid-cols-12 gap-5 items-center">
                        <!-- Image & Basic Item Info -->
                        <div class="md:col-span-5 flex items-center gap-4">
                            <img src="{{ $booking->barang->foto }}" alt="{{ $booking->barang->nama_barang }}"
                                 class="w-20 h-20 rounded-2xl object-cover shrink-0 border border-slate-200/80">
                            <div>
                                <h3 class="font-extrabold text-sm sm:text-base text-slate-900 line-clamp-1 hover:text-emerald-600 transition">
                                    <a href="{{ route('barangs.show', $booking->barang->slug) }}">
                                        {{ $booking->barang->nama_barang }}
                                    </a>
                                </h3>
                                <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                    <i data-lucide="store" class="w-3.5 h-3.5 text-indigo-500"></i>
                                    <span>Pemilik: <strong>{{ $booking->pemilik->name }}</strong></span>
                                </div>
                                <div class="text-[11px] text-slate-400 mt-0.5 flex items-center gap-1">
                                    <i data-lucide="map-pin" class="w-3 h-3 text-rose-500"></i>
                                    <span>{{ $booking->barang->lokasi->kecamatan }}, {{ $booking->barang->lokasi->kota }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- Date & Schedule Details -->
                        <div class="md:col-span-4 text-xs space-y-1.5 p-3.5 rounded-2xl bg-slate-50 border border-slate-100">
                            <div class="flex justify-between text-slate-600">
                                <span>Periode Sewa:</span>
                                <span class="font-bold text-slate-800">
                                    {{ \Carbon\Carbon::parse($booking->tanggal_mulai)->format('d M') }} - {{ \Carbon\Carbon::parse($booking->tanggal_selesai)->format('d M Y') }}
                                </span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>Durasi:</span>
                                <span class="font-bold text-slate-800">{{ $booking->jumlah_hari }} Hari</span>
                            </div>
                            <div class="flex justify-between text-slate-600">
                                <span>Pengambilan:</span>
                                <span class="font-bold text-slate-800">
                                    {{ $booking->metode_pengambilan === 'diantar' ? 'Diantar (+Ongkir)' : 'Ambil Sendiri' }}
                                </span>
                            </div>
                            <div class="pt-1.5 border-t border-slate-200 flex justify-between text-slate-900 font-extrabold">
                                <span>Total Tagihan:</span>
                                <span class="text-emerald-600">Rp{{ number_format($booking->total_biaya, 0, ',', '.') }}</span>
                            </div>
                        </div>

                        <!-- Actions Column -->
                        <div class="md:col-span-3 flex flex-col gap-2 justify-center">
                            <!-- Pay Now Action -->
                            @if(in_array($booking->status, ['DISETUJUI', 'MENUNGGU_PEMBAYARAN']))
                                <a href="{{ route('pembayaran.show', $booking->booking_code) }}"
                                   class="w-full py-2.5 px-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 text-white font-extrabold text-xs shadow-sm transition flex items-center justify-center gap-1.5">
                                    <i data-lucide="credit-card" class="w-3.5 h-3.5"></i>
                                    <span>Bayar Sekarang (24 Jam)</span>
                                </a>
                            @endif

                            <!-- Chat Action (when paid or active) -->
                            @if(in_array($booking->status, ['DIBAYAR', 'AKTIF', 'DIKEMBALIKAN', 'SELESAI']))
                                <a href="{{ route('chat.show', $booking->booking_code) }}"
                                   class="w-full py-2.5 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition flex items-center justify-center gap-1.5">
                                    <i data-lucide="message-square" class="w-3.5 h-3.5 text-emerald-400"></i>
                                    <span>Chat Pemilik</span>
                                </a>
                            @endif

                            <!-- Return Item Action (when active) -->
                            @if(in_array($booking->status, ['DIBAYAR', 'AKTIF']))
                                <button type="button"
                                        onclick="openReturnModal({{ $booking->id }}, '{{ $booking->barang->nama_barang }}')"
                                        class="w-full py-2 px-3 rounded-xl bg-purple-50 hover:bg-purple-100 text-purple-700 border border-purple-200 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i data-lucide="corner-down-left" class="w-3.5 h-3.5"></i>
                                    <span>Ajukan Pengembalian</span>
                                </button>
                            @endif

                            <!-- Leave Review (when completed and not reviewed yet) -->
                            @if($booking->status === 'SELESAI' && !$booking->review)
                                <button type="button"
                                        onclick="openReviewModal({{ $booking->id }}, '{{ $booking->barang->nama_barang }}')"
                                        class="w-full py-2 px-3 rounded-xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200 font-bold text-xs transition flex items-center justify-center gap-1.5">
                                    <i data-lucide="star" class="w-3.5 h-3.5 text-amber-500 fill-amber-400"></i>
                                    <span>Beri Review & Rating</span>
                                </button>
                            @endif

                            <!-- Details Link -->
                            <a href="{{ route('booking.show', $booking->booking_code) }}"
                               class="w-full py-2 px-3 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs text-center transition">
                                Rincian Booking &rarr;
                            </a>
                        </div>
                    </div>

                    <!-- Extra Notice / Warnings -->
                    @if(in_array($booking->status, ['DISETUJUI', 'MENUNGGU_PEMBAYARAN']))
                        <div class="mt-4 p-3 rounded-2xl bg-blue-50/80 border border-blue-100 text-[11px] text-blue-900 flex items-center justify-between">
                            <span class="flex items-center gap-1.5">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-blue-600 shrink-0"></i>
                                <span>Batas waktu pembayaran otomatis berakhir: <strong>{{ $booking->expires_at_payment ? $booking->expires_at_payment->format('d M Y, H:i') : '-' }}</strong></span>
                            </span>
                            <a href="{{ route('pembayaran.show', $booking->booking_code) }}" class="font-extrabold text-blue-700 hover:underline">
                                Bayar Sekarang
                            </a>
                        </div>
                    @endif

                    @if($booking->dendas->count() > 0)
                        <div class="mt-4 p-3 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-900 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <i data-lucide="alert-octagon" class="w-4 h-4 text-rose-600 shrink-0"></i>
                                <span>Catatan Denda Transaksi: <strong>Rp{{ number_format($booking->dendas->sum('nominal'), 0, ',', '.') }}</strong></span>
                            </div>
                            <span class="text-[10px] text-rose-700 font-bold uppercase">Lihat di rincian sewa</span>
                        </div>
                    @endif
                </div>
            @endforeach

            <div class="mt-8">
                {{ $bookings->links() }}
            </div>
        </div>
    @else
        <div class="bg-white p-12 text-center rounded-3xl border border-slate-200/90 shadow-2xs">
            <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                <i data-lucide="package-open" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base font-extrabold text-slate-900">Belum ada transaksi di tab ini</h3>
            <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                Temukan barang kebutuhan Anda di katalog atau ajukan penyewaan barang baru untuk memulai.
            </p>
            <a href="{{ route('barangs.index') }}"
               class="inline-flex items-center gap-2 mt-5 px-5 py-2.5 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-xs font-bold shadow-md shadow-emerald-600/20 hover:from-emerald-500 hover:to-teal-500 transition">
                <i data-lucide="search" class="w-3.5 h-3.5"></i>
                <span>Jelajahi Katalog Barang</span>
            </a>
        </div>
    @endif
</div>

<!-- Modal Ajukan Pengembalian -->
<div id="modalPengembalian" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h4 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i data-lucide="corner-down-left" class="w-4 h-4 text-purple-600"></i>
                <span>Ajukan Pengembalian Barang</span>
            </h4>
            <button type="button" onclick="closeReturnModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <p class="text-xs text-slate-500 mb-4" id="returnItemName"></p>

        <form id="formReturn" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                    Tanggal Pengembalian Aktual
                </label>
                <input type="date" name="tanggal_pengembalian_aktual" value="{{ date('Y-m-d') }}" required
                       class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500 font-semibold bg-white">
            </div>

            <div>
                <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                    Kondisi Fisik Barang
                </label>
                <select name="kondisi_barang" required
                        class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500 font-medium bg-white">
                    <option value="sesuai_awal">Sesuai Kondisi Awal (Normal / Baik)</option>
                    <option value="rusak_ringan">Ada Kerusakan Ringan / Lecet</option>
                    <option value="rusak_berat">Kerusakan Berat / Tidak Berfungsi</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                    Catatan Tambahan untuk Pemilik
                </label>
                <textarea name="catatan" rows="3" placeholder="Informasikan kondisi serah terima barang..."
                          class="w-full text-xs p-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-purple-500 font-medium"></textarea>
            </div>

            <div class="flex justify-end gap-2.5 pt-2">
                <button type="button" onclick="closeReturnModal()"
                        class="px-4 py-2.5 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-xs font-bold text-white bg-purple-600 hover:bg-purple-700 rounded-xl shadow-xs transition">
                    Kirim Pengajuan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Beri Review -->
<div id="modalReview" class="hidden fixed inset-0 z-50 bg-black/60 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-white rounded-3xl p-6 sm:p-8 max-w-lg w-full shadow-2xl border border-slate-100">
        <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-4">
            <h4 class="text-base font-black text-slate-900 flex items-center gap-2">
                <i data-lucide="star" class="w-4 h-4 text-amber-500 fill-amber-400"></i>
                <span>Beri Penilaian & Review</span>
            </h4>
            <button type="button" onclick="closeReviewModal()" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg">
                <i data-lucide="x" class="w-4 h-4"></i>
            </button>
        </div>

        <p class="text-xs text-slate-500 mb-4" id="reviewItemName"></p>

        <form id="formReview" method="POST" class="space-y-4">
            @csrf
            <div>
                <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                    Rating Bintang (1 - 5)
                </label>
                <div class="flex items-center gap-3">
                    @for($i = 5; $i >= 1; $i--)
                        <label class="flex items-center gap-1 cursor-pointer">
                            <input type="radio" name="rating" value="{{ $i }}" {{ $i === 5 ? 'checked' : '' }} class="text-amber-500 focus:ring-amber-400">
                            <span class="text-xs font-bold text-slate-700 flex items-center gap-0.5">
                                {{ $i }} <i data-lucide="star" class="w-3 h-3 fill-amber-400 text-amber-400"></i>
                            </span>
                        </label>
                    @endfor
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                    Ulasan Pengalaman Anda
                </label>
                <textarea name="komentar" rows="3" required placeholder="Ceritakan kepuasan Anda mengenai barang dan layanan pemilik..."
                          class="w-full text-xs p-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-amber-500 font-medium"></textarea>
            </div>

            <div class="flex justify-end gap-2.5 pt-2">
                <button type="button" onclick="closeReviewModal()"
                        class="px-4 py-2.5 text-xs font-bold text-slate-600 rounded-xl hover:bg-slate-100 transition">
                    Batal
                </button>
                <button type="submit"
                        class="px-5 py-2.5 text-xs font-bold text-white bg-amber-500 hover:bg-amber-600 rounded-xl shadow-xs transition">
                    Kirim Ulasan
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    function openReturnModal(bookingId, itemName) {
        document.getElementById('returnItemName').innerText = 'Barang: ' + itemName;
        document.getElementById('formReturn').action = '/booking/' + bookingId + '/pengembalian';
        document.getElementById('modalPengembalian').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeReturnModal() {
        document.getElementById('modalPengembalian').classList.add('hidden');
    }

    function openReviewModal(bookingId, itemName) {
        document.getElementById('reviewItemName').innerText = 'Barang: ' + itemName;
        document.getElementById('formReview').action = '/booking/' + bookingId + '/review';
        document.getElementById('modalReview').classList.remove('hidden');
        lucide.createIcons();
    }

    function closeReviewModal() {
        document.getElementById('modalReview').classList.add('hidden');
    }
</script>
@endpush
@endsection
