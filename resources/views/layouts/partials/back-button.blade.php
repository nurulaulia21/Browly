@php
    $routeName = request()->route()?->getName();

    // Halaman yang tidak perlu tombol kembali otomatis
    // (beranda, atau sudah punya tombol kembali sendiri di dalam halamannya)
    $skip = ['home', 'barangs.show', 'chat.show', 'pemilik.barang.create', 'pemilik.barang.edit', 'mode.pilih'];

    $bookingOwner = isset($booking) && auth()->check() && optional($booking->barang)->user_id === auth()->id();

    // [url tujuan cadangan, label, pakai riwayat browser?]
    $map = [
        'barangs.index'     => [route('home'), 'Kembali', true],
        'login'             => [route('home'), 'Kembali ke Beranda', false],
        'password.request'  => [route('login'), 'Kembali ke Login', false],
        'password.reset'    => [route('login'), 'Kembali ke Login', false],
        'register'          => [route('home'), 'Kembali ke Beranda', false],
        'penyewa.dashboard' => [route('home'), 'Kembali ke Beranda', false],
        'pemilik.dashboard' => [route('home'), 'Kembali ke Beranda', false],
        'admin.dashboard'   => [route('home'), 'Kembali ke Beranda', false],
        'booking.show'      => [
            $bookingOwner ? route('pemilik.dashboard') : route('penyewa.dashboard'),
            $bookingOwner ? 'Kembali ke Dashboard Pemilik' : 'Kembali ke Sewa Saya',
            false,
        ],
        'pembayaran.show'   => [isset($booking) ? route('booking.show', $booking->booking_code) : route('home'), 'Kembali ke Rincian Booking', false],
    ];

    $config = (! in_array($routeName, $skip, true)) ? ($map[$routeName] ?? [route('home'), 'Kembali', true]) : null;
@endphp

@if($config)
    <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 pt-5 -mb-3">
        <a href="{{ $config[0] }}"
           @if($config[2])
           onclick="if (document.referrer && new URL(document.referrer).origin === location.origin && history.length > 1) { history.back(); return false; }"
           @endif
           class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border border-slate-200/90 text-xs font-bold text-slate-700 hover:text-emerald-700 hover:border-emerald-400 hover:bg-emerald-50/60 shadow-xs transition-all duration-200 group cursor-pointer">
            <i data-lucide="arrow-left" class="w-4 h-4 text-slate-500 group-hover:text-emerald-600 transition-transform group-hover:-translate-x-1"></i>
            <span>{{ $config[1] }}</span>
        </a>
    </div>
@endif
