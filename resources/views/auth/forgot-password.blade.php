@extends('layouts.app')

@section('title', 'Lupa Password - Browly')

@section('content')
<div class="relative min-h-[70vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 overflow-hidden">
    <div class="pointer-events-none absolute top-10 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-gradient-to-tr from-emerald-400/20 via-teal-300/15 to-indigo-400/15 blur-3xl rounded-full opacity-70"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center relative z-10 px-4">
        <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 flex items-center justify-center mx-auto shadow-md mb-3">
            <i data-lucide="key-round" class="w-8 h-8 text-emerald-600"></i>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">Lupa Password?</h2>
        <p class="mt-1 text-xs sm:text-sm text-slate-500">
            Masukkan email dan nomor telepon yang terdaftar. Jika cocok, Anda bisa langsung membuat password baru.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
        <div class="bg-white/95 backdrop-blur-xl py-8 px-6 sm:px-10 shadow-xl shadow-slate-200/60 border border-slate-200/90 rounded-3xl">
            <form class="space-y-4" action="{{ route('password.verify') }}" method="POST">
                @csrf

                <div>
                    <label for="email" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i data-lucide="mail" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Alamat Email</span>
                    </label>
                    <input id="email" name="email" type="email" required value="{{ old('email') }}" autocomplete="email"
                           placeholder="nama@email.com"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                </div>

                <div>
                    <label for="phone" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i data-lucide="phone" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Nomor Telepon Terdaftar</span>
                    </label>
                    <input id="phone" name="phone" type="tel" required value="{{ old('phone') }}" autocomplete="tel"
                           placeholder="08xxxxxxxxxx"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-md shadow-emerald-600/25 text-xs sm:text-sm font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transition cursor-pointer">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span>Verifikasi & Lanjutkan</span>
                    </button>
                </div>
            </form>

            <div class="mt-6 pt-5 border-t border-slate-100 text-center text-xs text-slate-500">
                Ingat password Anda?
                <a href="{{ route('login') }}" class="font-bold text-emerald-600 hover:text-emerald-700 hover:underline">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
