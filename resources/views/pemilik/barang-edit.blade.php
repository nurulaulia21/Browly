@extends('layouts.app')

@section('title', 'Edit Barang Sewa: ' . $barang->nama_barang . ' - Browly')

@push('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<style>
    .leaflet-popup-content-wrapper { border-radius: 1rem; font-family: inherit; }
    .leaflet-container { font-family: inherit; border-radius: 1rem; z-index: 10; }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-6 flex items-center justify-between">
        <div>
            <a href="{{ route('pemilik.dashboard', ['tab' => 'barang_saya']) }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center gap-1.5 mb-2">
                <i data-lucide="arrow-left" class="w-4 h-4"></i> Kembali ke Dashboard
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Edit Data Barang Sewa</h1>
            <p class="text-xs text-slate-500 mt-1">Perbarui informasi tarif, kondisi, ketersediaan, atau metode pengantaran barang.</p>
        </div>
    </div>

    @if($errors->any())
        <div class="mb-6 p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <div class="font-bold mb-1">Terdapat kesalahan pengisian data:</div>
            <ul class="list-disc list-inside space-y-0.5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('pemilik.barang.update', $barang->id) }}" method="POST" enctype="multipart/form-data" class="bg-white p-6 sm:p-8 rounded-3xl border border-slate-200 shadow-xs space-y-6">
        @csrf
        @method('PUT')

        {{-- Bagian 1: Informasi Dasar --}}
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i data-lucide="info" class="w-4 h-4 text-emerald-600"></i> Informasi Barang
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Nama Barang Sewa <span class="text-rose-500">*</span></label>
                    <input type="text" name="nama_barang" value="{{ old('nama_barang', $barang->nama_barang) }}" required
                           class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>

                <!-- Kategori Barang (Searchable Combobox + Persistent Other + Custom Input) -->
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Barang <span class="text-rose-500">*</span></label>
                    <input type="hidden" name="kategori_id" id="kategoriIdInput" value="{{ old('kategori_id', $barang->kategori_id) }}" required>
                    
                    <!-- Wrapper Khusus Combobox (Relative agar popup dropdown menempel persis di bawah trigger) -->
                    <div class="relative">
                        <div id="kategoriTrigger" onclick="toggleDropdown('kategoriDropdown')"
                             class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white cursor-pointer flex items-center justify-between select-none hover:border-slate-400 transition">
                            <span id="kategoriTriggerText" class="truncate text-slate-700 font-semibold">{{ $barang->kategori->nama ?? '-- Pilih Kategori --' }}</span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" id="kategoriChevron"></i>
                        </div>

                        <div id="kategoriDropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2 space-y-1">
                            <div class="relative mb-1">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                                <input type="text" id="kategoriSearchInput" placeholder="Ketik untuk mencari kategori..."
                                       class="w-full text-xs pl-8 pr-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50"
                                       onclick="event.stopPropagation()" oninput="filterKategoriOptions(this.value)">
                            </div>

                            <div id="kategoriOptionsList" class="max-h-48 overflow-y-auto space-y-0.5 pr-0.5">
                                @foreach($kategoris as $kategori)
                                    <div class="kategori-option px-3 py-2 rounded-xl text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 cursor-pointer flex items-center justify-between transition {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'bg-emerald-50 text-emerald-700 font-bold' : '' }}"
                                         data-id="{{ $kategori->id }}"
                                         data-name="{{ strtolower($kategori->nama) }}"
                                         onclick="selectKategori('{{ $kategori->id }}', '{{ addslashes($kategori->nama) }}')">
                                        <span>{{ $kategori->nama }}</span>
                                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? '' : 'hidden' }} check-icon"></i>
                                    </div>
                                @endforeach
                                <div id="kategoriNoMatch" class="hidden px-3 py-2 text-xs text-slate-400 text-center italic">
                                    Kategori tidak ada dalam daftar umum
                                </div>
                            </div>

                            <!-- Persistent Other Option -->
                            <div id="kategoriOtherOption"
                                 class="px-3 py-2.5 rounded-xl text-xs font-semibold text-emerald-800 bg-emerald-50 hover:bg-emerald-100 cursor-pointer flex items-center justify-between border-t border-slate-100 mt-1 transition"
                                 onclick="selectKategori('other', 'Other (Kategori Lainnya / Baru)')">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="plus-circle" class="w-4 h-4 text-emerald-600"></i>
                                    <span>Other (Kategori Lainnya)</span>
                                </div>
                                <span class="text-[10px] bg-emerald-200 text-emerald-800 px-1.5 py-0.5 rounded-md font-bold">Ketik Sendiri</span>
                            </div>
                        </div>
                    </div>

                    <!-- Input Nama Kategori Baru (Muncul jika pilih 'Other') -->
                    <div id="customKategoriWrapper" class="hidden mt-2 p-3 rounded-2xl bg-emerald-50/60 border border-emerald-200 space-y-1.5 transition-all">
                        <label class="block text-xs font-bold text-emerald-900">
                            Nama Kategori Baru / Lainnya <span class="text-rose-500">*</span>
                        </label>
                        <input type="text" name="kategori_custom" id="customKategoriInput" value="{{ old('kategori_custom') }}"
                               placeholder="Contoh: Alat Musik & Audio, Mainan Hobi..."
                               class="w-full text-xs p-2.5 rounded-xl border border-emerald-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        <p class="text-[11px] text-emerald-700">Kategori ini otomatis disimpan dan langsung ditautkan pada barang sewaan Anda.</p>
                    </div>
                </div>

                <!-- Lokasi Keberadaan Barang (Searchable Combobox + Persistent Maps Option + Maps Modal) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label class="block text-xs font-bold text-slate-700">Lokasi Keberadaan Barang <span class="text-rose-500">*</span></label>
                        <button type="button" onclick="openLocationMapModal()" class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 hover:text-emerald-800 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 px-2 py-0.5 rounded-lg transition">
                            <i data-lucide="map-pin" class="w-3 h-3 text-emerald-600"></i>
                            <span>Pilih via Maps</span>
                        </button>
                    </div>

                    <input type="hidden" name="lokasi_id" id="lokasiIdInput" value="{{ old('lokasi_id', $barang->lokasi_id) }}" required>
                    <input type="hidden" name="lokasi_custom_kecamatan" id="lokasiCustomKecamatan" value="{{ old('lokasi_custom_kecamatan') }}">
                    <input type="hidden" name="lokasi_custom_kota" id="lokasiCustomKota" value="{{ old('lokasi_custom_kota') }}">

                    <!-- Wrapper Khusus Combobox (Relative agar popup dropdown menempel persis di bawah trigger) -->
                    <div class="relative">
                        <div id="lokasiTrigger" onclick="toggleDropdown('lokasiDropdown')"
                             class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white cursor-pointer flex items-center justify-between select-none hover:border-slate-400 transition min-h-[46px]">
                            <div class="min-w-0 flex-1 pr-2">
                                <span id="lokasiTriggerText" class="block truncate text-slate-700 font-semibold">{{ $barang->lokasi ? ($barang->lokasi->kecamatan . ', ' . $barang->lokasi->kota) : '-- Pilih Wilayah / Kecamatan --' }}</span>
                                <span id="lokasiTriggerDetail" class="hidden text-[11px] text-slate-500 font-normal leading-relaxed mt-0.5 break-words line-clamp-2"></span>
                            </div>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform duration-200" id="lokasiChevron"></i>
                        </div>

                        <div id="lokasiDropdown" class="hidden absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 p-2 space-y-1">
                            <div class="relative mb-1">
                                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                                <input type="text" id="lokasiSearchInput" placeholder="Ketik untuk mencari kecamatan / kota..."
                                       class="w-full text-xs pl-8 pr-3 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-slate-50"
                                       onclick="event.stopPropagation()" oninput="filterLokasiOptions(this.value)">
                            </div>

                            <div id="lokasiOptionsList" class="max-h-48 overflow-y-auto space-y-0.5 pr-0.5">
                                @foreach($lokasis as $lokasi)
                                    <div class="lokasi-option px-3 py-2 rounded-xl text-xs text-slate-700 hover:bg-emerald-50 hover:text-emerald-700 cursor-pointer flex items-center justify-between transition {{ old('lokasi_id', $barang->lokasi_id) == $lokasi->id ? 'bg-emerald-50 text-emerald-700 font-bold' : '' }}"
                                         data-id="{{ $lokasi->id }}"
                                         data-name="{{ strtolower($lokasi->kecamatan . ' ' . $lokasi->kota) }}"
                                         data-kecamatan="{{ $lokasi->kecamatan }}"
                                         data-kota="{{ $lokasi->kota }}"
                                         onclick="selectLokasi('{{ $lokasi->id }}', '{{ addslashes($lokasi->kecamatan . ', ' . $lokasi->kota) }}')">
                                        <span>{{ $lokasi->kecamatan }}, {{ $lokasi->kota }}</span>
                                        <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600 {{ old('lokasi_id', $barang->lokasi_id) == $lokasi->id ? '' : 'hidden' }} check-icon"></i>
                                    </div>
                                @endforeach
                                <div id="lokasiNoMatch" class="hidden px-3 py-2 text-xs text-slate-400 text-center italic">
                                    Wilayah tidak ditemukan dalam daftar
                                </div>
                            </div>

                            <!-- Persistent Other / Open Map Option -->
                            <div id="lokasiOtherOption"
                                 class="px-3 py-2.5 rounded-xl text-xs font-semibold text-indigo-800 bg-indigo-50 hover:bg-indigo-100 cursor-pointer flex items-center justify-between border-t border-slate-100 mt-1 transition"
                                 onclick="selectLokasiOtherMaps()">
                                <div class="flex items-center gap-2">
                                    <i data-lucide="map" class="w-4 h-4 text-indigo-600"></i>
                                    <span>Other (Deteksi / Tentukan Lewat Maps)</span>
                                </div>
                                <span class="text-[10px] bg-indigo-200 text-indigo-800 px-1.5 py-0.5 rounded-md font-bold">Buka Maps</span>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kondisi Fisik Barang <span class="text-rose-500">*</span></label>
                    @php
                        $opsiKondisi = ['Sangat Baik (Mulus Seperti Baru)', 'Baik & Berfungsi Normal', 'Normal (Ada Bekas Pemakaian Wajar)'];
                        $kondisiSekarang = old('kondisi', $barang->kondisi);
                        // Data lama yang isinya di luar daftar tetap ditampilkan agar tidak hilang.
                        if ($kondisiSekarang && ! in_array($kondisiSekarang, $opsiKondisi, true)) {
                            array_unshift($opsiKondisi, $kondisiSekarang);
                        }
                    @endphp
                    <select name="kondisi" required class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        @foreach($opsiKondisi as $opsi)
                            <option value="{{ $opsi }}" {{ $kondisiSekarang === $opsi ? 'selected' : '' }}>{{ $opsi }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Status Barang <span class="text-rose-500">*</span></label>
                    <select name="status" required class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="tersedia" {{ old('status', $barang->status) == 'tersedia' ? 'selected' : '' }}>Tersedia (Bisa Disewa)</option>
                        <option value="tidak_tersedia" {{ old('status', $barang->status) == 'tidak_tersedia' ? 'selected' : '' }}>Tidak Tersedia (Sedang Dipakai / Perbaikan)</option>
                        <option value="nonaktif" {{ old('status', $barang->status) == 'nonaktif' ? 'selected' : '' }}>Nonaktif (Sembunyikan dari Katalog)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Harga Sewa Per Hari (Rp) <span class="text-rose-500">*</span></label>
                    <div class="flex items-center rounded-xl border border-slate-300 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-emerald-500">
                        <span class="px-3 py-2.5 bg-slate-100 text-xs font-bold text-slate-600 border-r border-slate-200">Rp</span>
                        <input type="text" inputmode="numeric" name="harga_sewa_per_hari" id="hargaSewaInput"
                               value="{{ number_format((int) str_replace('.', '', (string) old('harga_sewa_per_hari', $barang->harga_sewa_per_hari)), 0, ',', '.') }}"
                               placeholder="50.000" required
                               class="format-rupiah w-full text-xs px-3 py-2.5 focus:outline-none font-medium bg-transparent">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Ketik nominal harga, titik (.) akan muncul otomatis.</p>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1">Foto Barang Sewa</label>
                    <p class="text-[11px] text-slate-500 mb-3">Unggah foto baru dari galeri/file perangkat atau potret langsung menggunakan kamera.</p>

                    <!-- Input Berkas Tersembunyi -->
                    <input type="file" id="fotoFileInput" name="foto_file" accept="image/*" class="hidden">
                    <input type="file" id="nativeCameraInput" accept="image/*" capture="environment" class="hidden">
                    <input type="hidden" name="foto" id="fotoUrlInput" value="{{ old('foto', $barang->getRawOriginal('foto')) }}">

                    <!-- Kartu Pilihan: File / Kamera -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mb-3">
                        <button type="button" onclick="document.getElementById('fotoFileInput').click()"
                                class="flex items-center gap-3 p-4 rounded-2xl border-2 border-dashed border-emerald-300 hover:border-emerald-500 bg-emerald-50/40 hover:bg-emerald-50 text-slate-800 transition text-left group">
                            <div class="w-11 h-11 rounded-xl bg-emerald-100 group-hover:bg-emerald-200 text-emerald-700 flex items-center justify-center shrink-0">
                                <i data-lucide="image-plus" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-900 group-hover:text-emerald-800">Pilih dari Galeri / File</span>
                                <span class="block text-[11px] text-slate-500">Unggah file foto baru (maks. 5MB)</span>
                            </div>
                        </button>

                        <button type="button" onclick="openCameraModal()"
                                class="flex items-center gap-3 p-4 rounded-2xl border-2 border-dashed border-indigo-300 hover:border-indigo-500 bg-indigo-50/40 hover:bg-indigo-50 text-slate-800 transition text-left group">
                            <div class="w-11 h-11 rounded-xl bg-indigo-100 group-hover:bg-indigo-200 text-indigo-700 flex items-center justify-center shrink-0">
                                <i data-lucide="camera" class="w-5 h-5"></i>
                            </div>
                            <div>
                                <span class="block text-xs font-bold text-slate-900 group-hover:text-indigo-800">Ambil dari Kamera</span>
                                <span class="block text-[11px] text-slate-500">Potret langsung dengan kamera perangkat</span>
                            </div>
                        </button>
                    </div>

                    <!-- Kotak Pratinjau Foto -->
                    <div id="previewContainer" class="p-4 rounded-2xl border border-slate-200 bg-slate-50 flex items-center justify-between gap-4">
                        <div class="flex items-center gap-3 min-w-0">
                            <img id="previewImg" src="{{ $barang->foto }}" alt="Pratinjau Foto" class="w-20 h-20 rounded-xl object-cover border border-slate-200 shrink-0">
                            <div class="min-w-0">
                                <span class="inline-flex items-center gap-1 text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded-md mb-1">
                                    <i data-lucide="check" class="w-3 h-3"></i> Foto Barang
                                </span>
                                <p id="previewName" class="text-xs font-semibold text-slate-800 truncate">Foto Aktif</p>
                                <p id="previewSize" class="text-[11px] text-slate-500">Tersimpan di sistem</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0">
                            <button type="button" onclick="document.getElementById('fotoFileInput').click()" class="px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 text-slate-700 font-semibold text-xs transition">
                                Ganti
                            </button>
                            <button type="button" onclick="clearPhoto()" class="p-1.5 rounded-lg text-rose-600 hover:bg-rose-50 transition" title="Hapus Foto">
                                <i data-lucide="trash-2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Opsi Tambahan: Input URL / Template Cepat (Opsional) -->
                    <div class="mt-2.5">
                        <button type="button" onclick="toggleUrlSection()" class="text-[11px] text-slate-500 hover:text-slate-800 flex items-center gap-1 font-medium transition">
                            <i data-lucide="link" class="w-3 h-3"></i>
                            <span>Atau gunakan URL gambar eksternal &darr;</span>
                        </button>
                        <div id="urlSection" class="hidden mt-2 p-3 rounded-2xl bg-slate-50 border border-slate-200 space-y-2">
                            <input type="url" id="manualUrlInput" value="{{ str_starts_with($barang->getRawOriginal('foto') ?? '', 'http') ? $barang->getRawOriginal('foto') : '' }}" placeholder="https://images.unsplash.com/..."
                                   class="w-full text-xs p-2.5 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white"
                                   oninput="applyUrlPhoto(this.value)">
                        </div>
                    </div>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Deskripsi Lengkap & Ketentuan Pemakaian <span class="text-rose-500">*</span></label>
                    <textarea name="deskripsi" required rows="4"
                              class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">{{ old('deskripsi', $barang->deskripsi) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Bagian 2: Pengaturan Transaksi PRD --}}
        <div>
            <h3 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center gap-2">
                <i data-lucide="settings" class="w-4 h-4 text-emerald-600"></i> Ketentuan Transaksi & Logistik (PRD)
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilihan Metode Pembayaran <span class="text-rose-500">*</span></label>
                    <select name="metode_pembayaran" required class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="both" {{ old('metode_pembayaran', $barang->metode_pembayaran) == 'both' ? 'selected' : '' }}>QRIS & Cash (Keduanya)</option>
                        <option value="qris" {{ old('metode_pembayaran', $barang->metode_pembayaran) == 'qris' ? 'selected' : '' }}>Hanya QRIS</option>
                        <option value="cash" {{ old('metode_pembayaran', $barang->metode_pembayaran) == 'cash' ? 'selected' : '' }}>Hanya Cash (Tunai)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Pilihan Metode Pengambilan <span class="text-rose-500">*</span></label>
                    <select name="metode_pengambilan" required class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        <option value="both" {{ old('metode_pengambilan', $barang->metode_pengambilan) == 'both' ? 'selected' : '' }}>Ambil Sendiri & Diantar (Keduanya)</option>
                        <option value="ambil_sendiri" {{ old('metode_pengambilan', $barang->metode_pengambilan) == 'ambil_sendiri' ? 'selected' : '' }}>Hanya Ambil Sendiri (Rp0 Ongkir)</option>
                        <option value="diantar" {{ old('metode_pengambilan', $barang->metode_pengambilan) == 'diantar' ? 'selected' : '' }}>Hanya Diantar</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Tarif Pengantaran Per KM (Rp)</label>
                    <div class="flex items-center rounded-xl border border-slate-300 overflow-hidden bg-white focus-within:ring-2 focus-within:ring-emerald-500 max-w-sm">
                        <span class="px-3 py-2.5 bg-slate-100 text-xs font-bold text-slate-600 border-r border-slate-200">Rp</span>
                        <input type="text" inputmode="numeric" name="tarif_pengantaran_per_km" id="tarifPengantaranInput"
                               value="{{ number_format((int) str_replace('.', '', (string) old('tarif_pengantaran_per_km', $barang->tarif_pengantaran_per_km)), 0, ',', '.') }}"
                               placeholder="5.000"
                               class="format-rupiah w-full text-xs px-3 py-2.5 focus:outline-none font-medium bg-transparent">
                    </div>
                    <p class="text-[10px] text-slate-400 mt-1">Dihitung otomatis saat penyewa memilih opsi 'Diantar'. Format titik (.) muncul otomatis.</p>
                </div>
            </div>
        </div>

        {{-- Submit Button --}}
        <div class="pt-4 border-t border-slate-100 flex items-center justify-end gap-3">
            <a href="{{ route('pemilik.dashboard', ['tab' => 'barang_saya']) }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 font-semibold text-xs transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs shadow-md shadow-emerald-600/20 transition flex items-center gap-2">
                <i data-lucide="check" class="w-4 h-4"></i>
                Simpan Perubahan
            </button>
        </div>
    </form>
</div>

<!-- Modal Peta Interaktif (OpenStreetMap & Leaflet) -->
<div id="locationMapModal" class="hidden fixed inset-0 z-50 bg-black/70 backdrop-blur-xs flex items-center justify-center p-3 sm:p-4">
    <div class="bg-white rounded-3xl max-w-2xl w-full overflow-hidden shadow-2xl border border-slate-200 flex flex-col max-h-[92vh]">
        <!-- Header -->
        <div class="p-4 px-6 bg-slate-900 text-white flex items-center justify-between shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center">
                    <i data-lucide="map-pin" class="w-4 h-4"></i>
                </div>
                <div>
                    <h4 class="text-sm font-bold">Pilih Titik Lokasi Keberadaan Barang</h4>
                    <p class="text-[11px] text-slate-400">Deteksi via GPS atau klik/geser pin di peta untuk memindahkan lokasi</p>
                </div>
            </div>
            <button type="button" onclick="closeLocationMapModal()" class="text-slate-400 hover:text-white p-1 rounded-lg transition">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <!-- Toolbar: Search address & GPS Detect -->
        <div class="p-3 px-6 bg-slate-50 border-b border-slate-200 flex flex-col sm:flex-row items-stretch sm:items-center gap-2 shrink-0">
            <div class="relative flex-1">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2"></i>
                <input type="text" id="mapSearchAddress" placeholder="Cari nama jalan, tempat, atau area..."
                       class="w-full text-xs pl-8 pr-16 py-2 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white"
                       onkeydown="if(event.key==='Enter'){event.preventDefault();searchAddressOnMap();}">
                <button type="button" onclick="searchAddressOnMap()" class="absolute right-1.5 top-1/2 -translate-y-1/2 px-2.5 py-1 text-[11px] font-bold bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                    Cari
                </button>
            </div>
            <button type="button" id="btnGpsDetect" onclick="detectCurrentLocation()"
                    class="px-3.5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition flex items-center justify-center gap-1.5 shrink-0">
                <i data-lucide="crosshair" class="w-3.5 h-3.5"></i>
                <span>Deteksi Lokasi Saya (GPS)</span>
            </button>
        </div>

        <!-- Map Container -->
        <div class="relative flex-1 min-h-[300px] sm:min-h-[360px] bg-slate-100">
            <div id="leafletMap" class="w-full h-full" style="min-height: 340px;"></div>
            <div id="mapLoading" class="hidden absolute inset-0 bg-white/75 backdrop-blur-xs flex flex-col items-center justify-center gap-2" style="z-index: 1000;">
                <i data-lucide="loader-2" class="w-7 h-7 text-emerald-600 animate-spin"></i>
                <span class="text-xs font-semibold text-slate-700" id="mapLoadingText">Memuat koordinat lokasi...</span>
            </div>
        </div>

        <!-- Footer Info & Apply Button -->
        <div class="p-4 px-6 bg-white border-t border-slate-200 shrink-0 space-y-3">
            <div class="p-3 rounded-2xl bg-slate-50 border border-slate-200 flex items-start gap-3">
                <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0 mt-0.5">
                    <i data-lucide="navigation" class="w-4 h-4"></i>
                </div>
                <div class="min-w-0 flex-1">
                    <div class="flex items-center gap-2">
                        <span class="text-[10px] font-bold uppercase tracking-wider text-emerald-700 bg-emerald-100 px-2 py-0.5 rounded">Lokasi Dipilih</span>
                        <span class="text-[10px] text-slate-400" id="mapCoordinatesLabel">-</span>
                    </div>
                    <div class="text-xs font-bold text-slate-800 mt-1" id="mapDetectedKecamatanKota">Belum ada lokasi dipilih</div>
                    <div class="text-[11px] text-slate-500 truncate" id="mapDetectedAddress">Klik di peta atau klik 'Deteksi Lokasi Saya'</div>
                </div>
            </div>

            <div class="flex items-center justify-between gap-3 pt-1">
                <p class="text-[11px] text-slate-400 hidden sm:block">
                    💡 Tip: Anda bisa menggeser (drag) pin di peta untuk memindahkan titik lokasi secara akurat.
                </p>
                <div class="flex items-center gap-2 ml-auto">
                    <button type="button" onclick="closeLocationMapModal()" class="px-4 py-2 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-semibold transition">
                        Batal
                    </button>
                    <button type="button" id="btnApplyLocation" onclick="applySelectedMapLocation()" disabled
                            class="px-5 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-700 disabled:opacity-50 disabled:cursor-not-allowed text-white text-xs font-bold shadow-md shadow-emerald-600/20 transition flex items-center gap-1.5">
                        <i data-lucide="check" class="w-3.5 h-3.5"></i>
                        <span>Gunakan Lokasi Ini</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Kamera Interaktif -->
<div id="cameraModal" class="hidden fixed inset-0 z-50 bg-black/75 backdrop-blur-xs flex items-center justify-center p-4">
    <div class="bg-slate-900 rounded-3xl max-w-md w-full overflow-hidden shadow-2xl border border-slate-800 flex flex-col">
        <div class="p-4 bg-slate-900 text-white flex items-center justify-between border-b border-slate-800">
            <div class="flex items-center gap-2">
                <i data-lucide="camera" class="w-4 h-4 text-emerald-400"></i>
                <h4 class="text-sm font-bold">Ambil Foto dengan Kamera</h4>
            </div>
            <button type="button" onclick="closeCameraModal()" class="text-slate-400 hover:text-white p-1 rounded-lg">
                <i data-lucide="x" class="w-5 h-5"></i>
            </button>
        </div>

        <div class="relative bg-black aspect-4/3 flex items-center justify-center overflow-hidden">
            <video id="cameraVideo" autoplay playsinline class="w-full h-full object-cover"></video>
            <canvas id="cameraCanvas" class="hidden"></canvas>
            <div id="cameraLoading" class="absolute inset-0 flex flex-col items-center justify-center text-slate-400 gap-2 bg-slate-950">
                <i data-lucide="loader-2" class="w-8 h-8 animate-spin text-emerald-400"></i>
                <span class="text-xs">Mengaktifkan kamera...</span>
            </div>
        </div>

        <div class="p-4 bg-slate-900 flex items-center justify-between gap-4">
            <button type="button" onclick="closeCameraModal()" class="px-4 py-2 text-xs font-semibold text-slate-400 hover:text-white">
                Batal
            </button>
            <button type="button" onclick="capturePhoto()" class="px-6 py-2.5 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs shadow-lg shadow-emerald-500/30 flex items-center gap-2">
                <i data-lucide="aperture" class="w-4 h-4"></i>
                Jepret Foto
            </button>
            <button type="button" onclick="switchCamera()" class="p-2 text-slate-400 hover:text-white rounded-lg" title="Balik Kamera">
                <i data-lucide="refresh-cw" class="w-4 h-4"></i>
            </button>
        </div>
    </div>
</div>

<script>
let cameraStream = null;
let currentFacingMode = 'environment';

const fotoFileInput = document.getElementById('fotoFileInput');
const nativeCameraInput = document.getElementById('nativeCameraInput');
const fotoUrlInput = document.getElementById('fotoUrlInput');
const previewContainer = document.getElementById('previewContainer');
const previewImg = document.getElementById('previewImg');
const previewName = document.getElementById('previewName');
const previewSize = document.getElementById('previewSize');
const cameraModal = document.getElementById('cameraModal');
const cameraVideo = document.getElementById('cameraVideo');
const cameraCanvas = document.getElementById('cameraCanvas');
const cameraLoading = document.getElementById('cameraLoading');

function showPreview(url, name = 'Foto Terpilih', size = '') {
    previewImg.src = url;
    previewName.textContent = name;
    previewSize.textContent = size;
    previewContainer.classList.remove('hidden');
    if (window.lucide) { lucide.createIcons(); }
}

function clearPhoto() {
    fotoFileInput.value = '';
    nativeCameraInput.value = '';
    fotoUrlInput.value = '';
    previewImg.src = '';
    previewContainer.classList.add('hidden');
}

fotoFileInput.addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        const file = this.files[0];
        const sizeFormatted = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        const reader = new FileReader();
        reader.onload = function(evt) {
            showPreview(evt.target.result, file.name, sizeFormatted);
            fotoUrlInput.value = '';
        };
        reader.readAsDataURL(file);
    }
});

