@extends('layouts.app')

@section('title', 'Daftar Akun Baru - Browly')

@section('content')
<div class="relative py-12 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Ambient Blur Background -->
    <div class="pointer-events-none absolute top-20 left-1/2 -translate-x-1/2 w-[700px] h-[400px] bg-gradient-to-tr from-emerald-400/20 via-teal-300/15 to-indigo-400/15 blur-3xl rounded-full opacity-70"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center relative z-10">
        <a href="{{ route('home') }}" class="inline-block group mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="Browly" class="w-20 h-20 object-contain mx-auto group-hover:scale-105 transition-all duration-300">
        </a>
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
            Daftar Akun Browly
        </h2>
        <p class="mt-1 text-xs sm:text-sm text-slate-500 max-w-sm mx-auto">
            Bergabunglah dengan ekosistem penyewaan barang lokal yang aman dan terpercaya
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-xl relative z-10">
        <div class="bg-white/95 backdrop-blur-xl py-8 px-6 sm:px-10 shadow-xl shadow-slate-200/60 border border-slate-200/90 rounded-3xl">
            <form class="space-y-4" action="{{ route('register') }}" method="POST">
                @csrf

                <!-- Full Name -->
                <div>
                    <label for="name" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i data-lucide="user" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Nama Lengkap</span>
                    </label>
                    <input id="name" name="name" type="text" required value="{{ old('name') }}"
                           placeholder="Contoh: Budi Santoso"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                </div>

                <!-- Email & Phone Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="email" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <i data-lucide="mail" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Alamat Email</span>
                        </label>
                        <input id="email" name="email" type="email" required value="{{ old('email') }}"
                               placeholder="nama@email.com"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                    </div>

                    <div>
                        <label for="phone" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Nomor WhatsApp / HP</span>
                        </label>
                        <input id="phone" name="phone" type="text" required value="{{ old('phone') }}"
                               placeholder="08xxxxxxxxxx"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                    </div>
                </div>

                <!-- Location Selector -->
                <div>
                    <label for="lokasi_id" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Wilayah Domisili (Kecamatan & Kota)</span>
                    </label>
                    <select id="lokasi_id" name="lokasi_id" required
                            class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 bg-white font-medium cursor-pointer transition">
                        <option value="">-- Pilih Wilayah Domisili Anda --</option>
                        @foreach($lokasis as $lok)
                            <option value="{{ $lok->id }}" {{ old('lokasi_id') == $lok->id ? 'selected' : '' }}>
                                {{ $lok->kecamatan }}, {{ $lok->kota }}
                            </option>
                        @endforeach
                    </select>
                    @if($lokasis->isEmpty())
                        <p class="mt-2 text-xs text-rose-600 font-medium">Data wilayah belum tersedia. Jalankan <code>php artisan db:seed</code> terlebih dahulu.</p>
                    @endif
                </div>

                <!-- Full Address / Landmark -->
                <div>
                    <label for="alamat_lengkap" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i data-lucide="navigation" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Alamat Lengkap / Patokan Serah Terima</span>
                    </label>
                    <input id="alamat_lengkap" name="alamat_lengkap" type="text" value="{{ old('alamat_lengkap') }}"
                           placeholder="Nama jalan, nomor rumah, atau patokan serah terima"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                </div>

                <!-- Passwords -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="password" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <i data-lucide="lock" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Kata Sandi</span>
                        </label>
                        <input id="password" name="password" type="password" required
                               placeholder="Min. 6 karakter"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                    </div>

                    <div>
                        <label for="password_confirmation" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                            <i data-lucide="shield-check" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Konfirmasi Sandi</span>
                        </label>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               placeholder="Ulangi sandi"
                               class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-3">
                    <button type="submit" id="btnRegisterSubmit"
                            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-md shadow-emerald-600/25 text-xs sm:text-sm font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                        <i data-lucide="user-plus" class="w-4 h-4" id="registerIcon"></i>
                        <span id="registerBtnText">Daftar Akun Baru</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500 font-normal">
                Sudah memiliki akun?
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 ml-1">
                    Masuk di sini &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const btn = document.getElementById('btnRegisterSubmit');
        const btnText = document.getElementById('registerBtnText');
        const icon = document.getElementById('registerIcon');

        if (form && btn) {
            form.addEventListener('submit', function() {
                btn.disabled = true;
                btn.classList.remove('hover:-translate-y-0.5');
                btn.classList.add('opacity-85', 'cursor-not-allowed');
                if (icon) {
                    icon.classList.add('animate-spin');
                    icon.setAttribute('data-lucide', 'loader-2');
                    lucide.createIcons();
                }
                if (btnText) {
                    btnText.textContent = 'Memproses Pendaftaran Akun...';
                }
            });
        }
    });
</script>
@endpush
@endsection
