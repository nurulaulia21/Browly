<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\DashboardAdminController;
use App\Http\Controllers\DashboardPemilikController;
use App\Http\Controllers\DashboardPenyewaController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ModeController;
use App\Http\Controllers\PembayaranController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Borrowly Platform
|--------------------------------------------------------------------------
*/

// Halaman Publik
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/barangs', [BarangController::class, 'index'])->name('barangs.index');
Route::get('/barangs/{slug}', [BarangController::class, 'show'])->name('barangs.show');
Route::post('/barangs/{barang}/check-availability', [BarangController::class, 'checkAvailability'])->name('barangs.check-availability');

// Autentikasi & Quick Demo Login
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::get('/quick-login/{role}', [AuthController::class, 'quickLogin'])->name('quick-login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Rute Pengguna Terautentikasi (Auth)
Route::middleware('auth')->group(function () {
    // Pilih mode pengguna: Penyewa atau Pemilik (bisa ditukar kapan saja)
    Route::get('/pilih-mode', [ModeController::class, 'pilih'])->name('mode.pilih');
    Route::post('/pilih-mode/{mode}', [ModeController::class, 'set'])->whereIn('mode', ['penyewa', 'pemilik'])->name('mode.set');

    // Booking
    Route::post('/barangs/{barang}/book', [BookingController::class, 'store'])->name('booking.store');
    Route::get('/booking/{code}', [BookingController::class, 'show'])->name('booking.show');

    // Pembayaran Simulasi QRIS & Cash
    Route::get('/booking/{code}/pembayaran', [PembayaranController::class, 'show'])->name('pembayaran.show');
    Route::post('/booking/{code}/pembayaran', [PembayaranController::class, 'process'])->name('pembayaran.process');

    // Chat Khusus Transaksi (Aktif hanya jika sudah dibayar)
    Route::get('/booking/{code}/chat', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/booking/{code}/chat', [ChatController::class, 'sendMessage'])->name('chat.send');

    // Pengembalian Barang
    Route::post('/booking/{booking}/pengembalian', [PengembalianController::class, 'store'])->name('pengembalian.store');
    Route::post('/booking/{booking}/pengembalian/confirm', [PengembalianController::class, 'confirm'])->name('pengembalian.confirm');

    // Review & Rating
    Route::post('/booking/{booking}/review', [ReviewController::class, 'store'])->name('review.store');

    // Dashboard Penyewa
    Route::get('/dashboard/penyewa', [DashboardPenyewaController::class, 'index'])->name('penyewa.dashboard');

    // Dashboard & Manajemen Pemilik (Role: Pemilik / Admin)
    Route::middleware('role:pemilik,admin')->group(function () {
        Route::get('/dashboard/pemilik', [DashboardPemilikController::class, 'index'])->name('pemilik.dashboard');
        Route::post('/pemilik/booking/{booking}/approve', [DashboardPemilikController::class, 'approveBooking'])->name('pemilik.booking.approve');
        Route::post('/pemilik/booking/{booking}/reject', [DashboardPemilikController::class, 'rejectBooking'])->name('pemilik.booking.reject');

        // CRUD Barang
        Route::get('/pemilik/barang/create', [DashboardPemilikController::class, 'createBarang'])->name('pemilik.barang.create');
        Route::post('/pemilik/barang', [DashboardPemilikController::class, 'storeBarang'])->name('pemilik.barang.store');
        Route::get('/pemilik/barang/{barang}/edit', [DashboardPemilikController::class, 'editBarang'])->name('pemilik.barang.edit');
        Route::put('/pemilik/barang/{barang}', [DashboardPemilikController::class, 'updateBarang'])->name('pemilik.barang.update');
        Route::delete('/pemilik/barang/{barang}', [DashboardPemilikController::class, 'destroyBarang'])->name('pemilik.barang.destroy');

        // Blokir Kalender Ketersediaan
        Route::post('/pemilik/barang/{barang}/block-date', [DashboardPemilikController::class, 'blockDate'])->name('pemilik.barang.block-date');
        Route::delete('/pemilik/blocked-date/{blockedDate}', [DashboardPemilikController::class, 'deleteBlockedDate'])->name('pemilik.blocked-date.destroy');
    });

    // Dashboard Admin (Role: Admin)
    Route::middleware('role:admin')->group(function () {
        Route::get('/dashboard/admin', [DashboardAdminController::class, 'index'])->name('admin.dashboard');
        Route::post('/admin/kategori', [DashboardAdminController::class, 'storeKategori'])->name('admin.kategori.store');
        Route::post('/admin/lokasi', [DashboardAdminController::class, 'storeLokasi'])->name('admin.lokasi.store');
        Route::delete('/admin/user/{user}', [DashboardAdminController::class, 'deleteUser'])->name('admin.user.destroy');
        Route::patch('/admin/barang/{barang}/toggle', [DashboardAdminController::class, 'toggleBarangStatus'])->name('admin.barang.toggle');
    });
});