nativeCameraInput.addEventListener('change', function(e) {
    if (this.files && this.files[0]) {
        fotoFileInput.files = this.files;
        const file = this.files[0];
        const sizeFormatted = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        const reader = new FileReader();
        reader.onload = function(evt) {
            showPreview(evt.target.result, 'Kamera: ' + file.name, sizeFormatted);
            fotoUrlInput.value = '';
        };
        reader.readAsDataURL(file);
    }
});

async function openCameraModal() {
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        nativeCameraInput.click();
        return;
    }
    cameraModal.classList.remove('hidden');
    cameraLoading.classList.remove('hidden');
    startCamera();
}

async function startCamera() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(t => t.stop());
    }
    try {
        cameraStream = await navigator.mediaDevices.getUserMedia({
            video: { facingMode: currentFacingMode }
        });
        cameraVideo.srcObject = cameraStream;
        cameraLoading.classList.add('hidden');
    } catch (err) {
        console.warn('Webcam stream error, fallback to native camera:', err);
        closeCameraModal();
        nativeCameraInput.click();
    }
}

function switchCamera() {
    currentFacingMode = currentFacingMode === 'environment' ? 'user' : 'environment';
    startCamera();
}

function capturePhoto() {
    if (!cameraVideo.videoWidth) return;
    cameraCanvas.width = cameraVideo.videoWidth;
    cameraCanvas.height = cameraVideo.videoHeight;
    const ctx = cameraCanvas.getContext('2d');
    ctx.drawImage(cameraVideo, 0, 0, cameraCanvas.width, cameraCanvas.height);

    cameraCanvas.toBlob(function(blob) {
        if (!blob) return;
        const file = new File([blob], 'foto_kamera_' + Date.now() + '.jpg', { type: 'image/jpeg' });
        const dt = new DataTransfer();
        dt.items.add(file);
        fotoFileInput.files = dt.files;

        const dataUrl = cameraCanvas.toDataURL('image/jpeg');
        const sizeFormatted = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
        showPreview(dataUrl, file.name, sizeFormatted);
        fotoUrlInput.value = '';
        closeCameraModal();
    }, 'image/jpeg', 0.9);
}

