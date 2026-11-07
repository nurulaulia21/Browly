@extends('layouts.app')

@section('title', 'Pilih Mode - Browly')

@section('content')
<div class="relative py-12 sm:py-16 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <div class="pointer-events-none absolute top-10 left-1/2 -translate-x-1/2 w-[600px] h-[350px] bg-gradient-to-tr from-emerald-400/20 via-teal-300/15 to-indigo-400/15 blur-3xl rounded-full"></div>

    <div class="relative z-10 max-w-3xl mx-auto text-center">
        <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-slate-900">
            Halo, {{ auth()->user()->name }} 👋
        </h1>
        <p class="text-sm text-slate-500 mt-2">Hari ini Anda ingin masuk sebagai apa? Anda bisa berganti mode kapan saja dari menu atas.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 mt-10">
            {{-- Penyewa --}}
            <form action="{{ route('mode.set', 'penyewa') }}" method="POST">
                @csrf
                <button type="submit"
                        class="group w-full h-full text-left p-6 rounded-3xl bg-white border-2 border-slate-200 hover:border-emerald-500 hover:shadow-xl hover:shadow-emerald-100 transition-all duration-200 cursor-pointer">
                    <div class="w-14 h-14 rounded-2xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <i data-lucide="user" class="w-7 h-7"></i>
                    </div>
                    <div class="text-lg font-black text-slate-900">Penyewa</div>
                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Cari dan sewa barang untuk kebutuhan sementara. Lihat status sewa Anda di Dashboard Penyewa.</p>
                    <span class="inline-flex items-center gap-1.5 mt-4 text-xs font-bold text-emerald-700">
                        Masuk sebagai Penyewa <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition"></i>
                    </span>
                </button>
            </form>

            {{-- Pemilik --}}
            <form action="{{ route('mode.set', 'pemilik') }}" method="POST">
                @csrf
                <button type="submit"
                        class="group w-full h-full text-left p-6 rounded-3xl bg-white border-2 border-slate-200 hover:border-rose-500 hover:shadow-xl hover:shadow-rose-100 transition-all duration-200 cursor-pointer">
                    <div class="w-14 h-14 rounded-2xl bg-rose-100 text-rose-700 flex items-center justify-center mb-4 group-hover:scale-110 transition">
                        <i data-lucide="store" class="w-7 h-7"></i>
                    </div>
                    <div class="text-lg font-black text-slate-900">Pemilik</div>
                    <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Sewakan barang Anda, setujui booking masuk, dan pantau pengembalian di Dashboard Pemilik.</p>
                    <span class="inline-flex items-center gap-1.5 mt-4 text-xs font-bold text-rose-700">
                        Masuk sebagai Pemilik <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-1 transition"></i>
                    </span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
