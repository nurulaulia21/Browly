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

    <script>
        (function() {
            try {
                if (window.innerWidth >= 768 && localStorage.getItem('browly_sidebar_collapsed') === '1') {
                    document.documentElement.classList.add('sidebar-is-collapsed-init');
                }
            } catch(e) {}
        })();
    </script>

    <style>
        html, body {
            overflow-x: clip;
        }
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
    <style>
        /* Sembunyikan ikon mata/hapus bawaan browser (Edge) agar tidak dobel dengan tombol kita */
        input[type="password"]::-ms-reveal,
        input[type="password"]::-ms-clear { display: none; }

        /* Efek gerak logo Browly */
        @keyframes browly-float {
            0%, 100% { transform: translateY(0) rotate(-2deg); }
            50%      { transform: translateY(-8px) rotate(2deg); }
        }
        @keyframes browly-glow {
            0%, 100% { opacity: .35; transform: scale(.9); }
            50%      { opacity: .75; transform: scale(1.15); }
        }
        @keyframes browly-wiggle {
            0%, 100% { transform: rotate(0) scale(1.08); }
            25%      { transform: rotate(-8deg) scale(1.12); }
            75%      { transform: rotate(8deg) scale(1.12); }
        }
        .browly-float { animation: browly-float 3.6s ease-in-out infinite; }
        .browly-glow  { animation: browly-glow 3.6s ease-in-out infinite; }
        .browly-nav-logo { transition: transform .2s ease; }
        .group:hover .browly-nav-logo { animation: browly-wiggle .6s ease-in-out; }
        @media (prefers-reduced-motion: reduce) {
            .browly-float, .browly-glow, .group:hover .browly-nav-logo { animation: none; }
        }
    
        /* Filter katalog yang bisa dilipat (hanya layar kecil) */
        .filter-toggle { display: none; }
        @media (max-width: 1023.98px) {
            .filter-toggle { display: flex; margin-bottom: .75rem; }
            .filter-collapsible.is-closed { display: none; }
        }

        /* Petunjuk "masih bisa digeser": sisi kanan memudar selama masih ada isi tersembunyi */
        .scroll-hint { scrollbar-width: thin; }
        .scroll-hint.has-more {
            -webkit-mask-image: linear-gradient(to right, #000 calc(100% - 44px), transparent);
            mask-image: linear-gradient(to right, #000 calc(100% - 44px), transparent);
        }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen text-slate-800 antialiased bg-slate-50/70">
    <div class="min-h-screen flex flex-col md:flex-row">

        {{-- Desktop Left Sidebar (Menu Samping Kiri - Tetap Diam / Sticky saat halaman discroll) --}}
        <aside id="desktopSidebar" class="hidden md:flex md:w-64 lg:w-72 shrink-0 bg-white border-r border-slate-200/90 h-screen sticky top-0 flex-col justify-between z-30 shadow-xs">
            @include('layouts.partials.sidebar-content', ['isMobile' => false])
        </aside>

        {{-- Mobile Sidebar Drawer & Backdrop --}}
        <div id="mobileSidebarBackdrop" onclick="toggleSidebar()" class="hidden md:hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-xs transition-opacity"></div>
        <aside id="mobileSidebar" class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-slate-200 flex flex-col justify-between -translate-x-full md:hidden transition-transform duration-300 ease-in-out shadow-2xl">
            @include('layouts.partials.sidebar-content', ['isMobile' => true])
        </aside>

        {{-- Right Side Area: Content & Footer --}}
        <div class="flex-1 min-w-0 flex flex-col min-h-screen">
            {{-- Top Bar with Hamburger Menu (☰ - Tetap Diam / Sticky saat halaman discroll) --}}
            <header class="bg-white/95 backdrop-blur-md border-b border-slate-200/80 sticky top-0 z-30 px-4 sm:px-6 py-2.5 flex items-center justify-between shadow-2xs">
                <div class="flex items-center gap-3">
                    <button type="button" id="sidebarToggleBtn" onclick="toggleSidebar()"
                            class="p-2 rounded-xl text-slate-700 hover:text-emerald-700 hover:bg-emerald-50 border border-slate-200/90 hover:border-emerald-300 transition cursor-pointer flex items-center justify-center shadow-2xs group"
                            title="Buka / Sembunyikan Menu Sidebar (☰)" aria-label="Toggle Menu">
                        <i data-lucide="menu" class="w-5 h-5 text-slate-700 group-hover:text-emerald-600 transition"></i>
                    </button>

                    <a href="{{ route('home') }}" id="topbarBrand" class="flex items-center gap-2 group">
                        <img src="{{ asset('images/logo.png') }}" alt="Browly" class="w-8 h-8 object-contain">
                        <span class="text-base font-black tracking-tight text-blue-600 group-hover:text-blue-700 transition">Browly</span>
                    </a>
                </div>

                <div class="flex items-center gap-2.5">
                    @auth
                        @php
                            $activeMode = request()->routeIs('pemilik.*') ? 'pemilik'
                                : (request()->routeIs('penyewa.*') ? 'penyewa' : (auth()->check() ? session('mode') : null));
                        @endphp
                        @if(auth()->user()->isPengguna())
                            <a href="{{ route('mode.pilih') }}" title="Ganti mode Penyewa / Pemilik"
                               class="px-3 py-1.5 rounded-xl text-xs font-bold transition flex items-center gap-1.5 {{ request()->routeIs('pemilik.*') ? 'bg-amber-100 text-amber-800 border border-amber-200' : 'bg-emerald-100 text-emerald-800 border border-emerald-200' }}">
                                <i data-lucide="arrow-left-right" class="w-3.5 h-3.5"></i>
                                <span class="hidden sm:inline">Mode:</span>
                                <span class="capitalize">{{ $activeMode ?? 'Pengguna' }}</span>
                            </a>
                        @endif

                        <a href="{{ route('profile.show') }}" class="flex items-center gap-2 p-1 rounded-xl hover:bg-slate-100 transition" title="Profil Saya">
                            @if(auth()->user()->avatar_url)
                                <img src="{{ auth()->user()->avatar_url }}" alt="Foto profil" class="w-7 h-7 rounded-full object-cover ring-1 ring-slate-200">
                            @else
                                <div class="w-7 h-7 rounded-full bg-emerald-600 text-white font-bold flex items-center justify-center text-xs">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                                </div>
                            @endif
                            <span class="text-xs font-bold text-slate-700 hidden md:inline">{{ auth()->user()->name }}</span>
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-3.5 py-1.5 text-xs font-bold text-slate-700 hover:text-emerald-600 rounded-xl hover:bg-slate-100 transition">
                            Masuk
                        </a>
                        <a href="{{ route('register') }}" class="px-3.5 py-1.5 text-xs font-bold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-xs transition">
                            Daftar
                        </a>
                    @endauth
                </div>
            </header>

            <!-- Flash Notifications -->
            <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 mt-4">
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


        </div>
    </div>

    <script>
        // Inisialisasi awal icon Lucide
        if (window.lucide) {
            lucide.createIcons();
        }

        // Fungsi Toggle Sidebar (Desktop Collapsible & Mobile Slide-out Drawer)
        function toggleSidebar() {
            const isDesktop = window.innerWidth >= 768;

            if (isDesktop) {
                const desktopSidebar = document.getElementById('desktopSidebar');
                if (!desktopSidebar) return;

                const isNowCollapsed = desktopSidebar.classList.toggle('collapsed');
                document.body.classList.toggle('sidebar-is-collapsed', isNowCollapsed);

                try {
                    localStorage.setItem('browly_sidebar_collapsed', isNowCollapsed ? '1' : '0');
                } catch (e) {}
            } else {
                const mobileSidebar = document.getElementById('mobileSidebar');
                const backdrop = document.getElementById('mobileSidebarBackdrop');
                if (!mobileSidebar || !backdrop) return;

                const isClosed = mobileSidebar.classList.contains('-translate-x-full');
                if (isClosed) {
                    mobileSidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                    document.body.classList.add('overflow-hidden');
                } else {
                    mobileSidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        }

        // Kompatibilitas alias untuk fungsi toggle
        window.toggleSidebar = toggleSidebar;
        window.toggleMobileSidebar = toggleSidebar;

        // Pulihkan preferensi sidebar yang tersimpan di localStorage saat reload
        document.addEventListener('DOMContentLoaded', function() {
            if (window.innerWidth >= 768) {
                try {
                    const savedCollapsed = localStorage.getItem('browly_sidebar_collapsed') === '1';
                    const desktopSidebar = document.getElementById('desktopSidebar');
                    if (desktopSidebar && savedCollapsed) {
                        desktopSidebar.classList.add('collapsed');
                        document.body.classList.add('sidebar-is-collapsed');
                    }
                } catch (e) {}
            }
            if (document.documentElement.classList.contains('sidebar-is-collapsed-init')) {
                document.documentElement.classList.remove('sidebar-is-collapsed-init');
            }

            if (window.lucide) {
                lucide.createIcons();
            }
        });

        // Tutup mobile drawer jika window diperlebar ke ukuran desktop
        window.addEventListener('resize', function() {
            if (window.innerWidth >= 768) {
                const mobileSidebar = document.getElementById('mobileSidebar');
                const backdrop = document.getElementById('mobileSidebarBackdrop');
                if (mobileSidebar && !mobileSidebar.classList.contains('-translate-x-full')) {
                    mobileSidebar.classList.add('-translate-x-full');
                    if (backdrop) backdrop.classList.add('hidden');
                    document.body.classList.remove('overflow-hidden');
                }
            }
        });

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
    <script>
        // Tombol lihat / sembunyikan password untuk semua kolom password
        document.addEventListener('DOMContentLoaded', function () {
            var EYE = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 12s3.5-7 10-7 10 7 10 7-3.5 7-10 7-10-7-10-7Z"/><circle cx="12" cy="12" r="3"/></svg>';
            var EYE_OFF = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9.88 9.88a3 3 0 1 0 4.24 4.24"/><path d="M10.73 5.08A10.43 10.43 0 0 1 12 5c6.5 0 10 7 10 7a13.16 13.16 0 0 1-1.67 2.68"/><path d="M6.61 6.61A13.53 13.53 0 0 0 2 12s3.5 7 10 7a9.74 9.74 0 0 0 5.39-1.61"/><line x1="2" x2="22" y1="2" y2="22"/></svg>';

            document.querySelectorAll('input[type="password"]').forEach(function (input) {
                var wrap = document.createElement('div');
                wrap.style.position = 'relative';
                input.parentNode.insertBefore(wrap, input);
                wrap.appendChild(input);
                input.style.paddingRight = '2.75rem';

                var btn = document.createElement('button');
                btn.type = 'button';
                btn.setAttribute('aria-label', 'Tampilkan password');
                btn.setAttribute('title', 'Tampilkan / sembunyikan password');
                btn.style.cssText = 'position:absolute;top:0;bottom:0;right:0;padding:0 .8rem;display:flex;align-items:center;background:none;border:0;color:#94a3b8;cursor:pointer;';
                btn.innerHTML = EYE;
                btn.addEventListener('mouseenter', function () { btn.style.color = '#059669'; });
                btn.addEventListener('mouseleave', function () { btn.style.color = '#94a3b8'; });
                btn.addEventListener('click', function () {
                    var show = input.type === 'password';
                    input.type = show ? 'text' : 'password';
                    btn.innerHTML = show ? EYE_OFF : EYE;
                    btn.setAttribute('aria-label', show ? 'Sembunyikan password' : 'Tampilkan password');
                });
                wrap.appendChild(btn);
            });
        });
    </script>
    <script>
        // Tandai area yang masih bisa digeser ke kanan (tab dan tabel di layar kecil)
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('.scroll-hint').forEach(function (el) {
                function perbarui() {
                    el.classList.toggle('has-more', el.scrollWidth - el.clientWidth - el.scrollLeft > 6);
                }
                el.addEventListener('scroll', perbarui, { passive: true });
                window.addEventListener('resize', perbarui);
                perbarui();
            });
        });
    </script>
    @stack('scripts')
</body>
</html>