function closeCameraModal() {
    if (cameraStream) {
        cameraStream.getTracks().forEach(t => t.stop());
        cameraStream = null;
    }
    cameraModal.classList.add('hidden');
}

function toggleUrlSection() {
    const sec = document.getElementById('urlSection');
    sec.classList.toggle('hidden');
}

function applyUrlPhoto(url) {
    if (!url) return;
    fotoUrlInput.value = url;
    fotoFileInput.value = '';
    showPreview(url, 'URL Gambar Eksternal', 'Gambar Web');
}

// ==========================================
// KATEGORI & LOKASI DROPDOWN & MAP INTERACTIVE
// ==========================================

// Global dropdown outside-click handler
document.addEventListener('click', function(e) {
    const katDropdown = document.getElementById('kategoriDropdown');
    const katTrigger = document.getElementById('kategoriTrigger');
    if (katDropdown && !katDropdown.contains(e.target) && !katTrigger.contains(e.target)) {
        katDropdown.classList.add('hidden');
        document.getElementById('kategoriChevron')?.classList.remove('rotate-180');
    }

    const lokDropdown = document.getElementById('lokasiDropdown');
    const lokTrigger = document.getElementById('lokasiTrigger');
    if (lokDropdown && !lokDropdown.contains(e.target) && !lokTrigger.contains(e.target)) {
        lokDropdown.classList.add('hidden');
        document.getElementById('lokasiChevron')?.classList.remove('rotate-180');
    }
});

