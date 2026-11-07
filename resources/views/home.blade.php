@extends('layouts.app')

@section('title', 'Browly - Platform Penyewaan Barang Antar Pengguna Lokal')

@section('content')
<!-- Hero Section with Ambient Glow -->
<div class="relative overflow-hidden pt-8 pb-20 border-b border-slate-200/60 bg-gradient-to-b from-emerald-50/50 via-white to-slate-50/50">
    <!-- Ambient Blur Background Elements -->
    <div class="pointer-events-none absolute -top-24 left-1/2 -translate-x-1/2 w-[720px] sm:w-[960px] h-[380px] bg-gradient-to-tr from-emerald-400/20 via-teal-300/15 to-cyan-400/20 blur-3xl rounded-full opacity-80"></div>
    <div class="pointer-events-none absolute top-40 right-[-100px] w-80 h-80 bg-emerald-300/15 blur-3xl rounded-full"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Text -->
        <div class="text-center max-w-3xl mx-auto pt-4 sm:pt-8">
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full text-xs font-semibold bg-emerald-100/70 text-emerald-800 border border-emerald-200/60 mb-6 shadow-xs backdrop-blur-sm">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                </span>
                <span>Platform Rental Peer-to-Peer Wilayah Lokal</span>
            </div>

            <h1 class="text-4xl sm:text-6xl font-black tracking-tight text-slate-900 leading-[1.12]">
                Barang yang jarang dipakai <br class="hidden sm:inline">
                <span class="bg-gradient-to-r from-emerald-600 via-teal-600 to-cyan-600 bg-clip-text text-transparent">
                    tidak harus selalu dibeli.
                </span>
            </h1>

            <p class="mt-5 text-base sm:text-lg text-slate-600 leading-relaxed font-normal max-w-2xl mx-auto">
                Sewa kamera, perlengkapan camping, proyektor, hingga perkakas berkualitas dari tetangga atau komunitas di sekitar Anda dengan sistem jadwal transparan dan anti-benturan.
            </p>
        </div>

        <!-- Floating Glassmorphic Search Bar -->
        <div class="mt-10 sm:mt-12 max-w-4xl mx-auto">
            <form action="{{ route('barangs.index') }}" method="GET"
                  class="bg-white/90 backdrop-blur-xl p-3 sm:p-4 rounded-3xl shadow-xl shadow-slate-200/70 border border-slate-200/90 transition-all duration-300 hover:shadow-2xl hover:shadow-emerald-500/10">
                <div class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
                    <!-- Search Input -->
                    <div class="sm:col-span-5 px-3 py-1">
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">
                            Cari Barang
                        </label>
                        <div class="flex items-center gap-2.5">
                            <i data-lucide="search" class="w-4 h-4 text-emerald-600 shrink-0"></i>
                            <input type="text" name="q" placeholder="Kamera Sony, Tenda Dome, Proyektor..."
                                   class="w-full text-sm text-slate-800 placeholder-slate-400 focus:outline-none font-medium bg-transparent">
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="hidden sm:block sm:col-span-1 border-r border-slate-200 h-10 mx-auto"></div>

                    <!-- Category Selector -->
                    <div class="sm:col-span-3 px-3 py-1">
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">
                            Kategori
                        </label>
                        <div class="flex items-center gap-2">
                            <i data-lucide="grid" class="w-4 h-4 text-slate-400 shrink-0"></i>
                            <select name="kategori" class="w-full text-xs sm:text-sm text-slate-800 bg-transparent focus:outline-none font-semibold cursor-pointer truncate">
                                <option value="">Semua Kategori</option>
                                @foreach($kategoris as $kat)
                                    <option value="{{ $kat->slug }}">{{ $kat->nama }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Divider -->
                    <div class="hidden sm:block sm:col-span-1 border-r border-slate-200 h-10 mx-auto"></div>

                    <!-- Location Selector -->
                    <div class="sm:col-span-2 px-3 py-1">
                        <label class="block text-[10px] font-extrabold text-slate-400 uppercase tracking-wider mb-1">
                            Lokasi
                        </label>
                        <div class="flex items-center gap-2">
                            <i data-lucide="map-pin" class="w-4 h-4 text-slate-400 shrink-0"></i>
                            <select name="lokasi_id" class="w-full text-xs sm:text-sm text-slate-800 bg-transparent focus:outline-none font-semibold cursor-pointer truncate">
                                <option value="">Semua Wilayah</option>
                                @foreach($lokasis as $lok)
                                    <option value="{{ $lok->id }}">{{ $lok->kecamatan }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <!-- Search Action Button -->
                    <div class="sm:col-span-12 mt-2 pt-3 border-t border-slate-100 flex items-center justify-between">
                        <div class="hidden sm:flex items-center gap-2 text-xs text-slate-400 font-medium">
                            <i data-lucide="sparkles" class="w-3.5 h-3.5 text-emerald-500"></i>
                            <span>Temukan barang terdekat dengan ketersediaan real-time</span>
                        </div>
                        <button type="submit"
                                class="w-full sm:w-auto px-7 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-sm rounded-2xl shadow-md shadow-emerald-600/30 transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-center gap-2">
                            <i data-lucide="search" class="w-4 h-4"></i>
                            <span>Cari Barang Sewaan</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Metric Stat Cards -->
        <div class="mt-12 sm:mt-16 grid grid-cols-3 max-w-2xl mx-auto gap-3 sm:gap-6 text-center">
            <div class="p-4 sm:p-5 bg-white/80 backdrop-blur-md rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs hover:border-emerald-300 transition duration-200">
                <div class="text-2xl sm:text-3xl font-extrabold text-slate-900 tracking-tight">{{ $stats['total_barang'] }}+</div>
                <div class="text-[11px] sm:text-xs text-slate-500 font-semibold mt-1">Barang Siap Sewa</div>
            </div>
            <div class="p-4 sm:p-5 bg-white/80 backdrop-blur-md rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs hover:border-emerald-300 transition duration-200">
                <div class="text-2xl sm:text-3xl font-extrabold text-emerald-600 tracking-tight">{{ $stats['total_transaksi'] }}</div>
                <div class="text-[11px] sm:text-xs text-slate-500 font-semibold mt-1">Transaksi Berhasil</div>
            </div>
            <div class="p-4 sm:p-5 bg-white/80 backdrop-blur-md rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-xs hover:border-emerald-300 transition duration-200">
                <div class="text-2xl sm:text-3xl font-extrabold text-indigo-600 tracking-tight">{{ $stats['total_user'] }}</div>
                <div class="text-[11px] sm:text-xs text-slate-500 font-semibold mt-1">Pengguna Terdaftar</div>
            </div>
        </div>
    </div>
</div>

<!-- Category Section -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div class="flex items-center justify-between mb-8">
        <div>
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Kategori Pilihan</span>
            <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Eksplorasi Berdasarkan Kebutuhan</h2>
        </div>
        <a href="{{ route('barangs.index') }}"
           class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/80 transition">
            Lihat Semua <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
        </a>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 sm:gap-6">
        @foreach($kategoris as $kat)
            <a href="{{ route('barangs.index', ['kategori' => $kat->slug]) }}"
               class="group p-6 bg-white rounded-3xl border border-slate-200/90 hover:border-emerald-400 hover:shadow-xl hover:shadow-emerald-500/10 transition-all duration-300 flex flex-col items-center text-center hover:-translate-y-1">
                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-50 to-teal-50 text-emerald-600 flex items-center justify-center mb-4 group-hover:scale-110 group-hover:bg-gradient-to-br group-hover:from-emerald-600 group-hover:to-teal-600 group-hover:text-white transition-all duration-300 shadow-xs">
                    @if($kat->icon == 'camera')
                        <i data-lucide="camera" class="w-7 h-7"></i>
                    @elseif($kat->icon == 'tent')
                        <i data-lucide="tent" class="w-7 h-7"></i>
                    @elseif($kat->icon == 'projector')
                        <i data-lucide="tv" class="w-7 h-7"></i>
                    @elseif($kat->icon == 'party-popper')
                        <i data-lucide="sparkles" class="w-7 h-7"></i>
                    @else
                        <i data-lucide="wrench" class="w-7 h-7"></i>
                    @endif
                </div>
                <h3 class="font-bold text-sm text-slate-900 group-hover:text-emerald-600 transition">{{ $kat->nama }}</h3>
                <span class="text-xs text-slate-400 font-medium mt-1">{{ $kat->barangs_count }} barang tersedia</span>
            </a>
        @endforeach
    </div>
</div>

<!-- Featured Items Section -->
<div class="bg-gradient-to-b from-slate-100/80 via-white to-slate-100/50 py-16 sm:py-20 border-y border-slate-200/70">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-10">
            <div>
                <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Paling Banyak Dicari</span>
                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-900 mt-1">Barang Rekomendasi di Wilayah Anda</h2>
                <p class="text-xs sm:text-sm text-slate-500 mt-1 font-normal">Kondisi prima, harga transparan, dan siap disewa hari ini.</p>
            </div>
            <a href="{{ route('barangs.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100/80 border border-emerald-200/80 transition">
                <span>Buka Katalog Lengkap</span>
                <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($featuredBarangs as $item)
                <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl hover:shadow-slate-200/70 hover:border-emerald-400/80 hover:-translate-y-1.5 transition-all duration-300 flex flex-col">
                    <!-- Image Card Container -->
                    <div class="relative aspect-4/3 bg-slate-100 overflow-hidden">
                        <img src="{{ $item->foto }}" alt="{{ $item->nama_barang }}"
                             class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

                        <!-- Top Badges -->
                        <div class="absolute top-3 left-3">
                            <span class="px-3 py-1 rounded-full text-[10px] font-extrabold bg-white/95 backdrop-blur text-slate-800 shadow-sm border border-slate-200/60">
                                {{ $item->kategori->nama }}
                            </span>
                        </div>
                        <div class="absolute top-3 right-3">
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-600/90 text-white shadow-sm backdrop-blur">
                                <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                                {{ ucfirst($item->status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Content Details -->
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <!-- Meta: Location & Rating -->
                            <div class="flex items-center justify-between text-xs text-slate-500 mb-2 font-medium">
                                <span class="flex items-center gap-1 truncate max-w-[65%]">
                                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500 shrink-0"></i>
                                    <span class="truncate">{{ $item->lokasi->kecamatan }}, {{ $item->lokasi->kota }}</span>
                                </span>
                                <span class="flex items-center gap-1 font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">
                                    <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                                    {{ $item->average_rating }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h3 class="font-extrabold text-sm sm:text-base text-slate-900 line-clamp-1 group-hover:text-emerald-600 transition">
                                <a href="{{ route('barangs.show', $item->slug) }}">
                                    {{ $item->nama_barang }}
                                </a>
                            </h3>

                            <!-- Tags -->
                            <div class="flex flex-wrap gap-1.5 mt-2.5">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600">
                                    {{ $item->metode_pengambilan === 'both' ? 'Ambil & Diantar' : ($item->metode_pengambilan === 'diantar' ? 'Diantar' : 'Ambil Sendiri') }}
                                </span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                                    {{ $item->metode_pembayaran === 'both' ? 'QRIS & Tunai' : strtoupper($item->metode_pembayaran) }}
                                </span>
                            </div>
                        </div>

                        <!-- Price & CTA -->
                        <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tarif Sewa</span>
                                <div class="text-base font-black text-emerald-600">
                                    Rp{{ number_format($item->harga_sewa_per_hari, 0, ',', '.') }}<span class="text-[11px] font-normal text-slate-500">/hari</span>
                                </div>
                            </div>
                            <a href="{{ route('barangs.show', $item->slug) }}"
                               class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold shadow-xs transition duration-200">
                                Cek Jadwal
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Workflow Section: Alur Transaksi -->
<div class="py-20 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center max-w-2xl mx-auto mb-16">
        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold text-emerald-700 bg-emerald-100/70 mb-2">
            <i data-lucide="shield-check" class="w-3.5 h-3.5"></i> Transparan & Bebas Khawatir
        </span>
        <h2 class="text-3xl font-black text-slate-900 mt-2">Alur Transaksi Sewa di Browly</h2>
        <p class="text-xs sm:text-sm text-slate-500 mt-2">
            Dirancang dengan alur sistematis untuk mencegah benturan jadwal dan melindungi hak penyewa serta pemilik barang.
        </p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 sm:gap-6 relative">
        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 text-center relative shadow-xs hover:border-emerald-300 transition duration-200 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-emerald-100/80 text-emerald-700 font-extrabold text-base flex items-center justify-center mx-auto mb-4 shadow-xs">
                    1
                </div>
                <h4 class="font-extrabold text-sm text-slate-900">Pilih Tanggal</h4>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed font-normal">
                    Pilih tanggal sewa melalui kalender interaktif anti double-booking.
                </p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 text-center relative shadow-xs hover:border-indigo-300 transition duration-200 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-indigo-100/80 text-indigo-700 font-extrabold text-base flex items-center justify-center mx-auto mb-4 shadow-xs">
                    2
                </div>
                <h4 class="font-extrabold text-sm text-slate-900">Konfirmasi Pemilik</h4>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed font-normal">
                    Pemilik memiliki batas waktu respon 24 jam untuk menerima atau menolak.
                </p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 text-center relative shadow-xs hover:border-sky-300 transition duration-200 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-sky-100/80 text-sky-700 font-extrabold text-base flex items-center justify-center mx-auto mb-4 shadow-xs">
                    3
                </div>
                <h4 class="font-extrabold text-sm text-slate-900">Pembayaran (24 Jam)</h4>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed font-normal">
                    Bayar via simulasi QRIS atau Cash. Dibatalkan otomatis jika batas waktu habis.
                </p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 text-center relative shadow-xs hover:border-purple-300 transition duration-200 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-purple-100/80 text-purple-700 font-extrabold text-base flex items-center justify-center mx-auto mb-4 shadow-xs">
                    4
                </div>
                <h4 class="font-extrabold text-sm text-slate-900">Chat & Serah Terima</h4>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed font-normal">
                    Chat otomatis aktif untuk janjian ambil sendiri atau pengantaran barang.
                </p>
            </div>
        </div>

        <div class="bg-white p-6 rounded-3xl border border-slate-200/80 text-center relative shadow-xs hover:border-emerald-300 transition duration-200 flex flex-col justify-between">
            <div>
                <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-emerald-600 to-teal-600 text-white font-extrabold text-base flex items-center justify-center mx-auto mb-4 shadow-xs">
                    5
                </div>
                <h4 class="font-extrabold text-sm text-slate-900">Kembali & Review</h4>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed font-normal">
                    Verifikasi fisik barang, perhitungan denda jika telat/rusak, dan review 5 bintang.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Owner Callout Banner -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-16">
    <div class="relative overflow-hidden bg-gradient-to-r from-emerald-600 via-teal-700 to-slate-900 rounded-3xl p-8 sm:p-12 text-white shadow-xl shadow-emerald-900/10 flex flex-col md:flex-row items-center justify-between gap-8">
        <!-- Decorative Glow -->
        <div class="pointer-events-none absolute -right-20 -bottom-20 w-80 h-80 bg-white/10 blur-2xl rounded-full"></div>

        <div class="max-w-xl relative z-10">
            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-bold bg-white/15 text-white backdrop-blur-sm mb-3">
                <i data-lucide="store" class="w-3.5 h-3.5"></i> Untuk Pemilik Barang
            </span>
            <h3 class="text-2xl sm:text-3xl font-black">Punya barang yang jarang terpakai di rumah?</h3>
            <p class="text-emerald-100 text-xs sm:text-sm mt-2 leading-relaxed">
                Sewakan kamera, perlengkapan mendaki, proyektor, atau perkakas Anda ke tetangga sekitar dan dapatkan penghasilan tambahan yang aman.
            </p>
        </div>
        <div class="flex items-center gap-3 shrink-0 relative z-10">
            <a href="{{ route('register') }}"
               class="px-6 py-3.5 rounded-2xl bg-white text-emerald-800 font-extrabold text-sm hover:bg-emerald-50 transition shadow-lg hover:-translate-y-0.5">
                Mulai Sewakan Sekarang
            </a>
        </div>
    </div>
</div>
@endsection
