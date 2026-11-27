@extends('layouts.app')

@section('title', 'Akses ditolak - Browly')

@section('content')
<div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-16 sm:py-24">
    <div class="max-w-xl mx-auto text-center">
        <div class="w-20 h-20 rounded-3xl bg-amber-50 border border-amber-100 text-amber-600 flex items-center justify-center mx-auto shadow-sm">
            <i data-lucide="shield-off" class="w-9 h-9"></i>
        </div>
        <p class="mt-6 text-6xl sm:text-7xl font-black tracking-tight text-slate-900">403</p>
        <h1 class="mt-2 text-xl sm:text-2xl font-extrabold text-slate-900">Akses ditolak</h1>
        <p class="mt-3 text-sm text-slate-500 leading-relaxed">Anda tidak memiliki izin untuk membuka halaman ini. Jika Anda merasa ini keliru, coba masuk dengan akun yang sesuai.</p>
        <div class="mt-8 flex flex-col sm:flex-row items-center justify-center gap-3">
            <a href="{{ route('home') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 text-white text-sm font-bold shadow-md hover:from-emerald-500 hover:to-teal-500 transition">
                <i data-lucide="home" class="w-4 h-4"></i> Kembali ke Beranda
            </a>
            <a href="{{ route('barangs.index') }}" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-white border border-slate-200 text-slate-700 text-sm font-bold hover:border-emerald-400 hover:text-emerald-700 transition">
                <i data-lucide="search" class="w-4 h-4"></i> Jelajahi Katalog
            </a>
        </div>
    </div>
</div>
@endsection