function toggleDropdown(id) {
    const dropdown = document.getElementById(id);
    const isKat = id === 'kategoriDropdown';
    const chevron = document.getElementById(isKat ? 'kategoriChevron' : 'lokasiChevron');
    const otherDropdown = document.getElementById(isKat ? 'lokasiDropdown' : 'kategoriDropdown');
    const otherChevron = document.getElementById(isKat ? 'lokasiChevron' : 'kategoriChevron');

    if (otherDropdown) otherDropdown.classList.add('hidden');
    if (otherChevron) otherChevron.classList.remove('rotate-180');

    dropdown.classList.toggle('hidden');
    if (!dropdown.classList.contains('hidden')) {
        chevron.classList.add('rotate-180');
        const searchInput = document.getElementById(isKat ? 'kategoriSearchInput' : 'lokasiSearchInput');
        if (searchInput) {
            setTimeout(() => searchInput.focus(), 50);
        }
    } else {
        chevron.classList.remove('rotate-180');
    }
}

// 1. KATEGORI LOGIC
function filterKategoriOptions(query) {
    const q = query.trim().toLowerCase();
    const options = document.querySelectorAll('.kategori-option');
    let hasMatch = false;

    options.forEach(opt => {
        const name = opt.dataset.name || '';
        if (name.includes(q)) {
            opt.classList.remove('hidden');
            hasMatch = true;
        } else {
            opt.classList.add('hidden');
        }
    });

    const noMatch = document.getElementById('kategoriNoMatch');
    if (noMatch) {
        if (!hasMatch && q !== '') {
            noMatch.classList.remove('hidden');
        } else {
            noMatch.classList.add('hidden');
        }
    }
    // Note: #kategoriOtherOption is always visible at the bottom
}

