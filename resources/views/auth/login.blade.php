@extends('layouts.app')

@section('title', 'Masuk ke Akun - Browly')

@section('content')
<div class="relative min-h-[75vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Ambient Blur Background -->
    <div class="pointer-events-none absolute top-10 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-gradient-to-tr from-emerald-400/20 via-teal-300/15 to-indigo-400/15 blur-3xl rounded-full opacity-70"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center relative z-10">
        <a href="{{ route('home') }}" class="inline-block group mb-3">
            <img src="{{ asset('images/logo.png') }}" alt="Browly" class="w-20 h-20 object-contain mx-auto group-hover:scale-105 transition-all duration-300">
        </a>
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
            Masuk ke Browly
        </h2>
        <p class="mt-1 text-xs sm:text-sm text-slate-500">
            Sewa barang yang Anda butuhkan atau kelola barang sewaan Anda
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10">
        <!-- Main Login Card -->
        <div class="bg-white/95 backdrop-blur-xl py-8 px-6 sm:px-10 shadow-xl shadow-slate-200/60 border border-slate-200/90 rounded-3xl">
            <form class="space-y-4" action="{{ route('login') }}" method="POST">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Alamat Email</span>
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}"
                           placeholder="nama@email.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider">
                            <i data-lucide="lock" class="w-3.5 h-3.5 text-emerald-600"></i>
                            <span>Kata Sandi</span>
                        </label>
                        <a href="{{ route('password.request') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 hover:underline">
                            Lupa password?
                        </a>
                    </div>
                    <input id="password" name="password" type="password" autocomplete="current-password" required
                           placeholder="••••••••"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center cursor-pointer">
                        <input id="remember" name="remember" type="checkbox"
                               class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                        <span class="ml-2 block text-xs text-slate-600 font-medium">
                            Ingat saya di perangkat ini
                        </span>
                    </label>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" id="btnLoginSubmit"
                            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-md shadow-emerald-600/25 text-xs sm:text-sm font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transition-all duration-200 hover:-translate-y-0.5 cursor-pointer">
                        <i data-lucide="log-in" class="w-4 h-4" id="loginIcon"></i>
                        <span id="loginBtnText">Masuk ke Akun</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500 font-normal">
                Belum memiliki akun?
                <a href="{{ route('register') }}" class="font-bold text-emerald-600 hover:text-emerald-700 ml-1">
                    Daftar akun baru &rarr;
                </a>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const btn = document.getElementById('btnLoginSubmit');
        const btnText = document.getElementById('loginBtnText');
        const icon = document.getElementById('loginIcon');

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
                    btnText.textContent = 'Memproses Masuk...';
                }
            });
        }
    });
</script>
@endpush
@endsection
