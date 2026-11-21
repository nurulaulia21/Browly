@extends('layouts.app')

@section('title', $barang->nama_barang . ' - Browly')

@section('content')
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <!-- Top Navigation & Back Button -->
    <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
        <a href="{{ route('barangs.index') }}"
           onclick="if (document.referrer && new URL(document.referrer).origin === location.origin && history.length > 1) { history.back(); return false; }"
           class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border border-slate-200/90 text-xs font-bold text-slate-700 hover:text-emerald-700 hover:border-emerald-400 hover:bg-emerald-50/60 shadow-xs transition-all duration-200 group cursor-pointer">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 transition-transform group-hover:-translate-x-1"></i>
            <span>Kembali ke Halaman Sebelumnya</span>
        </a>

        <!-- Breadcrumb -->
        <nav class="flex text-xs text-slate-500 gap-2 items-center font-medium">
            <a href="{{ route('home') }}" class="hover:text-emerald-600 transition flex items-center gap-1">
                <i data-lucide="home" class="w-3.5 h-3.5"></i>
                <span>Beranda</span>
            </a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
            <a href="{{ route('barangs.index', ['kategori' => $barang->kategori->slug]) }}" class="hover:text-emerald-600 transition">
                {{ $barang->kategori->nama }}
            </a>
            <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-400"></i>
            <span class="text-slate-900 font-bold truncate max-w-[200px] sm:max-w-xs">{{ $barang->nama_barang }}</span>
        </nav>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        <!-- Main Left Column: Image, Info, Calendar, Reviews -->
        <div class="lg:col-span-7 space-y-6">
            <!-- Product Photo -->
            <div class="bg-white rounded-3xl border border-slate-200/90 overflow-hidden shadow-sm relative aspect-4/3 sm:aspect-16/10 group">
                <img src="{{ $barang->foto }}" alt="{{ $barang->nama_barang }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-30"></div>

                <div class="absolute top-4 left-4 flex gap-2">
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-extrabold bg-white/95 backdrop-blur text-slate-800 shadow-sm border border-slate-200/60">
                        {{ $barang->kategori->nama }}
                    </span>
                    <span class="px-3.5 py-1.5 rounded-full text-xs font-bold bg-emerald-600/90 backdrop-blur text-white shadow-sm flex items-center gap-1.5">
                        <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                        Kondisi: {{ $barang->kondisi }}
                    </span>
                </div>
            </div>

            <!-- Header Info Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm">
                <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                    <div class="flex items-center gap-2 text-xs">
                        <span class="inline-flex items-center gap-1.5 font-bold text-rose-700 bg-rose-50 px-3 py-1.5 rounded-xl border border-rose-100">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500"></i>
                            {{ $barang->lokasi->kecamatan }}, {{ $barang->lokasi->kota }}
                        </span>
                    </div>
                    <div class="flex items-center gap-1.5 text-sm font-extrabold text-slate-800 bg-amber-50 px-3 py-1.5 rounded-xl border border-amber-200/60">
                        <i data-lucide="star" class="w-4 h-4 fill-amber-400 text-amber-400"></i>
                        <span>{{ $barang->average_rating }}</span>
                        <span class="text-xs font-medium text-slate-400">({{ $barang->reviews_count }} ulasan)</span>
                    </div>
                </div>

                <h1 class="text-2xl sm:text-3xl font-black text-slate-900 leading-tight">
                    {{ $barang->nama_barang }}
                </h1>

                <!-- Quick Specs Grid -->
                <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-3 p-4 rounded-2xl bg-slate-50 border border-slate-100 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-1 font-semibold uppercase tracking-wider text-[10px]">Metode Ambil</span>
                        <span class="font-extrabold text-slate-800">
                            {{ $barang->metode_pengambilan === 'both' ? 'Ambil Sendiri & Diantar' : ($barang->metode_pengambilan === 'diantar' ? 'Diantar Saja' : 'Ambil Sendiri Saja') }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-1 font-semibold uppercase tracking-wider text-[10px]">Metode Pembayaran</span>
                        <span class="font-extrabold text-emerald-700">
                            {{ $barang->metode_pembayaran === 'both' ? 'QRIS & Tunai (Cash)' : strtoupper($barang->metode_pembayaran) }}
                        </span>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-1 font-semibold uppercase tracking-wider text-[10px]">Tarif Ongkir Antar</span>
                        <span class="font-extrabold text-slate-800">
                            Rp{{ number_format($barang->tarif_pengantaran_per_km, 0, ',', '.') }} / km
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm">
                <h3 class="text-base font-extrabold text-slate-900 mb-4 flex items-center gap-2">
                    <i data-lucide="file-text" class="w-4 h-4 text-emerald-600"></i>
                    <span>Deskripsi & Kelengkapan Barang</span>
                </h3>
                <div class="text-xs sm:text-sm text-slate-600 leading-relaxed whitespace-pre-line font-normal">
                    {{ $barang->deskripsi }}
                </div>
            </div>

            <!-- Owner Info Card -->
            <div class="bg-white p-6 sm:p-7 rounded-3xl border border-slate-200/90 shadow-sm flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-indigo-500 to-indigo-700 text-white font-extrabold flex items-center justify-center text-lg shadow-sm">
                        {{ strtoupper(substr($barang->user->name, 0, 2)) }}
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-extrabold uppercase tracking-wider">Pemilik Barang</div>
                        <h4 class="text-sm font-black text-slate-900">{{ $barang->user->name }}</h4>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $barang->user->alamat_lengkap ?? 'Makassar, Sulawesi Selatan' }}</p>
                    </div>
                </div>
                <div class="text-right flex flex-col items-end gap-1">
                    @if(auth()->check() && auth()->id() === $barang->user_id)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-800 border border-emerald-200">
                            <i data-lucide="user-check" class="w-3 h-3 mr-1 text-emerald-700"></i>
                            Barang Milik Anda
                        </span>
                    @endif
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200/60">
                        <i data-lucide="shield-check" class="w-3.5 h-3.5 mr-1 text-indigo-600"></i>
                        <span>Terverifikasi</span>
                    </span>
                </div>
            </div>

            <!-- Availability Calendar Visual Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                            <i data-lucide="calendar" class="w-4 h-4 text-emerald-600"></i>
                            <span>Kalender Ketersediaan Barang</span>
                        </h3>
                        <p class="text-xs text-slate-500 mt-0.5">Sistem mendeteksi jadwal secara real-time untuk mencegah double booking.</p>
                    </div>
                </div>

                <!-- Legend -->
                <div class="flex flex-wrap gap-2.5 mb-6 p-3.5 rounded-2xl bg-slate-50 border border-slate-100 text-xs font-medium">
                    <div class="flex items-center gap-1.5 text-slate-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 inline-block"></span>
                        <span>Tersedia</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-amber-400 inline-block"></span>
                        <span>Menunggu Konfirmasi</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-rose-500 inline-block"></span>
                        <span>Sedang Disewa</span>
                    </div>
                    <div class="flex items-center gap-1.5 text-slate-700">
                        <span class="w-2.5 h-2.5 rounded-full bg-slate-800 inline-block"></span>
                        <span>Dipakai Pemilik</span>
                    </div>
                </div>

                @php
                    $today = \Carbon\Carbon::today();
                @endphp
                <div class="grid grid-cols-7 gap-2 text-center text-xs">
                    @foreach(['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'] as $dayName)
                        <div class="font-bold text-slate-400 py-1 uppercase text-[10px] tracking-wider">{{ $dayName }}</div>
                    @endforeach

                    @for ($i = 0; $i < 28; $i++)
                        @php
                            $currentDate = $today->copy()->addDays($i);
                            $dateStr = $currentDate->toDateString();

                            $isBlocked = $barang->blockedDates->contains(function ($item) use ($dateStr) {
                                return $dateStr >= $item->start_date && $dateStr <= $item->end_date;
                            });

                            $activeBooking = $activeBookings->first(function ($b) use ($dateStr) {
                                return $dateStr >= $b->tanggal_mulai && $dateStr <= $b->tanggal_selesai;
                            });

                            $statusColor = 'bg-emerald-50/80 text-emerald-900 border-emerald-200/80 hover:bg-emerald-100/80';
                            $dotColor = 'bg-emerald-500';
                            $titleText = 'Tersedia untuk disewa';

                            if ($isBlocked) {
                                $statusColor = 'bg-slate-900 text-white border-slate-900';
                                $dotColor = 'bg-slate-400';
                                $titleText = 'Tidak tersedia (Digunakan Pemilik)';
                            } elseif ($activeBooking) {
                                if (in_array($activeBooking->status, ['DIBAYAR', 'AKTIF'])) {
                                    $statusColor = 'bg-rose-50 text-rose-800 border-rose-200';
                                    $dotColor = 'bg-rose-500';
                                    $titleText = 'Sedang disewa';
                                } else {
                                    $statusColor = 'bg-amber-50 text-amber-800 border-amber-200';
                                    $dotColor = 'bg-amber-400';
                                    $titleText = 'Menunggu persetujuan / konfirmasi';
                                }
                            }
                        @endphp

                        <div title="{{ $dateStr }}: {{ $titleText }}"
                             class="p-2.5 rounded-2xl border flex flex-col items-center justify-between {{ $statusColor }} transition-all duration-200 hover:scale-105 shadow-2xs">
                            <span class="text-[9px] uppercase font-bold opacity-60">{{ $currentDate->format('M') }}</span>
                            <span class="font-extrabold text-sm my-0.5">{{ $currentDate->format('d') }}</span>
                            <span class="w-1.5 h-1.5 rounded-full {{ $dotColor }}"></span>
                        </div>
                    @endfor
                </div>

                <p class="text-[11px] text-slate-400 mt-5 text-center font-normal">
                    *Jadwal tanggal sewa saling mengunci secara real-time untuk menjamin kenyamanan Anda.
                </p>
            </div>

            <!-- Reviews Card -->
            <div class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200/90 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <h3 class="text-base font-extrabold text-slate-900 flex items-center gap-2">
                        <i data-lucide="message-circle" class="w-4 h-4 text-emerald-600"></i>
                        <span>Ulasan & Penilaian Penyewa</span>
                    </h3>
                    <span class="text-xs font-bold text-slate-600 bg-slate-100 px-2.5 py-1 rounded-lg">
                        {{ $barang->reviews->count() }} Ulasan
                    </span>
                </div>

                @if($barang->reviews->count() > 0)
                    <div class="space-y-4">
                        @foreach($barang->reviews as $rev)
                            <div class="p-4 sm:p-5 rounded-2xl bg-slate-50/70 border border-slate-100">
                                <div class="flex items-center justify-between mb-2">
                                    <div class="flex items-center gap-2.5">
                                        <div class="w-8 h-8 rounded-xl bg-slate-200 text-slate-700 font-bold flex items-center justify-center text-xs">
                                            {{ strtoupper(substr($rev->penyewa->name ?? 'P', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="text-xs font-bold text-slate-900 block">{{ $rev->penyewa->name ?? 'Penyewa' }}</span>
                                            <span class="text-[10px] text-slate-400">{{ $rev->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    <div class="flex items-center gap-1 text-xs font-bold text-amber-500 bg-amber-50 px-2 py-0.5 rounded-md">
                                        <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400"></i>
                                        <span>{{ $rev->rating }}/5</span>
                                    </div>
                                </div>
                                <p class="text-xs text-slate-600 leading-relaxed font-normal mt-2">{{ $rev->komentar }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-2">
                            <i data-lucide="sparkles" class="w-6 h-6"></i>
                        </div>
                        <p class="text-xs text-slate-400 font-medium">Belum ada ulasan untuk barang ini. Jadilah penyewa pertama!</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sticky Booking Box (Right Column) -->
        <div class="lg:col-span-5">
            <div class="bg-white/95 backdrop-blur-xl p-6 sm:p-7 rounded-3xl border border-slate-200/90 shadow-xl shadow-slate-200/60 sticky top-24">
                <!-- Price Display Header -->
                <div class="pb-5 border-b border-slate-100 mb-6">
                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider block">Tarif Sewa Barang</span>
                    <div class="flex items-baseline gap-1 mt-1">
                        <span class="text-3xl sm:text-4xl font-black text-emerald-600">
                            Rp{{ number_format($barang->harga_sewa_per_hari, 0, ',', '.') }}
                        </span>
                        <span class="text-xs text-slate-500 font-semibold">/ hari</span>
                    </div>
                </div>

                @auth
                    @if(auth()->id() === $barang->user_id && session('mode') === 'pemilik')
                        {{-- Tab Switcher: Kelola Pemilik vs Pratinjau Penyewa --}}
                        <div class="flex items-center p-1 bg-slate-100 rounded-2xl mb-6 text-xs font-bold">
                            <button type="button" id="tabOwnerBtn" onclick="toggleOwnerView('owner')"
                                    class="flex-1 py-2 px-3 rounded-xl bg-white text-slate-900 shadow-xs flex items-center justify-center gap-1.5 transition cursor-pointer">
                                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                                <span>Kelola Barang</span>
                            </button>
                            <button type="button" id="tabPreviewBtn" onclick="toggleOwnerView('preview')"
                                    class="flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5 transition cursor-pointer">
                                <i data-lucide="eye" class="w-3.5 h-3.5"></i>
                                <span>Pratinjau Penyewa</span>
                            </button>
                        </div>

                        {{-- Panel 1: Kelola Barang (Pemilik) --}}
                        <div id="ownerControlPanel" class="space-y-5">
                            {{-- Status Barang & Saklar Cepat --}}
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                                <div class="flex items-center justify-between mb-2">
                                    <span class="text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">Status Barang</span>
                                    @php
                                        $badgeColor = match($barang->status) {
                                            'tersedia' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
                                            'tidak_tersedia' => 'bg-amber-100 text-amber-800 border-amber-200',
                                            'nonaktif' => 'bg-slate-200 text-slate-700 border-slate-300',
                                            default => 'bg-slate-100 text-slate-700 border-slate-200',
                                        };
                                        $badgeText = match($barang->status) {
                                            'tersedia' => 'Tersedia di Katalog',
                                            'tidak_tersedia' => 'Sedang Tidak Tersedia',
                                            'nonaktif' => 'Nonaktif dari Katalog',
                                            default => ucfirst($barang->status),
                                        };
                                    @endphp
                                    <span class="px-2.5 py-0.5 rounded-full text-[11px] font-bold border {{ $badgeColor }}">
                                        {{ $badgeText }}
                                    </span>
                                </div>
                                <div class="flex items-center justify-between gap-3 pt-2 border-t border-slate-200/60">
                                    <p class="text-[11px] text-slate-500">
                                        {{ $barang->status === 'tersedia' ? 'Barang dapat ditemukan dan dibooking penyewa.' : 'Barang sementara disembunyikan dari katalog sewa.' }}
                                    </p>
                                    <form action="{{ route('pemilik.barang.toggle-status', $barang->id) }}" method="POST" class="shrink-0">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit"
                                                class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1 {{ $barang->status === 'tersedia' ? 'bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200' : 'bg-emerald-600 hover:bg-emerald-700 text-white shadow-xs' }}">
                                            <i data-lucide="{{ $barang->status === 'tersedia' ? 'pause-circle' : 'play-circle' }}" class="w-3.5 h-3.5"></i>
                                            <span>{{ $barang->status === 'tersedia' ? 'Nonaktifkan' : 'Aktifkan' }}</span>
                                        </button>
                                    </form>
                                </div>
                            </div>

                            {{-- Statistik Ringkas Barang --}}
                            <div class="grid grid-cols-3 gap-2 text-center">
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Total Sewa</span>
                                    <span class="text-sm font-black text-slate-900 mt-0.5 block">{{ $ownerStats['total_rent_count'] ?? 0 }}x</span>
                                </div>
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Pendapatan</span>
                                    <span class="text-xs font-black text-emerald-600 mt-0.5 block">Rp{{ number_format($ownerStats['total_earnings'] ?? 0, 0, ',', '.') }}</span>
                                </div>
                                <div class="p-3 rounded-2xl bg-slate-50 border border-slate-100">
                                    <span class="text-[10px] text-slate-400 block font-semibold uppercase">Rating</span>
                                    <span class="text-xs font-black text-amber-500 mt-0.5 flex items-center justify-center gap-0.5">
                                        <i data-lucide="star" class="w-3 h-3 fill-amber-400"></i> {{ $barang->average_rating }}
                                    </span>
                                </div>
                            </div>

                            {{-- Notifikasi Booking Masuk --}}
                            @if(isset($ownerBookings) && $ownerBookings->where('status', 'MENUNGGU_PERSETUJUAN')->count() > 0)
                                <div class="space-y-3">
                                    @foreach($ownerBookings->where('status', 'MENUNGGU_PERSETUJUAN') as $pending)
                                        <div class="p-4 rounded-2xl bg-gradient-to-r from-amber-500/10 via-amber-50 to-amber-100/40 border border-amber-300 text-amber-900 text-xs">
                                            <div class="flex items-center justify-between mb-1.5">
                                                <span class="inline-flex items-center gap-1 font-bold text-amber-800 bg-amber-200/80 px-2 py-0.5 rounded-md text-[10px] uppercase">
                                                    <i data-lucide="bell" class="w-3 h-3"></i> Booking Masuk
                                                </span>
                                                <span class="font-mono text-[10px] font-bold text-slate-600">{{ $pending->booking_code }}</span>
                                            </div>
                                            <div class="font-bold text-slate-900 mb-0.5">Penyewa: {{ $pending->penyewa->name ?? 'Pengguna' }}</div>
                                            <p class="text-[11px] text-slate-600 mb-3">
                                                {{ $pending->tanggal_mulai->format('d M') }} - {{ $pending->tanggal_selesai->format('d M Y') }} ({{ $pending->jumlah_hari }} hari) • Total: <strong>Rp{{ number_format($pending->total_biaya, 0, ',', '.') }}</strong>
                                            </p>
                                            <div class="flex items-center gap-2">
                                                <form action="{{ route('pemilik.booking.approve', $pending->id) }}" method="POST" class="flex-1">
                                                    @csrf
                                                    <button type="submit" onclick="return confirm('Setujui permintaan sewa ini?')"
                                                            class="w-full py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs flex items-center justify-center gap-1 shadow-xs transition cursor-pointer">
                                                        <i data-lucide="check" class="w-3.5 h-3.5"></i> Setujui
                                                    </button>
                                                </form>
                                                <a href="{{ route('booking.show', $pending->booking_code) }}"
                                                   class="px-3 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 font-semibold text-xs hover:bg-slate-50 transition">
                                                    Detail
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif

                            {{-- Tombol Tindakan Pemilik --}}
                            <div class="space-y-2.5 pt-1">
                                <a href="{{ route('pemilik.barang.edit', $barang->id) }}"
                                   class="flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-md transition-all duration-200">
                                    <i data-lucide="edit-3" class="w-4 h-4"></i>
                                    <span>Edit Data Barang Lengkap</span>
                                </a>

                                <button type="button" onclick="document.getElementById('blockDateDetailModal').classList.remove('hidden')"
                                        class="flex items-center justify-center gap-2 w-full py-3 rounded-2xl bg-amber-50 hover:bg-amber-100 text-amber-800 border border-amber-200/90 font-bold text-xs transition cursor-pointer">
                                    <i data-lucide="calendar" class="w-4 h-4 text-amber-600"></i>
                                    <span>Blokir Tanggal Kalender (Pakai Sendiri)</span>
                                </button>

                                <a href="{{ route('pemilik.dashboard', ['tab' => 'barang_saya']) }}"
                                   class="flex items-center justify-center gap-2 w-full py-2.5 rounded-2xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                                    <i data-lucide="layout-dashboard" class="w-3.5 h-3.5"></i>
                                    <span>Buka Dashboard Pemilik</span>
                                </a>
                            </div>

                            {{-- Daftar Tanggal Diblokir Pribadi --}}
                            <div class="pt-5 border-t border-slate-100">
                                <div class="flex items-center justify-between mb-3">
                                    <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                                        <i data-lucide="calendar-off" class="w-3.5 h-3.5 text-slate-400"></i>
                                        <span>Tanggal Diblokir Pribadi ({{ $barang->blockedDates->count() }})</span>
                                    </span>
                                    <button type="button" onclick="document.getElementById('blockDateDetailModal').classList.remove('hidden')"
                                            class="text-[11px] font-bold text-emerald-600 hover:text-emerald-700 flex items-center gap-1 cursor-pointer">
                                        <i data-lucide="plus" class="w-3 h-3"></i> Tambah
                                    </button>
                                </div>

                                @if($barang->blockedDates->count() > 0)
                                    <div class="space-y-2 max-h-44 overflow-y-auto pr-1">
                                        @foreach($barang->blockedDates as $blocked)
                                            <div class="flex items-center justify-between p-2.5 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                                                <div>
                                                    <span class="font-bold text-slate-800 block text-[11px]">
                                                        {{ \Carbon\Carbon::parse($blocked->start_date)->format('d M') }} - {{ \Carbon\Carbon::parse($blocked->end_date)->format('d M Y') }}
                                                    </span>
                                                    <span class="text-[10px] text-slate-500">{{ $blocked->alasan ?? 'Digunakan pribadi' }}</span>
                                                </div>
                                                <form action="{{ route('pemilik.blocked-date.destroy', $blocked->id) }}" method="POST" onsubmit="return confirm('Buka kembali ketersediaan tanggal ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 rounded-lg text-rose-500 hover:bg-rose-50 hover:text-rose-700 transition cursor-pointer" title="Hapus pemblokiran">
                                                        <i data-lucide="trash-2" class="w-3.5 h-3.5"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-[11px] text-slate-400 leading-relaxed">
                                        Belum ada tanggal pemakaian pribadi yang diblokir. Tanggal kalender terbuka penuh untuk penyewa.
                                    </p>
                                @endif
                            </div>
                        </div>

                        {{-- Panel 2: Pratinjau Tampilan Penyewa (Simulasi) --}}
                        <div id="renterPreviewPanel" class="hidden space-y-4">
                            <div class="p-3.5 rounded-2xl bg-blue-50 border border-blue-200 text-blue-900 text-xs leading-relaxed flex items-start gap-2">
                                <i data-lucide="info" class="w-4 h-4 text-blue-600 shrink-0 mt-0.5"></i>
                                <div>
                                    <span class="font-bold block">Mode Pratinjau Penyewa:</span>
                                    <span>Formulir ini adalah simulasi tampilan yang dilihat oleh calon penyewa Anda untuk menguji perhitungan tarif dan ketersediaan.</span>
                                </div>
                            </div>

                            @include('barangs.partials.booking-form', ['isOwnerPreview' => true])
                        </div>
                    @else
                        @include('barangs.partials.booking-form', ['isOwnerPreview' => false])
                    @endif
                @else
                    <div class="text-center py-6">
                        <div class="w-14 h-14 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center mx-auto mb-3 shadow-xs">
                            <i data-lucide="lock" class="w-6 h-6"></i>
                        </div>
                        <h4 class="text-sm font-black text-slate-900">Masuk untuk Melakukan Booking</h4>
                        <p class="text-xs text-slate-500 mt-1 max-w-xs mx-auto leading-relaxed">
                            Silakan masuk atau buat akun baru untuk memesan barang ini dan memeriksa tanggal ketersediaan.
                        </p>
                        <div class="mt-5 flex flex-col gap-2.5">
                            <a href="{{ route('login') }}"
                               class="w-full py-3 rounded-2xl bg-emerald-600 text-white font-bold text-xs hover:bg-emerald-700 transition shadow-sm">
                                Masuk ke Akun
                            </a>
                            <a href="{{ route('register') }}"
                               class="w-full py-3 rounded-2xl bg-slate-100 text-slate-700 font-bold text-xs hover:bg-slate-200 transition">
                                Daftar Akun Baru
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

{{-- Modal Blokir Tanggal Langsung di Halaman Detail --}}
@auth
    @if(auth()->id() === $barang->user_id && session('mode') === 'pemilik')
        <div id="blockDateDetailModal" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
            <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl">
                <div class="flex items-center justify-between mb-3">
                    <h4 class="text-base font-bold text-slate-900 flex items-center gap-2">
                        <i data-lucide="calendar" class="w-4 h-4 text-emerald-600"></i>
                        <span>Blokir Tanggal Kalender</span>
                    </h4>
                    <button type="button" onclick="document.getElementById('blockDateDetailModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600 cursor-pointer">
                        <i data-lucide="x" class="w-5 h-5"></i>
                    </button>
                </div>
                <p class="text-xs text-slate-500 mb-4 leading-relaxed">
                    Gunakan fitur ini jika barang sedang Anda gunakan sendiri, dalam pemeliharaan, atau diservis sehingga tidak dapat disewa pengguna lain pada rentang tanggal tersebut.
                </p>
                <form action="{{ route('pemilik.barang.block-date', $barang->id) }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-2 gap-3 mb-3">
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Mulai Tanggal</label>
                            <input type="date" name="start_date" required min="{{ date('Y-m-d') }}"
                                   value="{{ date('Y-m-d') }}"
                                   class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-slate-700 mb-1">Selesai Tanggal</label>
                            <input type="date" name="end_date" required min="{{ date('Y-m-d') }}"
                                   value="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-semibold">
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="block text-[11px] font-bold text-slate-700 mb-1">Alasan Pemblokiran (Opsional)</label>
                        <input type="text" name="alasan" placeholder="Misal: Dipakai acara keluarga, diservis berkala"
                               class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" onclick="document.getElementById('blockDateDetailModal').classList.add('hidden')"
                                class="px-4 py-2 text-xs font-semibold text-slate-600 rounded-xl hover:bg-slate-100 cursor-pointer">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs cursor-pointer">
                            Simpan Blokir Tanggal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
@endauth

@push('scripts')
<script>
    const pricePerDay = {{ $barang->harga_sewa_per_hari }};
    const deliveryRatePerKm = {{ $barang->tarif_pengantaran_per_km }};

    function toggleOwnerView(mode) {
        const ownerPanel = document.getElementById('ownerControlPanel');
        const previewPanel = document.getElementById('renterPreviewPanel');
        const tabOwnerBtn = document.getElementById('tabOwnerBtn');
        const tabPreviewBtn = document.getElementById('tabPreviewBtn');

        if (!ownerPanel || !previewPanel) return;

        if (mode === 'preview') {
            ownerPanel.classList.add('hidden');
            previewPanel.classList.remove('hidden');

            if (tabPreviewBtn) tabPreviewBtn.className = 'flex-1 py-2 px-3 rounded-xl bg-white text-slate-900 shadow-xs flex items-center justify-center gap-1.5 transition cursor-pointer';
            if (tabOwnerBtn) tabOwnerBtn.className = 'flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5 transition cursor-pointer';

            calculateCost();
        } else {
            previewPanel.classList.add('hidden');
            ownerPanel.classList.remove('hidden');

            if (tabOwnerBtn) tabOwnerBtn.className = 'flex-1 py-2 px-3 rounded-xl bg-white text-slate-900 shadow-xs flex items-center justify-center gap-1.5 transition cursor-pointer';
            if (tabPreviewBtn) tabPreviewBtn.className = 'flex-1 py-2 px-3 rounded-xl text-slate-500 hover:text-slate-800 flex items-center justify-center gap-1.5 transition cursor-pointer';
        }

        if (window.lucide) {
            lucide.createIcons();
        }
    }

    function calculateCost() {
        const startInput = document.getElementById('tanggal_mulai');
        const endInput = document.getElementById('tanggal_selesai');
        const jarakInput = document.getElementById('jarak_km');
        const deliveryRadios = document.getElementsByName('metode_pengambilan');
        const deliveryFields = document.getElementById('deliveryFields');

        let selectedMethod = 'ambil_sendiri';
        for (let radio of deliveryRadios) {
            if (radio.checked) {
                selectedMethod = radio.value;
                break;
            }
        }

        if (deliveryFields) {
            if (selectedMethod === 'diantar') {
                deliveryFields.classList.remove('hidden');
            } else {
                deliveryFields.classList.add('hidden');
            }
        }

        if (!startInput || !endInput || !startInput.value || !endInput.value) {
            return;
        }

        const startDate = new Date(startInput.value);
        const endDate = new Date(endInput.value);

        if (endDate < startDate) {
            endInput.value = startInput.value;
        }

        const diffTime = Math.abs(new Date(endInput.value) - new Date(startInput.value));
        const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;

        const rentalCost = diffDays * pricePerDay;
        let ongkir = 0;

        if (selectedMethod === 'diantar' && jarakInput) {
            const distance = parseFloat(jarakInput.value) || 0;
            ongkir = Math.round(distance * deliveryRatePerKm);
        }

        const totalCost = rentalCost + ongkir;

        const calcDaysEl = document.getElementById('calcDays');
        const calcRentalEl = document.getElementById('calcRental');
        const calcOngkirEl = document.getElementById('calcOngkir');
        const calcTotalEl = document.getElementById('calcTotal');

        if (calcDaysEl) calcDaysEl.innerText = diffDays;
        if (calcRentalEl) calcRentalEl.innerText = 'Rp' + rentalCost.toLocaleString('id-ID');
        if (calcOngkirEl) calcOngkirEl.innerText = 'Rp' + ongkir.toLocaleString('id-ID');
        if (calcTotalEl) calcTotalEl.innerText = 'Rp' + totalCost.toLocaleString('id-ID');

        checkAvailabilityAjax(startInput.value, endInput.value, selectedMethod, jarakInput ? jarakInput.value : 0);
    }

    function checkAvailabilityAjax(start, end, method, distance) {
        const notice = document.getElementById('availabilityNotice');
        const btn = document.getElementById('btnSubmitBooking');

        if (!notice || !btn) return;

        fetch("{{ route('barangs.check-availability', $barang->id) }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                tanggal_mulai: start,
                tanggal_selesai: end,
                metode_pengambilan: method,
                jarak_km: distance
            })
        })
        .then(res => res.json())
        .then(data => {
            notice.classList.remove('hidden');
            if (data.available) {
                notice.className = 'p-3.5 rounded-2xl text-xs flex items-center gap-2 bg-emerald-50 text-emerald-800 border border-emerald-200 font-semibold';
                notice.innerHTML = '<i data-lucide="check-circle" class="w-4 h-4 text-emerald-600 shrink-0"></i> ' + data.message;
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
            } else {
                notice.className = 'p-3.5 rounded-2xl text-xs flex items-center gap-2 bg-rose-50 text-rose-800 border border-rose-200 font-semibold';
                notice.innerHTML = '<i data-lucide="alert-circle" class="w-4 h-4 text-rose-600 shrink-0"></i> ' + data.message;
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            lucide.createIcons();
        })
        .catch(err => console.error(err));
    }

    document.addEventListener('DOMContentLoaded', function() {
        calculateCost();
        const startInput = document.getElementById('tanggal_mulai');
        const endInput = document.getElementById('tanggal_selesai');
        if (startInput) startInput.addEventListener('change', calculateCost);
        if (endInput) endInput.addEventListener('change', calculateCost);
    });
</script>
@endpush
@endsection