function selectKategori(id, name) {
    const input = document.getElementById('kategoriIdInput');
    const triggerText = document.getElementById('kategoriTriggerText');
    const customWrapper = document.getElementById('customKategoriWrapper');
    const customInput = document.getElementById('customKategoriInput');
    const searchInput = document.getElementById('kategoriSearchInput');

    input.value = id;
    triggerText.textContent = name;
    triggerText.classList.add('font-semibold', 'text-slate-900');

    document.querySelectorAll('.kategori-option').forEach(opt => {
        const check = opt.querySelector('.check-icon');
        if (opt.dataset.id === id) {
            opt.classList.add('bg-emerald-50', 'text-emerald-700', 'font-bold');
            if (check) check.classList.remove('hidden');
        } else {
            opt.classList.remove('bg-emerald-50', 'text-emerald-700', 'font-bold');
            if (check) check.classList.add('hidden');
        }
    });

    if (id === 'other') {
        customWrapper.classList.remove('hidden');
        customInput.required = true;
        if (searchInput && searchInput.value.trim() && !customInput.value.trim()) {
            customInput.value = searchInput.value.trim();
        }
        setTimeout(() => customInput.focus(), 100);
    } else {
        customWrapper.classList.add('hidden');
        customInput.required = false;
    }

    document.getElementById('kategoriDropdown').classList.add('hidden');
    document.getElementById('kategoriChevron')?.classList.remove('rotate-180');
}

