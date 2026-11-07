@extends('layouts.app')

@section('title', $barang->nama_barang . ' - Browly')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
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
                <div class="text-right">
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
                    @if(auth()->id() === $barang->user_id)
                        <div class="p-4 rounded-2xl bg-amber-50 border border-amber-200 text-amber-900 text-xs leading-relaxed">
                            <div class="flex items-center gap-1.5 font-bold mb-1">
                                <i data-lucide="info" class="w-4 h-4 text-amber-600 shrink-0"></i>
                                <span>Informasi Pemilik:</span>
                            </div>
                            <p>Anda adalah pemilik barang ini. Anda dapat mengelola ketersediaan tanggal atau memperbarui data barang di Dashboard Pemilik.</p>
                        </div>
                        <a href="{{ route('pemilik.barang.edit', $barang->id) }}"
                           class="mt-4 flex items-center justify-center gap-2 w-full py-3.5 rounded-2xl bg-slate-900 text-white font-bold text-xs hover:bg-slate-800 transition shadow-sm">
                            <i data-lucide="edit-3" class="w-4 h-4"></i>
                            <span>Edit Data Barang Ini</span>
                        </a>
                    @else
                        <form action="{{ route('booking.store', $barang->id) }}" method="POST" id="bookingForm" class="space-y-4">
                            @csrf

                            <!-- Date Inputs -->
                            <div class="grid grid-cols-2 gap-3">
                                <div>
                                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tanggal Mulai
                                    </label>
                                    <input type="date" name="tanggal_mulai" id="tanggal_mulai" required
                                           min="{{ date('Y-m-d') }}"
                                           value="{{ old('tanggal_mulai', date('Y-m-d')) }}"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-semibold">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                                        Tanggal Selesai
                                    </label>
                                    <input type="date" name="tanggal_selesai" id="tanggal_selesai" required
                                           min="{{ date('Y-m-d') }}"
                                           value="{{ old('tanggal_selesai', date('Y-m-d', strtotime('+1 day'))) }}"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-semibold">
                                </div>
                            </div>

                            <!-- Live Availability Notice -->
                            <div id="availabilityNotice" class="hidden p-3.5 rounded-2xl text-xs flex items-center gap-2 font-medium"></div>

                            <!-- Delivery Method -->
                            <div>
                                <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Metode Pengambilan
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    @if(in_array($barang->metode_pengambilan, ['ambil_sendiri', 'both']))
                                        <label class="flex items-center p-3 border rounded-2xl cursor-pointer hover:border-emerald-500 text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                                            <input type="radio" name="metode_pengambilan" value="ambil_sendiri" class="sr-only" checked onchange="calculateCost()">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="store" class="w-4 h-4 text-emerald-600"></i>
                                                <span class="font-bold text-slate-800">Ambil Sendiri (Rp0)</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if(in_array($barang->metode_pengambilan, ['diantar', 'both']))
                                        <label class="flex items-center p-3 border rounded-2xl cursor-pointer hover:border-emerald-500 text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                                            <input type="radio" name="metode_pengambilan" value="diantar" class="sr-only" {{ $barang->metode_pengambilan === 'diantar' ? 'checked' : '' }} onchange="calculateCost()">
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="truck" class="w-4 h-4 text-emerald-600"></i>
                                                <span class="font-bold text-slate-800">Diantar Pemilik</span>
                                            </div>
                                        </label>
                                    @endif
                                </div>
                            </div>

                            <!-- Delivery Fields Container -->
                            <div id="deliveryFields" class="hidden space-y-3 p-4 rounded-2xl bg-slate-50 border border-slate-200/80">
                                <div>
                                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                        Perkiraan Jarak Pengantaran (KM)
                                    </label>
                                    <input type="number" step="0.5" min="0.5" max="50" name="jarak_km" id="jarak_km" value="5"
                                           onchange="calculateCost()" oninput="calculateCost()"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-semibold">
                                    <span class="text-[10px] text-slate-400 mt-1 block">Tarif: Rp{{ number_format($barang->tarif_pengantaran_per_km, 0, ',', '.') }}/km</span>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                        Alamat Lengkap Pengantaran
                                    </label>
                                    <input type="text" name="alamat_pengantaran" placeholder="Jalan, No. Rumah, Patokan lokasi"
                                           class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-medium">
                                </div>
                            </div>

                            <!-- Payment Options -->
                            <div>
                                <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1.5">
                                    Pilihan Metode Pembayaran
                                </label>
                                <div class="grid grid-cols-2 gap-2">
                                    @if(in_array($barang->metode_pembayaran, ['qris', 'both']))
                                        <label class="flex items-center p-3 border rounded-2xl cursor-pointer hover:border-emerald-500 text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                                            <input type="radio" name="metode_pembayaran" value="qris" class="sr-only" checked>
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="qr-code" class="w-4 h-4 text-emerald-600"></i>
                                                <span class="font-bold text-slate-800">Simulasi QRIS</span>
                                            </div>
                                        </label>
                                    @endif

                                    @if(in_array($barang->metode_pembayaran, ['cash', 'both']))
                                        <label class="flex items-center p-3 border rounded-2xl cursor-pointer hover:border-emerald-500 text-xs has-[:checked]:border-emerald-600 has-[:checked]:bg-emerald-50/50 transition">
                                            <input type="radio" name="metode_pembayaran" value="cash" class="sr-only" {{ $barang->metode_pembayaran === 'cash' ? 'checked' : '' }}>
                                            <div class="flex items-center gap-2">
                                                <i data-lucide="banknote" class="w-4 h-4 text-emerald-600"></i>
                                                <span class="font-bold text-slate-800">Tunai (Cash / COD)</span>
                                            </div>
                                        </label>
                                    @endif
                                </div>
                            </div>

                            <!-- Notes -->
                            <div>
                                <label class="block text-[11px] font-extrabold text-slate-700 uppercase tracking-wider mb-1">
                                    Catatan / Kebutuhan Tambahan
                                </label>
                                <textarea name="catatan_penyewa" rows="2" placeholder="Misal: untuk acara akhir pekan, mohon disiapkan tas cadangan"
                                          class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium"></textarea>
                            </div>

                            <!-- Real-time Cost Calculation Card -->
                            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 space-y-2 text-xs">
                                <div class="flex justify-between text-slate-600 font-medium">
                                    <span>Biaya Sewa (<span id="calcDays" class="font-bold text-slate-800">1</span> hari)</span>
                                    <span class="font-bold text-slate-800" id="calcRental">Rp0</span>
                                </div>
                                <div class="flex justify-between text-slate-600 font-medium">
                                    <span>Ongkos Kirim</span>
                                    <span class="font-bold text-slate-800" id="calcOngkir">Rp0</span>
                                </div>
                                <div class="pt-2 border-t border-slate-200 flex justify-between text-sm font-extrabold text-slate-900">
                                    <span>Total Biaya</span>
                                    <span class="text-emerald-600 font-black text-base" id="calcTotal">Rp0</span>
                                </div>
                            </div>

                            <!-- Rules Micro-Notice -->
                            <div class="p-3.5 rounded-2xl bg-blue-50/80 border border-blue-100 text-[11px] text-blue-900 space-y-1">
                                <div class="font-bold flex items-center gap-1.5 text-blue-800">
                                    <i data-lucide="info" class="w-3.5 h-3.5 text-blue-600 shrink-0"></i>
                                    <span>Ketentuan Transaksi:</span>
                                </div>
                                <p>• Pemilik memiliki waktu <strong>24 jam</strong> untuk menyetujui booking Anda.</p>
                                <p>• Ruang chat resmi aktif setelah pembayaran berhasil diselesaikan.</p>
                            </div>

                            <!-- Submit Button -->
                            <button type="submit" id="btnSubmitBooking"
                                    class="w-full py-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-extrabold text-sm transition-all duration-200 shadow-md shadow-emerald-600/30 flex items-center justify-center gap-2 hover:-translate-y-0.5">
                                <i data-lucide="send" class="w-4 h-4"></i>
                                <span>Ajukan Booking Sekarang</span>
                            </button>
                        </form>
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

@push('scripts')
<script>
    const pricePerDay = {{ $barang->harga_sewa_per_hari }};
    const deliveryRatePerKm = {{ $barang->tarif_pengantaran_per_km }};

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
