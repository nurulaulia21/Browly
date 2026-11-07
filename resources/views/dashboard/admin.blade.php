@extends('layouts.app')

@section('title', 'Dashboard Administrator - Browly')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Header --}}
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <span class="text-xs font-bold text-indigo-600 uppercase tracking-wider">Super Administrator</span>
            <h1 class="text-2xl font-bold text-slate-900 mt-0.5">Pengawasan Sistem Platform Browly</h1>
            <p class="text-xs text-slate-500 mt-1">Monitoring seluruh transaksi, master data kategori & lokasi, pengelolaan pengguna, dan denda.</p>
        </div>

        <div class="flex items-center gap-2">
            <span class="px-3.5 py-1.5 rounded-xl bg-indigo-50 border border-indigo-200 text-indigo-700 font-bold text-xs flex items-center gap-1.5">
                <i data-lucide="shield-check" class="w-4 h-4"></i> Mode Administrator Aktif
            </span>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-3 mb-8">
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold text-slate-400">Total Pengguna</div>
            <div class="text-xl font-bold text-slate-800 mt-1">{{ $stats['total_users'] }}</div>
            <span class="text-[10px] text-slate-400">{{ $stats['total_pengguna'] }} Pengguna | {{ $stats['total_pemilik'] }} Pemilik Barang</span>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold text-slate-400">Total Barang</div>
            <div class="text-xl font-bold text-slate-800 mt-1">{{ $stats['total_barangs'] }}</div>
            <span class="text-[10px] text-slate-400">Terdaftar di katalog</span>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold text-slate-400">Total Booking</div>
            <div class="text-xl font-bold text-slate-800 mt-1">{{ $stats['total_bookings'] }}</div>
            <span class="text-[10px] text-slate-400">Semua riwayat pesanan</span>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold text-slate-400">Sewa Berjalan</div>
            <div class="text-xl font-bold text-emerald-600 mt-1">{{ $stats['booking_aktif'] }}</div>
            <span class="text-[10px] text-emerald-600 font-medium">Aktif / Sedang disewa</span>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold text-slate-400">Total Pembayaran</div>
            <div class="text-lg font-bold text-indigo-700 mt-1">Rp{{ number_format($stats['total_omset'], 0, ',', '.') }}</div>
            <span class="text-[10px] text-slate-400">Simulasi omset lunas</span>
        </div>

        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-xs">
            <div class="text-[11px] font-semibold text-slate-400">Total Denda</div>
            <div class="text-lg font-bold text-rose-600 mt-1">Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}</div>
            <span class="text-[10px] text-rose-500 font-medium">Keterlambatan & Rusak</span>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="flex items-center gap-2 overflow-x-auto pb-2 mb-6 border-b border-slate-200 text-xs font-semibold">
        <a href="{{ route('admin.dashboard', ['tab' => 'transaksi']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $tab == 'transaksi' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="receipt" class="w-3.5 h-3.5"></i> Transaksi Booking
        </a>
        <a href="{{ route('admin.dashboard', ['tab' => 'barang']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $tab == 'barang' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="package" class="w-3.5 h-3.5"></i> Manajemen Barang
        </a>
        <a href="{{ route('admin.dashboard', ['tab' => 'pengguna']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $tab == 'pengguna' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="users" class="w-3.5 h-3.5"></i> Pengguna Platform
        </a>
        <a href="{{ route('admin.dashboard', ['tab' => 'kategori']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $tab == 'kategori' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="grid" class="w-3.5 h-3.5"></i> Master Kategori
        </a>
        <a href="{{ route('admin.dashboard', ['tab' => 'lokasi']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $tab == 'lokasi' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="map-pin" class="w-3.5 h-3.5"></i> Master Lokasi
        </a>
        <a href="{{ route('admin.dashboard', ['tab' => 'denda']) }}"
           class="px-4 py-2 rounded-xl transition flex items-center gap-1.5 {{ $tab == 'denda' ? 'bg-slate-900 text-white' : 'text-slate-600 hover:bg-slate-100' }}">
            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i> Laporan Denda
        </a>
    </div>

    {{-- Tab 1: Transaksi Booking --}}
    @if($tab == 'transaksi')
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-sm">Seluruh Transaksi Booking Platform</h3>
                <span class="text-xs text-slate-400">Menampilkan seluruh status penyewaan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Kode</th>
                            <th class="py-3.5 px-4">Barang</th>
                            <th class="py-3.5 px-4">Penyewa</th>
                            <th class="py-3.5 px-4">Pemilik</th>
                            <th class="py-3.5 px-4">Durasi Sewa</th>
                            <th class="py-3.5 px-4">Total Biaya</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($bookings as $booking)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $booking->booking_code }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $booking->barang->nama_barang }}</td>
                                <td class="py-3 px-4">{{ $booking->penyewa->name }}</td>
                                <td class="py-3 px-4">{{ $booking->pemilik->name }}</td>
                                <td class="py-3 px-4">{{ $booking->tanggal_mulai->format('d/m/y') }} - {{ $booking->tanggal_selesai->format('d/m/y') }} ({{ $booking->durasi_hari }}h)</td>
                                <td class="py-3 px-4 font-bold text-emerald-700">Rp{{ number_format($booking->total_biaya, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    @php
                                        $badgeClasses = match($booking->status) {
                                            'MENUNGGU_PERSETUJUAN' => 'bg-amber-100 text-amber-800',
                                            'DISETUJUI', 'MENUNGGU_PEMBAYARAN' => 'bg-blue-100 text-blue-800',
                                            'DIBAYAR', 'AKTIF' => 'bg-emerald-100 text-emerald-800',
                                            'DIKEMBALIKAN' => 'bg-purple-100 text-purple-800',
                                            'SELESAI' => 'bg-teal-100 text-teal-800',
                                            'DITOLAK', 'DIBATALKAN' => 'bg-rose-100 text-rose-800',
                                            default => 'bg-slate-100 text-slate-800',
                                        };
                                    @endphp
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold {{ $badgeClasses }}">
                                        {{ $booking->status_label }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <a href="{{ route('booking.show', $booking->booking_code) }}"
                                       class="px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-[11px] transition">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="py-8 text-center text-slate-400">Belum ada data transaksi booking.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($bookings->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $bookings->appends(['tab' => 'transaksi'])->links() }}
                </div>
            @endif
        </div>

    {{-- Tab 2: Manajemen Barang --}}
    @elseif($tab == 'barang')
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-sm">Daftar Barang Disewakan di Platform</h3>
                <span class="text-xs text-slate-400">Admin dapat menonaktifkan barang jika melanggar ketentuan</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Barang</th>
                            <th class="py-3.5 px-4">Kategori</th>
                            <th class="py-3.5 px-4">Lokasi</th>
                            <th class="py-3.5 px-4">Pemilik</th>
                            <th class="py-3.5 px-4">Tarif/Hari</th>
                            <th class="py-3.5 px-4">Status</th>
                            <th class="py-3.5 px-4 text-right">Moderasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($barangs as $barang)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-4 font-semibold text-slate-900 flex items-center gap-3">
                                    <img src="{{ $barang->foto }}" alt="" class="w-10 h-10 rounded-lg object-cover border border-slate-100">
                                    <span>{{ $barang->nama_barang }}</span>
                                </td>
                                <td class="py-3 px-4">{{ $barang->kategori->nama }}</td>
                                <td class="py-3 px-4">{{ $barang->lokasi->kecamatan }}, {{ $barang->lokasi->kota }}</td>
                                <td class="py-3 px-4">{{ $barang->user->name }}</td>
                                <td class="py-3 px-4 font-bold text-emerald-700">Rp{{ number_format($barang->harga_sewa_per_hari, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold capitalize {{ $barang->status === 'tersedia' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $barang->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-right">
                                    <form action="{{ route('admin.barang.toggle', $barang->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" onclick="return confirm('Ubah status ketersediaan barang ini?')"
                                                class="px-2.5 py-1 rounded-lg {{ $barang->status === 'tersedia' ? 'bg-rose-50 text-rose-700 hover:bg-rose-100' : 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100' }} font-bold text-[11px] transition">
                                            {{ $barang->status === 'tersedia' ? 'Nonaktifkan' : 'Aktifkan' }}
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Belum ada barang terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($barangs->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $barangs->appends(['tab' => 'barang'])->links() }}
                </div>
            @endif
        </div>

    {{-- Tab 3: Manajemen Pengguna --}}
    @elseif($tab == 'pengguna')
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-sm">Daftar Akun Pengguna Terdaftar</h3>
                <span class="text-xs text-slate-400">Total: {{ $stats['total_users'] }} akun</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Nama</th>
                            <th class="py-3.5 px-4">Email</th>
                            <th class="py-3.5 px-4">No. HP</th>
                            <th class="py-3.5 px-4">Role</th>
                            <th class="py-3.5 px-4">Wilayah Lokasi</th>
                            <th class="py-3.5 px-4">Terdaftar</th>
                            <th class="py-3.5 px-4 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($users as $user)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-4 font-bold text-slate-900">{{ $user->name }}</td>
                                <td class="py-3 px-4">{{ $user->email }}</td>
                                <td class="py-3 px-4">{{ $user->phone }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-emerald-100 text-emerald-800' }}">
                                        {{ $user->isAdmin() ? 'admin' : 'pengguna' }}
                                    </span>
                                </td>
                                <td class="py-3 px-4">{{ $user->lokasi ? $user->lokasi->kecamatan . ', ' . $user->lokasi->kota : '-' }}</td>
                                <td class="py-3 px-4">{{ $user->created_at->format('d/m/Y') }}</td>
                                <td class="py-3 px-4 text-right">
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.user.destroy', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus akun pengguna {{ $user->name }}?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-rose-500 hover:text-rose-700 hover:bg-rose-50 rounded-lg transition" title="Hapus User">
                                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-[10px] text-slate-400 italic">Akun Anda</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Belum ada pengguna.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($users->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $users->appends(['tab' => 'pengguna'])->links() }}
                </div>
            @endif
        </div>

    {{-- Tab 4: Master Kategori --}}
    @elseif($tab == 'kategori')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs h-fit">
                <h3 class="font-bold text-slate-900 text-sm mb-1">Tambah Kategori Baru</h3>
                <p class="text-xs text-slate-500 mb-4">Tambahkan kategori untuk mengelompokkan barang sewaan.</p>
                <form action="{{ route('admin.kategori.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Nama Kategori</label>
                        <input type="text" name="nama" required placeholder="Contoh: Alat Berkebun"
                               class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Icon (Lucide name)</label>
                        <input type="text" name="icon" placeholder="Contoh: scissors, hammer, tv"
                               class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Singkat</label>
                        <textarea name="deskripsi" rows="2" placeholder="Deskripsi kategori..."
                                  class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500"></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition">
                        Simpan Kategori Baru
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-sm">Daftar Kategori Saat Ini</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($kategoris as $kat)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                    <i data-lucide="{{ $kat->icon ?? 'grid' }}" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">{{ $kat->nama }}</h4>
                                    <p class="text-[11px] text-slate-400">{{ $kat->deskripsi ?? 'Slug: ' . $kat->slug }}</p>
                                </div>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold text-xs">
                                {{ $kat->barangs_count }} Barang
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    {{-- Tab 5: Master Lokasi --}}
    @elseif($tab == 'lokasi')
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-3xl border border-slate-200 shadow-xs h-fit">
                <h3 class="font-bold text-slate-900 text-sm mb-1">Tambah Wilayah Baru</h3>
                <p class="text-xs text-slate-500 mb-4">Tambahkan wilayah cakupan lokal untuk pencarian barang.</p>
                <form action="{{ route('admin.lokasi.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kota / Kabupaten</label>
                        <input type="text" name="kota" required placeholder="Contoh: Makassar"
                               class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-slate-700 mb-1">Kecamatan</label>
                        <input type="text" name="kecamatan" required placeholder="Contoh: Panakkukang"
                               class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs shadow-xs transition">
                        Simpan Wilayah Baru
                    </button>
                </form>
            </div>

            <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="p-5 border-b border-slate-100">
                    <h3 class="font-bold text-slate-900 text-sm">Daftar Wilayah Cakupan Browly</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($lokasis as $lok)
                        <div class="p-4 flex items-center justify-between hover:bg-slate-50 transition">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center font-bold">
                                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                                </div>
                                <div>
                                    <h4 class="font-bold text-slate-900 text-xs">{{ $lok->kecamatan }}</h4>
                                    <p class="text-[11px] text-slate-400">Kota {{ $lok->kota }}</p>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-bold text-[11px]">
                                    {{ $lok->barangs_count }} Barang
                                </span>
                                <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 font-bold text-[11px]">
                                    {{ $lok->users_count }} Pengguna
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    {{-- Tab 6: Laporan Denda --}}
    @elseif($tab == 'denda')
        <div class="bg-white rounded-3xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <h3 class="font-bold text-slate-900 text-sm">Laporan Denda Keterlambatan & Kerusakan</h3>
                <span class="text-xs text-rose-600 font-bold">Total Denda: Rp{{ number_format($stats['total_denda'], 0, ',', '.') }}</span>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-500 font-bold uppercase text-[10px] tracking-wider border-b border-slate-200">
                        <tr>
                            <th class="py-3.5 px-4">Booking</th>
                            <th class="py-3.5 px-4">Barang</th>
                            <th class="py-3.5 px-4">Penyewa</th>
                            <th class="py-3.5 px-4">Jenis Denda</th>
                            <th class="py-3.5 px-4">Nominal</th>
                            <th class="py-3.5 px-4">Status Bayar</th>
                            <th class="py-3.5 px-4">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($dendas as $denda)
                            <tr class="hover:bg-slate-50/80 transition">
                                <td class="py-3 px-4 font-mono font-bold text-slate-800">{{ $denda->booking->booking_code }}</td>
                                <td class="py-3 px-4 font-semibold text-slate-900">{{ $denda->booking->barang->nama_barang }}</td>
                                <td class="py-3 px-4">{{ $denda->booking->penyewa->name }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $denda->jenis_denda === 'keterlambatan' ? 'bg-amber-100 text-amber-800' : 'bg-rose-100 text-rose-800' }}">
                                        {{ $denda->jenis_denda }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 font-bold text-rose-600">Rp{{ number_format($denda->nominal, 0, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-0.5 rounded-full text-[10px] font-bold capitalize {{ $denda->status_pembayaran === 'lunas' ? 'bg-emerald-100 text-emerald-800' : 'bg-slate-100 text-slate-800' }}">
                                        {{ str_replace('_', ' ', $denda->status_pembayaran) }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-slate-500">{{ $denda->deskripsi ?? '-' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-slate-400">Belum ada catatan denda.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($dendas->hasPages())
                <div class="p-4 border-t border-slate-100">
                    {{ $dendas->appends(['tab' => 'denda'])->links() }}
                </div>
            @endif
        </div>
    @endif
</div>
@endsection