// 2. LOKASI LOGIC
function filterLokasiOptions(query) {
    const q = query.trim().toLowerCase();
    const options = document.querySelectorAll('.lokasi-option');
    let hasMatch = false;

    options.forEach(opt => {
        const name = opt.dataset.name || '';
        if (name.includes(q)) {
            opt.classList.remove('hidden');
            hasMatch = true;
        } else {
            opt.classList.add('hidden');
        }
    });

    const noMatch = document.getElementById('lokasiNoMatch');
    if (noMatch) {
        if (!hasMatch && q !== '') {
            noMatch.classList.remove('hidden');
        } else {
            noMatch.classList.add('hidden');
        }
    }
}

function selectLokasi(id, label) {
    const input = document.getElementById('lokasiIdInput');
    const triggerText = document.getElementById('lokasiTriggerText');
    const triggerDetail = document.getElementById('lokasiTriggerDetail');

    input.value = id;
    triggerText.textContent = label;
    triggerText.classList.add('font-semibold', 'text-slate-900');
    triggerText.classList.remove('font-bold');

    if (triggerDetail) {
        triggerDetail.textContent = '';
        triggerDetail.classList.add('hidden');
    }

    document.getElementById('lokasiCustomKecamatan').value = '';
    document.getElementById('lokasiCustomKota').value = '';

    document.querySelectorAll('.lokasi-option').forEach(opt => {
        const check = opt.querySelector('.check-icon');
        if (opt.dataset.id === id) {
            opt.classList.add('bg-emerald-50', 'text-emerald-700', 'font-bold');
            if (check) check.classList.remove('hidden');
        } else {
            opt.classList.remove('bg-emerald-50', 'text-emerald-700', 'font-bold');
            if (check) check.classList.add('hidden');
        }
    });

    document.getElementById('lokasiDropdown').classList.add('hidden');
    document.getElementById('lokasiChevron')?.classList.remove('rotate-180');
}

