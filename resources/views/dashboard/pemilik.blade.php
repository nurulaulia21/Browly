@extends('layouts.app')

@section('title', 'Dashboard Pemilik - Browly')

@section('content')
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Dashboard Pemilik</span>
            <h1 class="text-2xl font-bold text-slate-900 mt-0.5">Kelola Penyewaan: {{ auth()->user()->name }}</h1>
            <p class="text-xs text-slate-500 mt-1">Kelola katalog barang, konfirmasi booking masuk, pantau sewa aktif, dan verifikasi pengembalian.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('pemilik.barang.create') }}" class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-semibold text-xs shadow-sm transition flex items-center gap-1.5">
                <i data-lucide="plus-circle" class="w-4 h-4"></i>
                Tambah Barang Baru
            </a>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-slate-400">Total Barang</div>
            <div class="text-2xl font-bold text-slate-800 mt-1">{{ $stats['total_barang'] }}</div>
            <span class="text-[10px] text-slate-400">Barang disewakan</span>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-slate-400">Booking Masuk</div>
            <div class="text-2xl font-bold text-amber-500 mt-1">{{ $stats['booking_masuk'] }}</div>
            <span class="text-[10px] text-amber-600 font-medium">Batas respon 24 jam</span>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-slate-400">Sewa Aktif</div>
            <div class="text-2xl font-bold text-emerald-600 mt-1">{{ $stats['sewa_aktif'] }}</div>
            <span class="text-[10px] text-slate-400">Sedang berjalan</span>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-xs font-semibold text-slate-400">Pengembalian</div>
            <div class="text-2xl font-bold text-purple-600 mt-1">{{ $stats['pengembalian_pending'] }}</div>
            <span class="text-[10px] text-purple-600 font-medium">Perlu verifikasi</span>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs col-span-2 lg:col-span-1">
            <div class="text-xs font-semibold text-slate-400">Total Pendapatan</div>
            <div class="text-xl font-bold text-emerald-700 mt-1">Rp{{ number_format($stats['total_pendapatan'], 0, ',', '.') }}</div>
            <span class="text-[10px] text-slate-400">Dari transaksi valid</span>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 border-b border-slate-200 text-xs font-semibold">
        <a href="{{ route('pemilik.dashboard', ['tab' => 'booking_masuk']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-2 {{ $tab == 'booking_masuk' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="bell" class="w-3.5 h-3.5"></i>
            Booking Masuk ({{ $stats['booking_masuk'] }})
        </a>
        <a href="{{ route('pemilik.dashboard', ['tab' => 'barang_saya']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-2 {{ $tab == 'barang_saya' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="package" class="w-3.5 h-3.5"></i>
            Barang Saya ({{ $stats['total_barang'] }})
        </a>
        <a href="{{ route('pemilik.dashboard', ['tab' => 'sewa_aktif']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-2 {{ $tab == 'sewa_aktif' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="play-circle" class="w-3.5 h-3.5"></i>
            Sewa Berjalan ({{ $stats['sewa_aktif'] }})
        </a>
        <a href="{{ route('pemilik.dashboard', ['tab' => 'pengembalian']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-2 {{ $tab == 'pengembalian' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
            Pengembalian & Denda ({{ $stats['pengembalian_pending'] }})
        </a>
    </div>

    {{-- Tab 1: Booking Masuk --}}
    @if($tab == 'booking_masuk')
        <div class="space-y-4">
            @forelse($bookingsMasuk as $booking)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs hover:border-slate-300 transition">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
                        <div class="flex items-start gap-4">
                            <img src="{{ $booking->barang->foto }}" alt="" class="w-20 h-20 rounded-2xl object-cover shrink-0 border border-slate-100">
                            <div>
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="font-mono text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $booking->booking_code }}
                                    </span>
                                    <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full bg-amber-100 text-amber-800">
                                        Menunggu Persetujuan Anda
                                    </span>
                                </div>
                                <h3 class="font-bold text-slate-900 text-base mb-1">{{ $booking->barang->nama_barang }}</h3>
                                <div class="text-xs text-slate-500 flex flex-wrap items-center gap-y-1 gap-x-3">
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="user" class="w-3.5 h-3.5 text-slate-400"></i>
                                        Penyewa: <strong>{{ $booking->penyewa->name }}</strong> ({{ $booking->penyewa->phone }})
                                    </span>
                                    <span class="flex items-center gap-1">
                                        <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                        {{ $booking->tanggal_mulai->format('d M Y') }} s/d {{ $booking->tanggal_selesai->format('d M Y') }} ({{ $booking->durasi_hari }} Hari)
                                    </span>
                                </div>

                                <div class="mt-3 flex flex-wrap gap-2 text-xs">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
                                        Metode Ambil: <strong class="capitalize">{{ str_replace('_', ' ', $booking->metode_pengambilan) }}</strong>
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-700 font-medium">
                                        Metode Bayar: <strong class="uppercase">{{ $booking->metode_pembayaran }}</strong>
                                    </span>
                                    <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-800 font-bold">
                                        Total Biaya: Rp{{ number_format($booking->total_biaya, 0, ',', '.') }}
                                    </span>
                                </div>

                                @if($booking->catatan_penyewa)
                                    <div class="mt-2.5 text-xs text-slate-600 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                                        <span class="font-semibold text-slate-700">Catatan Penyewa:</span> "{{ $booking->catatan_penyewa }}"
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- Action Buttons & 24h Countdown Reminder --}}
                        <div class="flex flex-col items-end gap-3 shrink-0">
                            <div class="text-right">
                                <span class="text-[11px] font-semibold text-amber-700 flex items-center justify-end gap-1">
                                    <i data-lucide="clock" class="w-3.5 h-3.5"></i> Batas Respon:
                                </span>
                                <span class="text-xs font-bold text-slate-800">
                                    {{ $booking->expires_at_owner ? $booking->expires_at_owner->format('d M Y, H:i') : '24 Jam' }}
                                </span>
                                <p class="text-[10px] text-slate-400">Otomatis batal jika melewati batas</p>
                            </div>

                            <div class="flex items-center gap-2">
                                <form action="{{ route('pemilik.booking.approve', $booking->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" onclick="return confirm('Apakah Anda yakin ingin menyetujui booking ini?')"
                                            class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                                        <i data-lucide="check" class="w-4 h-4"></i>
                                        Setujui
                                    </button>
                                </form>

                                <button onclick="document.getElementById('rejectModal-{{ $booking->id }}').classList.remove('hidden')"
                                        class="px-4 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 font-bold text-xs border border-rose-200 transition flex items-center gap-1.5">
                                    <i data-lucide="x" class="w-4 h-4"></i>
                                    Tolak
                                </button>

                                <a href="{{ route('booking.show', $booking->booking_code) }}"
                                   class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Reject Modal for this booking --}}
                <div id="rejectModal-{{ $booking->id }}" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
                    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-base font-bold text-slate-900">Tolak Booking {{ $booking->booking_code }}</h4>
                            <button type="button" onclick="document.getElementById('rejectModal-{{ $booking->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mb-4">Mohon berikan alasan penolakan untuk penyewa (misal: barang sedang diperbaiki, benturan jadwal mendadak):</p>
                        <form action="{{ route('pemilik.booking.reject', $booking->id) }}" method="POST">
                            @csrf
                            <textarea name="alasan_penolakan" required rows="3" placeholder="Tulis alasan penolakan di sini..."
                                      class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-rose-500 mb-4"></textarea>
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="document.getElementById('rejectModal-{{ $booking->id }}').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 rounded-xl hover:bg-slate-100">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-rose-600 hover:bg-rose-700 rounded-xl shadow-xs">
                                    Kirim Penolakan
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white p-12 text-center rounded-3xl border border-slate-200">
                    <i data-lucide="inbox" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                    <h3 class="font-bold text-slate-700 text-sm">Tidak Ada Booking Masuk</h3>
                    <p class="text-xs text-slate-400 mt-1">Saat ini belum ada pengajuan sewa baru yang membutuhkan persetujuan Anda.</p>
                </div>
            @endforelse
        </div>

    {{-- Tab 2: Barang Saya --}}
    @elseif($tab == 'barang_saya')
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($barangs as $barang)
                <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden flex flex-col justify-between hover:shadow-md transition">
                    <div>
                        <div class="relative h-48 bg-slate-100 overflow-hidden">
                            <img src="{{ $barang->foto }}" alt="{{ $barang->nama_barang }}" class="w-full h-full object-cover">
                            <div class="absolute top-3 right-3 flex items-center gap-1.5">
                                @php
                                    $statusColor = match($barang->status) {
                                        'tersedia' => 'bg-emerald-500 text-white',
                                        'tidak_tersedia' => 'bg-amber-500 text-white',
                                        'nonaktif' => 'bg-slate-500 text-white',
                                        default => 'bg-slate-500 text-white',
                                    };
                                @endphp
                                <span class="px-2.5 py-1 rounded-full text-[10px] font-bold shadow-xs capitalize {{ $statusColor }}">
                                    {{ $barang->status }}
                                </span>
                            </div>
                            <div class="absolute bottom-3 left-3">
                                <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-slate-900/80 backdrop-blur-xs text-white">
                                    {{ $barang->kategori->nama }}
                                </span>
                            </div>
                        </div>

                        <div class="p-5">
                            <h3 class="font-bold text-slate-900 text-base mb-1">{{ $barang->nama_barang }}</h3>
                            <p class="text-xs text-slate-500 flex items-center gap-1 mb-3">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                {{ $barang->lokasi->kecamatan }}, {{ $barang->lokasi->kota }}
                            </p>

                            <div class="flex items-baseline justify-between mb-4 pb-3 border-b border-slate-100">
                                <span class="text-xs text-slate-400">Harga Sewa:</span>
                                <span class="text-base font-bold text-emerald-600">
                                    Rp{{ number_format($barang->harga_sewa_per_hari, 0, ',', '.') }}<span class="text-[10px] text-slate-400 font-normal">/hari</span>
                                </span>
                            </div>

                            <div class="space-y-1.5 text-[11px] text-slate-600 mb-4">
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Kondisi:</span>
                                    <span class="font-semibold">{{ $barang->kondisi }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Metode Bayar:</span>
                                    <span class="font-semibold uppercase">{{ $barang->metode_pembayaran }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-400">Pengambilan:</span>
                                    <span class="font-semibold capitalize">{{ str_replace('_', ' ', $barang->metode_pengambilan) }}</span>
                                </div>
                            </div>

                            {{-- Blokir Tanggal Summary --}}
                            @if($barang->blockedDates->count() > 0)
                                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 mb-4">
                                    <span class="text-[11px] font-bold text-slate-700 flex items-center gap-1 mb-1">
                                        <i data-lucide="calendar-off" class="w-3.5 h-3.5 text-slate-400"></i>
                                        {{ $barang->blockedDates->count() }} Tanggal Diblokir Pribadi:
                                    </span>
                                    <div class="space-y-1">
                                        @foreach($barang->blockedDates as $blocked)
                                            <div class="flex items-center justify-between text-[10px] text-slate-600">
                                                <span>{{ $blocked->start_date->format('d/m/y') }} - {{ $blocked->end_date->format('d/m/y') }}</span>
                                                <form action="{{ route('pemilik.blocked-date.destroy', $blocked->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" onclick="return confirm('Hapus pemblokiran tanggal ini?')" class="text-rose-500 hover:text-rose-700">
                                                        <i data-lucide="trash-2" class="w-3 h-3"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- Actions Card Footer --}}
                    <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('pemilik.barang.edit', $barang->id) }}"
                               class="px-3 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 font-semibold text-xs flex items-center gap-1 transition">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i> Edit
                            </a>
                            <button onclick="document.getElementById('blockDateModal-{{ $barang->id }}').classList.remove('hidden')"
                                    class="px-3 py-1.5 rounded-lg bg-amber-50 border border-amber-200 text-amber-800 hover:bg-amber-100 font-semibold text-xs flex items-center gap-1 transition" title="Blokir tanggal kalender">
                                <i data-lucide="calendar" class="w-3.5 h-3.5"></i> Blokir
                            </button>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('barangs.show', $barang->slug) }}" target="_blank"
                               class="px-3 py-1.5 rounded-lg bg-slate-200 text-slate-700 hover:bg-slate-300 font-semibold text-xs flex items-center gap-1 transition">
                                <i data-lucide="external-link" class="w-3.5 h-3.5"></i> Lihat
                            </a>
                            <form action="{{ route('pemilik.barang.destroy', $barang->id) }}" method="POST" onsubmit="return confirm('Hapus barang ini secara permanen?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Hapus Barang">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Modal Blokir Tanggal --}}
                <div id="blockDateModal-{{ $barang->id }}" class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs flex items-center justify-center p-4">
                    <div class="bg-white rounded-3xl p-6 max-w-md w-full shadow-2xl">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-base font-bold text-slate-900">Blokir Tanggal Kalender</h4>
                            <button type="button" onclick="document.getElementById('blockDateModal-{{ $barang->id }}').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                                <i data-lucide="x" class="w-5 h-5"></i>
                            </button>
                        </div>
                        <p class="text-xs text-slate-500 mb-4">
                            Gunakan fitur ini jika barang sedang Anda gunakan sendiri sehingga tidak dapat disewa pengguna lain pada rentang tanggal tersebut.
                        </p>
                        <form action="{{ route('pemilik.barang.block-date', $barang->id) }}" method="POST">
                            @csrf
                            <div class="grid grid-cols-2 gap-3 mb-3">
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Mulai Tanggal</label>
                                    <input type="date" name="start_date" required min="{{ date('Y-m-d') }}"
                                           class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                                <div>
                                    <label class="block text-[11px] font-bold text-slate-700 mb-1">Sampai Tanggal</label>
                                    <input type="date" name="end_date" required min="{{ date('Y-m-d') }}"
                                           class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Keterangan / Alasan</label>
                                <input type="text" name="alasan" placeholder="Misal: Digunakan acara keluarga"
                                       class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                            </div>
                            <div class="flex justify-end gap-2">
                                <button type="button" onclick="document.getElementById('blockDateModal-{{ $barang->id }}').classList.add('hidden')" class="px-4 py-2 text-xs font-semibold text-slate-600 rounded-xl hover:bg-slate-100">
                                    Batal
                                </button>
                                <button type="submit" class="px-4 py-2 text-xs font-bold text-white bg-slate-900 hover:bg-black rounded-xl shadow-xs">
                                    Simpan Blokir Tanggal
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white p-12 text-center rounded-3xl border border-slate-200">
                    <i data-lucide="package-plus" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                    <h3 class="font-bold text-slate-700 text-sm">Belum Ada Barang yang Didaftarkan</h3>
                    <p class="text-xs text-slate-400 mt-1 mb-4">Mulai sewakan barang Anda yang jarang dipakai dan dapatkan penghasilan tambahan.</p>
                    <a href="{{ route('pemilik.barang.create') }}" class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs">
                        <i data-lucide="plus" class="w-4 h-4"></i> Tambah Barang Sekarang
                    </a>
                </div>
            @endforelse
        </div>

    {{-- Tab 3: Sewa Berjalan --}}
    @elseif($tab == 'sewa_aktif')
        <div class="space-y-4">
            @forelse($sewaAktif as $booking)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs flex flex-col md:flex-row md:items-center justify-between gap-4">
                    <div class="flex items-start gap-4">
                        <img src="{{ $booking->barang->foto }}" alt="" class="w-20 h-20 rounded-2xl object-cover shrink-0 border border-slate-100">
                        <div>
                            <div class="flex items-center gap-2 mb-1.5">
                                <span class="font-mono text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ $booking->booking_code }}
                                </span>
                                @php
                                    $badge = match($booking->status) {
                                        'DISETUJUI', 'MENUNGGU_PEMBAYARAN' => 'bg-blue-100 text-blue-800',
                                        'DIBAYAR' => 'bg-emerald-100 text-emerald-800',
                                        'AKTIF' => 'bg-teal-100 text-teal-800',
                                        default => 'bg-slate-100 text-slate-800',
                                    };
                                @endphp
                                <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full {{ $badge }}">
                                    {{ $booking->status_label }}
                                </span>
                            </div>
                            <h3 class="font-bold text-slate-900 text-base mb-1">{{ $booking->barang->nama_barang }}</h3>
                            <div class="text-xs text-slate-500 flex flex-wrap items-center gap-y-1 gap-x-3">
                                <span>Penyewa: <strong>{{ $booking->penyewa->name }}</strong> ({{ $booking->penyewa->phone }})</span>
                                <span>Rentang: <strong>{{ $booking->tanggal_mulai->format('d M') }} - {{ $booking->tanggal_selesai->format('d M Y') }}</strong></span>
                                <span>Total: <strong class="text-emerald-700">Rp{{ number_format($booking->total_biaya, 0, ',', '.') }}</strong></span>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 shrink-0">
                        @if($booking->isChatActive())
                            <a href="{{ route('chat.show', $booking->booking_code) }}"
                               class="px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition flex items-center gap-1.5">
                                <i data-lucide="message-square" class="w-4 h-4"></i>
                                Buka Chat
                            </a>
                        @endif

                        <a href="{{ route('booking.show', $booking->booking_code) }}"
                           class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs transition">
                            Lihat Rincian
                        </a>
                    </div>
                </div>
            @empty
                <div class="bg-white p-12 text-center rounded-3xl border border-slate-200">
                    <i data-lucide="check-circle-2" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                    <h3 class="font-bold text-slate-700 text-sm">Tidak Ada Transaksi Sewa yang Sedang Aktif</h3>
                    <p class="text-xs text-slate-400 mt-1">Transaksi yang telah disetujui atau sedang dalam masa sewa akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

    {{-- Tab 4: Pengembalian & Denda --}}
    @elseif($tab == 'pengembalian')
        <div class="space-y-4">
            @forelse($pengembalianList as $booking)
                <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs">
                    <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
                        <div class="flex items-start gap-4">
                            <img src="{{ $booking->barang->foto }}" alt="" class="w-16 h-16 rounded-2xl object-cover shrink-0 border border-slate-100">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="font-mono text-[11px] font-bold text-slate-500 bg-slate-100 px-2 py-0.5 rounded">
                                        {{ $booking->booking_code }}
                                    </span>
                                    <span class="text-[10px] font-bold px-2.5 py-0.5 rounded-full {{ $booking->status === 'SELESAI' ? 'bg-teal-100 text-teal-800' : 'bg-purple-100 text-purple-800' }}">
                                        {{ $booking->status_label }}
                                    </span>
                                </div>
                                <h3 class="font-bold text-slate-900 text-base">{{ $booking->barang->nama_barang }}</h3>
                                <p class="text-xs text-slate-500">Penyewa: {{ $booking->penyewa->name }} ({{ $booking->penyewa->phone }})</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('booking.show', $booking->booking_code) }}"
                               class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs transition">
                                Detail Transaksi
                            </a>
                        </div>
                    </div>

                    {{-- Informasi Pengembalian Diajukan --}}
                    @if($booking->pengembalian)
                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 text-xs">
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-3">
                                <div>
                                    <span class="text-slate-400 block text-[11px]">Tanggal Pengembalian:</span>
                                    <span class="font-bold text-slate-800">{{ $booking->pengembalian->tanggal_pengembalian_aktual->format('d M Y, H:i') }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[11px]">Klaim Kondisi Penyewa:</span>
                                    <span class="font-bold text-slate-800 capitalize">{{ str_replace('_', ' ', $booking->pengembalian->kondisi_barang) }}</span>
                                </div>
                                <div>
                                    <span class="text-slate-400 block text-[11px]">Keterlambatan:</span>
                                    <span class="font-bold {{ $booking->pengembalian->hari_keterlambatan > 0 ? 'text-rose-600' : 'text-emerald-600' }}">
                                        {{ $booking->pengembalian->hari_keterlambatan }} Hari
                                    </span>
                                </div>
                            </div>

                            @if($booking->pengembalian->catatan)
                                <p class="text-slate-600 italic mb-3">"{{ $booking->pengembalian->catatan }}"</p>
                            @endif

                            {{-- Form Verifikasi & Penetapan Denda bila belum selesai --}}
                            @if($booking->status === 'DIKEMBALIKAN')
                                <div class="mt-4 pt-4 border-t border-slate-200">
                                    <h4 class="font-bold text-slate-900 text-xs uppercase tracking-wider mb-2">Form Konfirmasi & Penetapan Denda Pemilik:</h4>
                                    <form action="{{ route('pengembalian.confirm', $booking->id) }}" method="POST" class="space-y-3">
                                        @csrf
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Verifikasi Kondisi Barang</label>
                                                <select name="kondisi_barang_verifikasi" required class="w-full text-xs p-2 rounded-xl border border-slate-300">
                                                    <option value="sesuai_awal">Sesuai Kondisi Awal (Bagus)</option>
                                                    <option value="rusak_ringan">Rusak Ringan</option>
                                                    <option value="rusak_berat">Rusak Berat / Hilang</option>
                                                </select>
                                            </div>

                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Denda Terlambat (Rp)</label>
                                                @php
                                                    $suggestedLateFine = $booking->pengembalian->hari_keterlambatan * $booking->barang->harga_sewa_per_hari;
                                                @endphp
                                                <input type="number" name="denda_keterlambatan" value="{{ $suggestedLateFine }}" min="0"
                                                       class="w-full text-xs p-2 rounded-xl border border-slate-300">
                                            </div>

                                            <div>
                                                <label class="block text-[11px] font-bold text-slate-700 mb-1">Denda Kerusakan (Rp)</label>
                                                <input type="number" name="denda_kerusakan" value="0" min="0"
                                                       class="w-full text-xs p-2 rounded-xl border border-slate-300">
                                            </div>
                                        </div>

                                        <div>
                                            <input type="text" name="deskripsi_kerusakan" placeholder="Catatan kerusakan jika ada (opsional)..."
                                                   class="w-full text-xs p-2 rounded-xl border border-slate-300">
                                        </div>

                                        <div class="flex justify-end">
                                            <button type="submit" onclick="return confirm('Konfirmasi pengembalian barang ini dan nyatakan transaksi selesai?')"
                                                    class="px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-xs transition">
                                                Konfirmasi & Selesaikan Transaksi
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>
            @empty
                <div class="bg-white p-12 text-center rounded-3xl border border-slate-200">
                    <i data-lucide="rotate-ccw" class="w-12 h-12 text-slate-300 mx-auto mb-3"></i>
                    <h3 class="font-bold text-slate-700 text-sm">Tidak Ada Pengembalian Barang</h3>
                    <p class="text-xs text-slate-400 mt-1">Pengembalian barang yang diajukan oleh penyewa akan muncul di sini untuk Anda periksa.</p>
                </div>
            @endforelse
        </div>
    @endif
</div>
@endsection

