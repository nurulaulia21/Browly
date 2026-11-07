<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50 selection:bg-emerald-500 selection:text-white">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Browly - Platform Penyewaan Barang Antar Pengguna')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    @stack('styles')
</head>
<body class="flex flex-col min-h-screen text-slate-800 antialiased bg-slate-50/70">

    <!-- Top Announcement & Demo Switcher Bar -->
    <header class="bg-slate-950 text-slate-300 text-xs py-2 px-4 border-b border-slate-800/80 sticky top-0 z-50 backdrop-blur-md bg-slate-950/90">
        <div class="max-w-7xl mx-auto flex flex-wrap items-center justify-between gap-2.5">
            <div class="flex items-center gap-2.5">
                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-500/15 text-emerald-400 border border-emerald-500/30">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                    Tugas RPLO 2026
                </span>
                <span class="hidden sm:inline text-slate-400 text-xs font-medium">“Barang yang jarang digunakan tidak harus selalu dibeli”</span>
            </div>

            <!-- Quick Demo Login Switcher -->
            <div class="flex items-center gap-1.5 overflow-x-auto text-xs">
                <span class="text-slate-400 mr-1 hidden md:inline text-[11px] font-medium flex items-center gap-1">
                    <i data-lucide="zap" class="w-3 h-3 text-amber-400"></i> Akses Cepat Demo:
                </span>
                <a href="{{ route('quick-login', 'siti') }}" class="px-2.5 py-1 rounded-lg bg-slate-900/90 hover:bg-emerald-600 hover:text-white border border-slate-800 hover:border-emerald-500 transition font-medium text-slate-300 flex items-center gap-1.5 shadow-2xs">
                    <i data-lucide="user" class="w-3 h-3 text-emerald-400"></i>
                    <span>Pengguna: Siti</span>
                </a>
                <a href="{{ route('quick-login', 'budi') }}" class="px-2.5 py-1 rounded-lg bg-slate-900/90 hover:bg-indigo-600 hover:text-white border border-slate-800 hover:border-indigo-500 transition font-medium text-slate-300 flex items-center gap-1.5 shadow-2xs">
                    <i data-lucide="store" class="w-3 h-3 text-indigo-400"></i>
                    <span>Pengguna: Budi</span>
                </a>
                <a href="{{ route('quick-login', 'admin') }}" class="px-2.5 py-1 rounded-lg bg-slate-900/90 hover:bg-amber-600 hover:text-white border border-slate-800 hover:border-amber-500 transition font-medium text-slate-300 flex items-center gap-1.5 shadow-2xs">
                    <i data-lucide="shield-check" class="w-3 h-3 text-amber-400"></i>
                    <span>Admin</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Navigation Bar -->
    <nav class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-[37px] z-40 transition-all shadow-xs">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 items-center">
                <!-- Logo & Brand -->
                <div class="flex items-center gap-8">
                    <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                        <div class="relative w-10 h-10 rounded-2xl bg-gradient-to-tr from-emerald-600 to-teal-500 p-0.5 shadow-sm shadow-emerald-500/20 group-hover:scale-105 transition duration-200">
                            <div class="w-full h-full bg-white rounded-[14px] flex items-center justify-center overflow-hidden">
                                <img src="{{ asset('images/logo.png') }}" alt="Browly" class="w-8 h-8 object-contain">
                            </div>
                        </div>
                        <div>
                            <span class="text-xl font-extrabold tracking-tight text-slate-900 group-hover:text-emerald-600 transition flex items-center gap-1.5">
                                Browly
                                <span class="text-[9px] uppercase font-bold tracking-wider px-1.5 py-0.5 rounded-md bg-emerald-50 text-emerald-700 border border-emerald-200/60 hidden sm:inline-block">Makassar</span>
                            </span>
                            <span class="hidden md:block text-[10px] uppercase font-semibold tracking-wider text-slate-400">Platform Sewa Lokal</span>
                        </div>
                    </a>

                    <!-- Nav Links -->
                    <div class="hidden md:flex items-center gap-1.5">
                        <a href="{{ route('home') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('home') ? 'text-emerald-700 bg-emerald-50/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                            Beranda
                        </a>
                        <a href="{{ route('barangs.index') }}" class="px-3.5 py-2 rounded-xl text-sm font-semibold transition {{ request()->routeIs('barangs.*') ? 'text-emerald-700 bg-emerald-50/80 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/70' }}">
                            Katalog Barang
                        </a>
                    </div>
                </div>

                <!-- Right Side Actions & Profile -->
                <div class="flex items-center gap-2.5">
                    @auth
                        @php
                            $activeMode = request()->routeIs('pemilik.*') ? 'pemilik'
                                : (request()->routeIs('penyewa.*') ? 'penyewa' : session('mode'));
                        @endphp
                        @if(auth()->user()->isPengguna() && $activeMode === 'pemilik')
                            <a href="{{ route('pemilik.barang.create') }}" class="hidden sm:inline-flex items-center gap-1.5 px-3.5 py-2 rounded-xl text-xs font-bold bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white shadow-sm shadow-emerald-600/20 transition duration-150">
                                <i data-lucide="plus-circle" class="w-3.5 h-3.5"></i>
                                Sewakan Barang
                            </a>
                        @endif

                        <div class="flex items-center gap-2 border-l border-slate-200 pl-3">
                            @if(auth()->user()->isAdmin())
                                <a href="{{ route('admin.dashboard') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-amber-50 text-amber-700 hover:bg-amber-100 border border-amber-200 flex items-center gap-1.5 transition">
                                    <i data-lucide="shield" class="w-3.5 h-3.5 text-amber-600"></i> Admin
                                </a>
                            @endif

                            @if(auth()->user()->isPengguna())
                                @if($activeMode === 'pemilik')
                                    <a href="{{ route('pemilik.dashboard') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 border border-indigo-200 flex items-center gap-1.5 transition">
                                        <i data-lucide="store" class="w-3.5 h-3.5 text-indigo-600"></i> Dashboard Pemilik
                                    </a>
                                @elseif($activeMode === 'penyewa')
                                    <a href="{{ route('penyewa.dashboard') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200 flex items-center gap-1.5 transition">
                                        <i data-lucide="package" class="w-3.5 h-3.5 text-emerald-600"></i> Sewa Saya
                                    </a>
                                @endif

                                @if($activeMode)
                                    {{-- Tukar mode Penyewa <-> Pemilik --}}
                                    <form action="{{ route('mode.set', $activeMode === 'pemilik' ? 'penyewa' : 'pemilik') }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" title="Ganti ke mode {{ $activeMode === 'pemilik' ? 'Penyewa' : 'Pemilik' }}"
                                                class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-slate-700 flex items-center gap-1.5 transition cursor-pointer">
                                            <i data-lucide="arrow-left-right" class="w-3.5 h-3.5"></i>
                                            <span class="hidden sm:inline">Ganti ke {{ $activeMode === 'pemilik' ? 'Penyewa' : 'Pemilik' }}</span>
                                        </button>
                                    </form>
                                @else
                                    <a href="{{ route('mode.pilih') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold bg-slate-900 text-white hover:bg-slate-700 flex items-center gap-1.5 transition">
                                        <i data-lucide="arrow-left-right" class="w-3.5 h-3.5"></i> Pilih Mode
                                    </a>
                                @endif
                            @endif

                            <div class="relative ml-1 flex items-center gap-2 bg-slate-50 border border-slate-200/80 rounded-2xl py-1 px-2.5">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-500 text-white font-bold flex items-center justify-center text-xs shadow-2xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                                <span class="hidden lg:inline text-xs font-bold text-slate-800">{{ auth()->user()->name }}</span>
                                
                                <form action="{{ route('logout') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" title="Keluar dari akun" class="p-1 text-slate-400 hover:text-rose-600 rounded-lg hover:bg-rose-50 transition cursor-pointer">
                                        <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-2">
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-700 hover:text-emerald-600 rounded-xl hover:bg-slate-100/70 transition">
                                Masuk
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 text-sm font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 rounded-xl shadow-sm shadow-emerald-600/25 transition">
                                Daftar Akun
                            </a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Flash Notifications -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-5 w-full">
        @if(session('success'))
            <div class="flash-toast flex items-center justify-between gap-3 p-4 mb-3 text-sm rounded-2xl bg-emerald-50/95 text-emerald-900 border border-emerald-200/90 shadow-sm shadow-emerald-500/10 backdrop-blur-xs transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <i data-lucide="check-circle-2" class="w-4 h-4"></i>
                    </div>
                    <div class="font-bold text-xs sm:text-sm">{{ session('success') }}</div>
                </div>
                <button type="button" onclick="dismissToast(this)" class="p-1 rounded-lg text-emerald-600 hover:text-emerald-900 hover:bg-emerald-100/70 transition cursor-pointer" title="Tutup">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @if(session('error'))
            <div class="flash-toast flex items-center justify-between gap-3 p-4 mb-3 text-sm rounded-2xl bg-rose-50/95 text-rose-900 border border-rose-200/90 shadow-sm shadow-rose-500/10 backdrop-blur-xs transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                        <i data-lucide="alert-circle" class="w-4 h-4"></i>
                    </div>
                    <div class="font-bold text-xs sm:text-sm">{{ session('error') }}</div>
                </div>
                <button type="button" onclick="dismissToast(this)" class="p-1 rounded-lg text-rose-600 hover:text-rose-900 hover:bg-rose-100/70 transition cursor-pointer" title="Tutup">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @if(session('info'))
            <div class="flash-toast flex items-center justify-between gap-3 p-4 mb-3 text-sm rounded-2xl bg-sky-50/95 text-sky-900 border border-sky-200/90 shadow-sm shadow-sky-500/10 backdrop-blur-xs transition-all">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
                        <i data-lucide="info" class="w-4 h-4"></i>
                    </div>
                    <div class="font-bold text-xs sm:text-sm">{{ session('info') }}</div>
                </div>
                <button type="button" onclick="dismissToast(this)" class="p-1 rounded-lg text-sky-600 hover:text-sky-900 hover:bg-sky-100/70 transition cursor-pointer" title="Tutup">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif

        @if ($errors->any())
            <div class="flash-toast flex items-start justify-between gap-3 p-4 mb-3 text-sm rounded-2xl bg-amber-50/95 text-amber-900 border border-amber-200/90 shadow-sm shadow-amber-500/10 backdrop-blur-xs transition-all">
                <div>
                    <div class="font-bold flex items-center gap-2 mb-1.5 text-xs sm:text-sm">
                        <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600"></i>
                        Terdapat beberapa kesalahan:
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs text-amber-800">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button type="button" onclick="dismissToast(this)" class="p-1 rounded-lg text-amber-600 hover:text-amber-900 hover:bg-amber-100/70 transition cursor-pointer" title="Tutup">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow">
        @include('layouts.partials.back-button')
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-slate-950 text-slate-400 mt-20 border-t border-slate-800/80">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-14">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-10">
                <div class="md:col-span-1 space-y-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-emerald-600 to-teal-500 p-0.5 shadow-sm">
                            <div class="w-full h-full bg-slate-900 rounded-[10px] flex items-center justify-center">
                                <img src="{{ asset('images/logo.png') }}" alt="Browly" class="w-6 h-6 object-contain">
                            </div>
                        </div>
                        <span class="text-xl font-extrabold text-white tracking-tight">Browly</span>
                    </div>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Platform penyewaan barang antar pengguna berbasis web dalam wilayah lokal untuk mendukung ekonomi sirkular dan efisiensi barang.
                    </p>
                    <div class="text-[11px] text-slate-500 font-mono flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block"></span>
                        Mata Kuliah: RPLO 2026
                    </div>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3.5">Kategori Sewa</h4>
                    <ul class="space-y-2.5 text-xs">
                        <li><a href="{{ route('barangs.index', ['kategori' => 'kamera-fotografi']) }}" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i data-lucide="camera" class="w-3.5 h-3.5 text-slate-500"></i> Kamera & Fotografi</a></li>
                        <li><a href="{{ route('barangs.index', ['kategori' => 'camping-outdoor']) }}" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i data-lucide="tent" class="w-3.5 h-3.5 text-slate-500"></i> Camping & Outdoor</a></li>
                        <li><a href="{{ route('barangs.index', ['kategori' => 'proyektor-audio']) }}" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i data-lucide="tv" class="w-3.5 h-3.5 text-slate-500"></i> Proyektor & Audio</a></li>
                        <li><a href="{{ route('barangs.index', ['kategori' => 'perlengkapan-pesta']) }}" class="hover:text-emerald-400 transition flex items-center gap-1.5"><i data-lucide="sparkles" class="w-3.5 h-3.5 text-slate-500"></i> Perlengkapan Pesta</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3.5">Keamanan & Aturan</h4>
                    <ul class="space-y-2 text-xs">
                        <li class="flex items-center gap-2"><i data-lucide="calendar" class="w-3.5 h-3.5 text-emerald-400"></i> Kalender Anti Benturan</li>
                        <li class="flex items-center gap-2"><i data-lucide="clock" class="w-3.5 h-3.5 text-emerald-400"></i> Batas Waktu Respon 24 Jam</li>
                        <li class="flex items-center gap-2"><i data-lucide="qr-code" class="w-3.5 h-3.5 text-emerald-400"></i> Pembayaran QRIS & Tunai</li>
                        <li class="flex items-center gap-2"><i data-lucide="message-square" class="w-3.5 h-3.5 text-emerald-400"></i> Chat Resmi Transaksi Lunas</li>
                        <li class="flex items-center gap-2"><i data-lucide="shield-alert" class="w-3.5 h-3.5 text-emerald-400"></i> Pencatatan Denda & Review</li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-xs font-bold text-slate-200 uppercase tracking-wider mb-3.5">Wilayah Layanan</h4>
                    <p class="text-xs text-slate-400 mb-3 leading-relaxed">Makassar, Panakkukang, Tamalanrea, Rappocini, Gowa, dan sekitarnya.</p>
                    <div class="p-3.5 rounded-2xl bg-slate-900/90 border border-slate-800 text-[11px] flex items-center justify-between">
                        <div>
                            <span class="text-slate-400 block text-[10px]">Status Sistem:</span>
                            <span class="text-emerald-400 font-bold flex items-center gap-1.5 mt-0.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 inline-block animate-ping"></span>
                                Siap Demo RPLO
                            </span>
                        </div>
                        <i data-lucide="check-circle" class="w-5 h-5 text-emerald-400"></i>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-900 flex flex-col sm:flex-row items-center justify-between gap-3 text-xs text-slate-500">
                <div>
                    &copy; {{ date('Y') }} Browly - Platform Rental Peer-to-Peer Lokal. All rights reserved.
                </div>
                <div class="text-[11px] text-slate-600">
                    Rekayasa Perangkat Lunak Berbasis Objek (RPLO)
                </div>
            </div>
        </div>
    </footer>

    <script>
        lucide.createIcons();

        // Auto-dismiss notification toasts after 3.2 seconds
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('.flash-toast');
            toasts.forEach(toast => {
                setTimeout(() => {
                    dismissToastElement(toast);
                }, 3200);
            });
        });

        function dismissToast(btn) {
            const toast = btn.closest('.flash-toast');
            if (toast) {
                dismissToastElement(toast);
            }
        }

        function dismissToastElement(toast) {
            toast.style.transition = 'opacity 0.4s cubic-bezier(0.16, 1, 0.3, 1), transform 0.4s cubic-bezier(0.16, 1, 0.3, 1), max-height 0.4s ease, margin 0.4s ease, padding 0.4s ease';
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => {
                toast.style.maxHeight = '0';
                toast.style.marginTop = '0';
                toast.style.marginBottom = '0';
                toast.style.paddingTop = '0';
                toast.style.paddingBottom = '0';
                toast.style.overflow = 'hidden';
                setTimeout(() => toast.remove(), 400);
            }, 200);
        }
    </script>
    @stack('scripts')
</body>
</html>