function selectLokasiOtherMaps() {
    document.getElementById('lokasiDropdown').classList.add('hidden');
    document.getElementById('lokasiChevron')?.classList.remove('rotate-180');
    openLocationMapModal();
}

// 3. INTERACTIVE LEAFLET MAP MODAL & GPS
let leafletMap = null;
let mapMarker = null;
let detectedLocation = null;
const defaultCoords = [-5.147665, 119.432732]; // Makassar center

function openLocationMapModal() {
    const modal = document.getElementById('locationMapModal');
    modal.classList.remove('hidden');
    if (window.lucide) { lucide.createIcons(); }

    setTimeout(() => {
        initLeafletMap();
    }, 150);
}

function closeLocationMapModal() {
    document.getElementById('locationMapModal').classList.add('hidden');
}

function initLeafletMap() {
    if (typeof L === 'undefined') {
        console.warn('Leaflet JS belum terload, mencoba lagi...');
        setTimeout(initLeafletMap, 300);
        return;
    }

    if (!leafletMap) {
        leafletMap = L.map('leafletMap').setView(defaultCoords, 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(leafletMap);

        // Click anywhere to place or move marker
        leafletMap.on('click', function(e) {
            setMarkerLocation(e.latlng.lat, e.latlng.lng);
        });

        detectCurrentLocation();
    } else {
        leafletMap.invalidateSize();
    }
}

function setMarkerLocation(lat, lng) {
    if (!mapMarker) {
        mapMarker = L.marker([lat, lng], { draggable: true }).addTo(leafletMap);
        mapMarker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            reverseGeocode(pos.lat, pos.lng);
        });
    } else {
        mapMarker.setLatLng([lat, lng]);
    }
    leafletMap.panTo([lat, lng]);
    reverseGeocode(lat, lng);
}

function detectCurrentLocation() {
    if (!navigator.geolocation) {
        alert('Browser Anda tidak mendukung deteksi GPS.');
        if (!mapMarker) setMarkerLocation(defaultCoords[0], defaultCoords[1]);
        return;
    }

    showMapLoading('Mendeteksi posisi GPS Anda...');
    navigator.geolocation.getCurrentPosition(
        function(pos) {
            const lat = pos.coords.latitude;
            const lng = pos.coords.longitude;
            if (leafletMap) {
                leafletMap.setView([lat, lng], 15);
            }
            setMarkerLocation(lat, lng);
        },
        function(err) {
            hideMapLoading();
            console.warn('GPS permission denied or error:', err.message);
            if (!mapMarker) {
                setMarkerLocation(defaultCoords[0], defaultCoords[1]);
            }
        },
        { enableHighAccuracy: true, timeout: 8000, maximumAge: 0 }
    );
}

async function searchAddressOnMap() {
    const input = document.getElementById('mapSearchAddress');
    const query = input.value.trim();
    if (!query) return;

    showMapLoading('Mencari alamat...');
    try {
        const url = `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&limit=1`;
        const res = await fetch(url, { headers: { 'Accept-Language': 'id, en' } });
        const data = await res.json();
        if (data && data.length > 0) {
            const lat = parseFloat(data[0].lat);
            const lng = parseFloat(data[0].lon);
            leafletMap.setView([lat, lng], 15);
            setMarkerLocation(lat, lng);
        } else {
            alert('Alamat atau tempat tidak ditemukan. Coba ketik nama jalan atau daerah lain.');
        }
    } catch (e) {
        console.warn('Search address error:', e);
    } finally {
        hideMapLoading();
    }
}

