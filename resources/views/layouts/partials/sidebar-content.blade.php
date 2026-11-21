@php
    $activeMode = request()->routeIs('pemilik.*') ? 'pemilik'
        : (request()->routeIs('penyewa.*') ? 'penyewa' : (auth()->check() ? session('mode') : null));
    $isMobile = $isMobile ?? false;
@endphp

<div class="flex flex-col h-full justify-between">
    {{-- Header: Logo & Brand --}}
    <div>
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                <img src="{{ asset('images/logo.png') }}" alt="Browly" class="browly-nav-logo w-12 h-12 object-contain group-hover:scale-105 transition-transform duration-200 shrink-0">
                <div class="min-w-0">
                    <span class="text-xl font-black tracking-tight text-blue-600 group-hover:text-blue-700 transition block">Browly</span>
                    <span class="text-[9px] uppercase font-bold tracking-wider text-slate-400 block truncate">Platform Sewa Lokal</span>
                </div>
            </a>

            <button type="button" onclick="toggleSidebar()" class="p-1.5 rounded-xl text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition cursor-pointer" title="{{ $isMobile ? 'Tutup Menu' : 'Sembunyikan Sidebar' }}" aria-label="Toggle Sidebar">
                <i data-lucide="{{ $isMobile ? 'x' : 'panel-left-close' }}" class="w-5 h-5"></i>
            </button>
        </div>

        {{-- Navigasi Menu Utama --}}
        <nav class="p-4 space-y-1 overflow-y-auto">
            <div class="px-3.5 py-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                Menu Utama
            </div>

            {{-- Beranda --}}
            <a href="{{ route('home') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ request()->routeIs('home') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                <i data-lucide="home" class="w-4 h-4 {{ request()->routeIs('home') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                <span>Beranda</span>
            </a>

            {{-- Katalog Barang --}}
            <a href="{{ route('barangs.index') }}"
               class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ request()->routeIs('barangs.*') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                <i data-lucide="package" class="w-4 h-4 {{ request()->routeIs('barangs.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                <span>Katalog Barang</span>
            </a>

            @auth
                {{-- Profil Saya --}}
                <a href="{{ route('profile.show') }}"
                   class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ request()->routeIs('profile.*') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                    <i data-lucide="user" class="w-4 h-4 {{ request()->routeIs('profile.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                    <span>Profil Saya</span>
                </a>

                {{-- Dashboard Link Sesuai Mode / Peran --}}
                @if(auth()->user()->isAdmin())
                    <a href="{{ route('admin.dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ request()->routeIs('admin.*') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <i data-lucide="shield" class="w-4 h-4 {{ request()->routeIs('admin.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Dashboard Admin</span>
                    </a>
                @elseif($activeMode === 'pemilik')
                    <a href="{{ route('pemilik.dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ request()->routeIs('pemilik.*') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <i data-lucide="store" class="w-4 h-4 {{ request()->routeIs('pemilik.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Dashboard Pemilik</span>
                    </a>
                @elseif($activeMode === 'penyewa')
                    <a href="{{ route('penyewa.dashboard') }}"
                       class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ request()->routeIs('penyewa.*') ? 'bg-emerald-50 text-emerald-800 border border-emerald-200/70 shadow-2xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100/80' }}">
                        <i data-lucide="shopping-bag" class="w-4 h-4 {{ request()->routeIs('penyewa.*') ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                        <span>Dashboard Penyewa</span>
                    </a>
                @endif

                {{-- Aksi Tambahan --}}
                <div class="pt-4 mt-3 border-t border-slate-100 space-y-1.5">
                    <div class="px-3.5 py-1 text-[10px] font-extrabold text-slate-400 uppercase tracking-wider">
                        Aksi Mode
                    </div>

                    @if(auth()->user()->isPengguna() && $activeMode === 'pemilik')
                        <a href="{{ route('pemilik.barang.create') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 shadow-sm shadow-emerald-600/20 transition-all duration-150">
                            <i data-lucide="plus-circle" class="w-4 h-4"></i>
                            <span>Sewakan Barang Baru</span>
                        </a>
                    @endif

                    @if(auth()->user()->isPengguna())
                        <a href="{{ route('mode.pilih') }}"
                           class="flex items-center gap-3 px-3.5 py-2.5 rounded-2xl text-xs font-bold transition-all duration-150 {{ request()->routeIs('mode.*') ? 'bg-slate-900 text-white shadow-xs' : 'text-slate-700 hover:bg-slate-100/80 border border-slate-200/70' }}">
                            <i data-lucide="arrow-left-right" class="w-4 h-4 text-slate-400"></i>
                            <span>Ganti / Pilih Mode</span>
                        </a>
                    @endif
                </div>
            @endauth
        </nav>
    </div>

    {{-- Footer Sidebar: User Profile & Logout / Login Actions --}}
    <div class="p-4 border-t border-slate-100 bg-slate-50/70">
        @auth
            <div class="flex items-center justify-between gap-3">
                <a href="{{ route('profile.show') }}" class="flex items-center gap-2.5 min-w-0 group" title="Lihat Profil">
                    @if(auth()->user()->avatar_url)
                        <img src="{{ auth()->user()->avatar_url }}" alt="Foto profil" class="w-9 h-9 rounded-full object-cover ring-2 ring-emerald-400/80 shrink-0">
                    @else
                        <div class="w-9 h-9 rounded-full bg-gradient-to-tr from-emerald-500 to-teal-500 text-white font-bold flex items-center justify-center text-xs shadow-2xs shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="min-w-0">
                        <div class="text-xs font-bold text-slate-800 truncate group-hover:text-emerald-700 transition">
                            {{ auth()->user()->name }}
                        </div>
                        <div class="text-[10px] text-slate-400 font-semibold capitalize truncate">
                            {{ auth()->user()->isAdmin() ? 'Admin' : ($activeMode ? 'Mode ' . ucfirst($activeMode) : 'Pengguna') }}
                        </div>
                    </div>
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" title="Keluar dari akun" class="p-2 text-slate-400 hover:text-rose-600 rounded-xl hover:bg-rose-50 transition cursor-pointer">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                    </button>
                </form>
            </div>
        @else
            <div class="space-y-2">
                <a href="{{ route('login') }}" class="w-full py-2.5 px-3 rounded-xl text-xs font-bold text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 text-center block transition shadow-xs">
                    Masuk ke Akun
                </a>
                <a href="{{ route('register') }}" class="w-full py-2.5 px-3 rounded-xl text-xs font-bold text-white bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-center block transition shadow-xs">
                    Daftar Akun Baru
                </a>
            </div>
        @endauth
    </div>
</div>
