@extends('layouts.app')

@section('title', 'Katalog Barang Sewaan - Browly')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">
    <!-- Header with Result Count & Sorter -->
    <div class="mb-8 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-100/70 text-emerald-800">
                    <i data-lucide="compass" class="w-3.5 h-3.5"></i> Eksplorasi
                </span>
            </div>
            <h1 class="text-2xl sm:text-3xl font-black text-slate-900 tracking-tight">Katalog Barang Sewaan</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1">
                Menemukan <span class="font-extrabold text-emerald-600">{{ $barangs->total() }}</span> barang siap sewa di wilayah lokal Anda.
            </p>
        </div>

        <!-- Sort Control -->
        <form method="GET" action="{{ route('barangs.index') }}" class="flex items-center gap-2 self-start sm:self-auto">
            @foreach(request()->except('sort', 'page') as $key => $val)
                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
            @endforeach
            <label for="sort" class="text-xs font-bold text-slate-400 uppercase tracking-wider whitespace-nowrap">
                Urutan:
            </label>
            <div class="relative">
                <select id="sort" name="sort" onchange="this.form.submit()"
                        class="text-xs font-bold border border-slate-200/90 rounded-xl px-3.5 py-2.5 bg-white text-slate-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 shadow-xs cursor-pointer pr-8">
                    <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru Ditambahkan</option>
                    <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>Harga: Terendah ke Tertinggi</option>
                    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>Harga: Tertinggi ke Terendah</option>
                </select>
                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2.5 text-slate-400">
                    <i data-lucide="chevron-down" class="w-3.5 h-3.5"></i>
                </div>
            </div>
        </form>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        <!-- Filter Sidebar -->
        <div class="lg:col-span-1">
            <div class="bg-white/95 backdrop-blur-md p-6 rounded-3xl border border-slate-200/90 shadow-sm sticky top-24">
                <div class="flex items-center justify-between pb-4 border-b border-slate-100 mb-5">
                    <span class="text-sm font-extrabold text-slate-900 flex items-center gap-2">
                        <i data-lucide="sliders-horizontal" class="w-4 h-4 text-emerald-600"></i>
                        <span>Filter Pencarian</span>
                    </span>
                    @if(request()->anyFilled(['q', 'kategori', 'lokasi_id', 'pengambilan', 'min_harga', 'max_harga']))
                        <a href="{{ route('barangs.index') }}"
                           class="text-xs font-bold text-rose-600 hover:text-rose-700 bg-rose-50 px-2 py-1 rounded-lg transition">
                            Reset
                        </a>
                    @endif
                </div>

                <form method="GET" action="{{ route('barangs.index') }}" class="space-y-5">
                    <!-- Search Input -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <i data-lucide="search" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Nama Barang</span>
                        </label>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Contoh: Sony A7, Tenda..."
                               class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 font-medium bg-white">
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <i data-lucide="grid" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Kategori</span>
                        </label>
                        <select name="kategori"
                                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-medium cursor-pointer">
                            <option value="">Semua Kategori</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->slug }}" {{ request('kategori') == $kat->slug ? 'selected' : '' }}>
                                    {{ $kat->nama }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Wilayah Domisili</span>
                        </label>
                        <select name="lokasi_id"
                                class="w-full text-xs px-3.5 py-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white font-medium cursor-pointer">
                            <option value="">Semua Wilayah</option>
                            @foreach($lokasis as $lok)
                                <option value="{{ $lok->id }}" {{ request('lokasi_id') == $lok->id ? 'selected' : '' }}>
                                    {{ $lok->kecamatan }}, {{ $lok->kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Delivery Method Filter -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <i data-lucide="truck" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Metode Pengambilan</span>
                        </label>
                        <div class="space-y-1.5 text-xs font-medium text-slate-600">
                            <label class="flex items-center gap-2 p-2 rounded-xl hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="pengambilan" value="" {{ !request('pengambilan') ? 'checked' : '' }}
                                       class="text-emerald-600 focus:ring-emerald-500">
                                <span>Semua Metode</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-xl hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="pengambilan" value="ambil_sendiri" {{ request('pengambilan') == 'ambil_sendiri' ? 'checked' : '' }}
                                       class="text-emerald-600 focus:ring-emerald-500">
                                <span>Ambil Sendiri (Rp0)</span>
                            </label>
                            <label class="flex items-center gap-2 p-2 rounded-xl hover:bg-slate-50 cursor-pointer">
                                <input type="radio" name="pengambilan" value="diantar" {{ request('pengambilan') == 'diantar' ? 'checked' : '' }}
                                       class="text-emerald-600 focus:ring-emerald-500">
                                <span>Diantar Pemilik</span>
                            </label>
                        </div>
                    </div>

                    <!-- Max Price -->
                    <div>
                        <label class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <i data-lucide="banknote" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Batas Harga / Hari (Rp)</span>
                        </label>
                        <div class="flex items-center rounded-xl border border-slate-300 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-emerald-500 focus-within:border-emerald-500">
                            <span class="px-3 py-2.5 bg-slate-100 text-xs font-bold text-slate-600 border-r border-slate-200">Rp</span>
                            <input type="number" name="max_harga" value="{{ request('max_harga') }}" placeholder="150000"
                                   class="w-full text-xs px-3 py-2 focus:outline-none font-medium bg-transparent">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit"
                            class="w-full py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition-all duration-200 flex items-center justify-center gap-2">
                        <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                        <span>Terapkan Filter</span>
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Grid -->
        <div class="lg:col-span-3">
            @if($barangs->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                    @foreach($barangs as $item)
                        <div class="group bg-white rounded-3xl border border-slate-200/80 overflow-hidden shadow-xs hover:shadow-xl hover:shadow-slate-200/70 hover:border-emerald-400/80 hover:-translate-y-1.5 transition-all duration-300 flex flex-col">
                            <!-- Image -->
                            <div class="relative aspect-4/3 bg-slate-100 overflow-hidden">
                                <img src="{{ $item->foto }}" alt="{{ $item->nama_barang }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>

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

                            <!-- Info -->
                            <div class="p-5 flex-grow flex flex-col justify-between">
                                <div>
                                    <div class="flex items-center justify-between text-xs text-slate-500 mb-2 font-medium">
                                        <span class="flex items-center gap-1 truncate max-w-[65%]">
                                            <i data-lucide="map-pin" class="w-3.5 h-3.5 text-rose-500 shrink-0"></i>
                                            <span class="truncate">{{ $item->lokasi->kecamatan }}, {{ $item->lokasi->kota }}</span>
                                        </span>
                                        <span class="flex items-center gap-1 font-bold text-amber-600 bg-amber-50 px-2 py-0.5 rounded-md">
                                            <i data-lucide="star" class="w-3.5 h-3.5 fill-amber-400 text-amber-400"></i>
                                            {{ $item->average_rating }} ({{ $item->reviews_count }})
                                        </span>
                                    </div>

                                    <h3 class="font-extrabold text-base text-slate-900 line-clamp-1 group-hover:text-emerald-600 transition">
                                        <a href="{{ route('barangs.show', $item->slug) }}">
                                            {{ $item->nama_barang }}
                                        </a>
                                    </h3>

                                    <p class="text-xs text-slate-500 line-clamp-2 mt-1.5 font-normal leading-relaxed">
                                        {{ Str::limit($item->deskripsi, 85) }}
                                    </p>

                                    <div class="flex flex-wrap gap-1.5 mt-3">
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-slate-100 text-slate-600">
                                            Kondisi: {{ $item->kondisi }}
                                        </span>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-semibold bg-emerald-50 text-emerald-700">
                                            {{ $item->metode_pembayaran === 'both' ? 'QRIS & Tunai' : strtoupper($item->metode_pembayaran) }}
                                        </span>
                                    </div>
                                </div>

                                <div class="mt-5 pt-3.5 border-t border-slate-100 flex items-center justify-between">
                                    <div>
                                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Tarif Sewa</span>
                                        <div class="text-base font-black text-emerald-600">
                                            Rp{{ number_format($item->harga_sewa_per_hari, 0, ',', '.') }}<span class="text-[11px] font-normal text-slate-500">/hari</span>
                                        </div>
                                    </div>
                                    <a href="{{ route('barangs.show', $item->slug) }}"
                                       class="px-3.5 py-2 rounded-xl bg-slate-900 hover:bg-emerald-600 text-white text-xs font-bold shadow-xs transition duration-200">
                                        Detail & Booking
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="mt-10">
                    {{ $barangs->links() }}
                </div>
            @else
                <div class="bg-white p-12 text-center rounded-3xl border border-slate-200/90 shadow-xs">
                    <div class="w-16 h-16 rounded-3xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto mb-4">
                        <i data-lucide="package-x" class="w-8 h-8"></i>
                    </div>
                    <h3 class="text-base font-extrabold text-slate-900">Tidak ada barang yang cocok</h3>
                    <p class="text-xs text-slate-500 mt-1 max-w-sm mx-auto leading-relaxed">
                        Coba gunakan kata kunci pencarian yang lebih umum atau sesuaikan filter wilayah dan kategori.
                    </p>
                    <a href="{{ route('barangs.index') }}"
                       class="inline-flex items-center gap-1.5 mt-5 px-5 py-2.5 rounded-xl bg-slate-900 text-white text-xs font-bold hover:bg-slate-800 transition shadow-xs">
                        <i data-lucide="rotate-ccw" class="w-3.5 h-3.5"></i>
                        <span>Reset Semua Filter</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