async function reverseGeocode(lat, lng) {
    showMapLoading('Mendeteksi nama wilayah...');
    try {
        const url = `https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`;
        const res = await fetch(url, { headers: { 'Accept-Language': 'id, en' } });
        const data = await res.json();

        let kecamatan = '';
        let kota = '';

        if (data && data.address) {
            kecamatan = data.address.suburb || data.address.district || data.address.city_district || data.address.quarter || data.address.neighbourhood || data.address.town || data.address.village || '';
            kota = data.address.city || data.address.town || data.address.municipality || data.address.regency || data.address.county || data.address.state || 'Makassar';
        }

        kecamatan = kecamatan.replace(/^Kecamatan\s+/i, '').trim();
        kota = kota.replace(/^(Kota|Kabupaten)\s+/i, '').trim();

        if (!kecamatan) kecamatan = 'Wilayah Peta';
        if (!kota) kota = 'Makassar';

        const displayAddress = data?.display_name || `${kecamatan}, ${kota}`;

        detectedLocation = {
            lat: lat,
            lng: lng,
            kecamatan: kecamatan,
            kota: kota,
            fullAddress: displayAddress
        };

        updateMapDetectedUI();
    } catch (err) {
        console.warn('Reverse geocode error:', err);
        detectedLocation = {
            lat: lat,
            lng: lng,
            kecamatan: 'Wilayah Peta',
            kota: 'Makassar',
            fullAddress: `Koordinat: ${lat.toFixed(5)}, ${lng.toFixed(5)}`
        };
        updateMapDetectedUI();
    } finally {
        hideMapLoading();
    }
}

function updateMapDetectedUI() {
    if (!detectedLocation) return;
    document.getElementById('mapCoordinatesLabel').textContent = `${detectedLocation.lat.toFixed(4)}, ${detectedLocation.lng.toFixed(4)}`;
    document.getElementById('mapDetectedKecamatanKota').textContent = `${detectedLocation.kecamatan}, ${detectedLocation.kota}`;
    document.getElementById('mapDetectedAddress').textContent = detectedLocation.fullAddress;
    document.getElementById('btnApplyLocation').removeAttribute('disabled');
}

function applySelectedMapLocation() {
    if (!detectedLocation) return;

    const kec = detectedLocation.kecamatan || 'Wilayah Peta';
    const kota = detectedLocation.kota || 'Makassar';
    const fullAddress = detectedLocation.fullAddress || `${kec}, ${kota}`;

    // Gunakan lokasi kustom dari peta agar detail alamat dari peta masuk secara presisi
    document.getElementById('lokasiIdInput').value = 'other';
    document.getElementById('lokasiCustomKecamatan').value = kec;
    document.getElementById('lokasiCustomKota').value = kota;

    const triggerText = document.getElementById('lokasiTriggerText');
    const triggerDetail = document.getElementById('lokasiTriggerDetail');

    triggerText.textContent = `📍 ${kec}, ${kota} (dari Peta)`;
    triggerText.classList.add('font-bold', 'text-slate-900');
    triggerText.classList.remove('text-slate-700', 'font-medium');

    if (triggerDetail) {
        triggerDetail.textContent = fullAddress;
        triggerDetail.classList.remove('hidden');
    }

    // Reset pilihan dropdown biasa
    document.querySelectorAll('.lokasi-option').forEach(opt => {
        opt.classList.remove('bg-emerald-50', 'text-emerald-700', 'font-bold');
        const check = opt.querySelector('.check-icon');
        if (check) check.classList.add('hidden');
    });

    closeLocationMapModal();
}

function showMapLoading(text = 'Memuat...') {
    const loading = document.getElementById('mapLoading');
    const loadingText = document.getElementById('mapLoadingText');
    if (loading && loadingText) {
        loadingText.textContent = text;
        loading.classList.remove('hidden');
    }
}

function hideMapLoading() {
    const loading = document.getElementById('mapLoading');
    if (loading) loading.classList.add('hidden');
}

// 4. AUTO FORMAT RUPIAH DENGAN TITIK (.)
document.querySelectorAll('.format-rupiah').forEach(input => {
    input.addEventListener('input', function(e) {
        let cursorPosition = this.selectionStart;
        let originalLength = this.value.length;

        let rawDigits = this.value.replace(/\D/g, '');
        if (!rawDigits) {
            this.value = '';
            return;
        }

        let formatted = rawDigits.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
        this.value = formatted;

        let newLength = formatted.length;
        cursorPosition = cursorPosition + (newLength - originalLength);
        if (cursorPosition < 0) cursorPosition = 0;
        this.setSelectionRange(cursorPosition, cursorPosition);
    });
});

// Bersihkan tanda titik saat form di-submit agar backend menerima integer bersih
const barangForm = document.querySelector('form');
if (barangForm) {
    barangForm.addEventListener('submit', function() {
        document.querySelectorAll('.format-rupiah').forEach(input => {
            input.value = input.value.replace(/\./g, '');
        });
    });
}

// Pre-fill / restore old selection on load
document.addEventListener('DOMContentLoaded', function() {
    const currentKatId = document.getElementById('kategoriIdInput')?.value;
    if (currentKatId) {
        if (currentKatId === 'other') {
            selectKategori('other', 'Other (Kategori Lainnya / Baru)');
        } else {
            const opt = document.querySelector(`.kategori-option[data-id="${currentKatId}"]`);
            if (opt) {
                selectKategori(currentKatId, opt.querySelector('span').textContent);
            }
        }
    }

    const currentLokId = document.getElementById('lokasiIdInput')?.value;
    const customKec = document.getElementById('lokasiCustomKecamatan')?.value;
    const customKota = document.getElementById('lokasiCustomKota')?.value;

    if (currentLokId === 'other' && customKec && customKota) {
        const triggerText = document.getElementById('lokasiTriggerText');
        triggerText.textContent = `📍 ${customKec}, ${customKota} (dari Peta)`;
        triggerText.classList.add('font-semibold', 'text-emerald-700');
    } else if (currentLokId) {
        const opt = document.querySelector(`.lokasi-option[data-id="${currentLokId}"]`);
        if (opt) {
            selectLokasi(currentLokId, opt.querySelector('span').textContent);
        }
    }
});
</script>
@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
@endpush

