@extends('layouts.app')

@section('title', 'Buat Password Baru - Browly')

@section('content')
<div class="relative min-h-[70vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8 overflow-hidden">
    <div class="pointer-events-none absolute top-10 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-gradient-to-tr from-emerald-400/20 via-teal-300/15 to-indigo-400/15 blur-3xl rounded-full opacity-70"></div>

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center relative z-10 px-4">
        <div class="w-16 h-16 rounded-3xl bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 flex items-center justify-center mx-auto shadow-md mb-3">
            <i data-lucide="lock-keyhole" class="w-8 h-8 text-emerald-600"></i>
        </div>
        <h2 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">Buat Password Baru</h2>
        <p class="mt-1 text-xs sm:text-sm text-slate-500">
            Identitas terverifikasi untuk <span class="font-bold text-slate-700">{{ $email }}</span>. Silakan isi password baru Anda.
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md relative z-10 px-4 sm:px-0">
        <div class="bg-white/95 backdrop-blur-xl py-8 px-6 sm:px-10 shadow-xl shadow-slate-200/60 border border-slate-200/90 rounded-3xl">
            <form class="space-y-4" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <input type="hidden" name="email" value="{{ $email }}">

                <div>
                    <label for="password" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i data-lucide="lock" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Password Baru</span>
                    </label>
                    <input id="password" name="password" type="password" required minlength="6" autocomplete="new-password"
                           placeholder="Minimal 6 karakter"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                </div>

                <div>
                    <label for="password_confirmation" class="flex items-center gap-1.5 text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        <i data-lucide="lock" class="w-3.5 h-3.5 text-emerald-600"></i>
                        <span>Ulangi Password Baru</span>
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="6" autocomplete="new-password"
                           placeholder="Ketik ulang password baru"
                           class="w-full px-4 py-2.5 rounded-xl border border-slate-300 text-xs sm:text-sm focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 font-medium bg-white transition">
                </div>

                <div class="pt-2">
                    <button type="submit"
                            class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-2xl shadow-md shadow-emerald-600/25 text-xs sm:text-sm font-extrabold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 transition cursor-pointer">
                        <i data-lucide="check-circle" class="w-4 h-4"></i>
                        <span>Simpan Password Baru</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
