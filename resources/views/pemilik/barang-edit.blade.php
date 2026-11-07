@extends('layouts.app')

@section('title', 'Edit Barang Sewa: ' . $barang->nama_barang . ' - Browly')

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

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kategori Barang <span class="text-rose-500">*</span></label>
                    <select name="kategori_id" required class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        @foreach($kategoris as $kategori)
                            <option value="{{ $kategori->id }}" {{ old('kategori_id', $barang->kategori_id) == $kategori->id ? 'selected' : '' }}>
                                {{ $kategori->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Lokasi Keberadaan Barang <span class="text-rose-500">*</span></label>
                    <select name="lokasi_id" required class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500 bg-white">
                        @foreach($lokasis as $lokasi)
                            <option value="{{ $lokasi->id }}" {{ old('lokasi_id', $barang->lokasi_id) == $lokasi->id ? 'selected' : '' }}>
                                {{ $lokasi->kecamatan }}, {{ $lokasi->kota }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 mb-1.5">Kondisi Fisik Barang <span class="text-rose-500">*</span></label>
                    <input type="text" name="kondisi" value="{{ old('kondisi', $barang->kondisi) }}" required
                           class="w-full text-xs p-3 rounded-xl border border-slate-300 focus:outline-none focus:ring-2 focus:ring-emerald-500">
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
                        <input type="number" name="harga_sewa_per_hari" value="{{ old('harga_sewa_per_hari', $barang->harga_sewa_per_hari) }}" min="5000" step="1000" required
                               class="w-full text-xs px-3 py-2.5 focus:outline-none font-medium bg-transparent">
                    </div>
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
                        <input type="number" name="tarif_pengantaran_per_km" value="{{ old('tarif_pengantaran_per_km', $barang->tarif_pengantaran_per_km) }}" min="0" step="500"
                               class="w-full text-xs px-3 py-2.5 focus:outline-none font-medium bg-transparent">
                    </div>
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
</script>
@endsection

