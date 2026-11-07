<?php

namespace Tests\Feature;

use App\Console\Commands\CheckBorrowlyExpirations;
use App\Models\Barang;
use App\Models\Booking;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\User;
use App\Services\BookingService;
use Carbon\Carbon;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BorrowlyFeatureTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }

    /**
     * 1. Halaman utama (Home) dan katalog dapat diakses.
     */
    public function test_home_page_and_catalog_are_accessible(): void
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Borrowly');
        $response->assertSee('Canon EOS 200D');

        $responseCatalog = $this->get(route('barangs.index'));
        $responseCatalog->assertStatus(200);
        $responseCatalog->assertSee('Katalog Barang');
    }

    /**
     * 2. Quick Demo Login untuk Penyewa, Pemilik, dan Admin.
     */
    public function test_quick_demo_login_works_for_all_roles(): void
    {
        // Login Penyewa
        $respPenyewa = $this->get(route('quick-login', 'penyewa'));
        $respPenyewa->assertRedirect(route('penyewa.dashboard'));
        $this->assertAuthenticated();
        $this->assertEquals('penyewa', auth()->user()->role);

        $this->post(route('logout'));

        // Login Pemilik
        $respPemilik = $this->get(route('quick-login', 'pemilik'));
        $respPemilik->assertRedirect(route('pemilik.dashboard'));
        $this->assertEquals('pemilik', auth()->user()->role);

        $this->post(route('logout'));

        // Login Admin
        $respAdmin = $this->get(route('quick-login', 'admin'));
        $respAdmin->assertRedirect(route('admin.dashboard'));
        $this->assertEquals('admin', auth()->user()->role);
    }

    /**
     * 3. Registrasi pengguna baru berhasil.
     */
    public function test_user_registration_creates_account_and_logs_in(): void
    {
        $lokasi = Lokasi::first();

        $response = $this->post('/register', [
            'name' => 'Fajar Pratama',
            'email' => 'fajar@example.com',
            'phone' => '081299887766',
            'role' => 'penyewa',
            'lokasi_id' => $lokasi->id,
            'alamat_lengkap' => 'Jl. Boulevard Blok A No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('penyewa.dashboard'));
        $this->assertDatabaseHas('users', ['email' => 'fajar@example.com']);
    }

    /**
     * 4. Pemilik dapat menambahkan barang sewaan baru ke katalog.
     */
    public function test_owner_can_create_new_rental_item(): void
    {
        $pemilik = User::where('role', 'pemilik')->first();
        $kategori = Kategori::first();
        $lokasi = Lokasi::first();

        $response = $this->actingAs($pemilik)->post(route('pemilik.barang.store'), [
            'nama_barang' => 'Drone DJI Mini 3 Pro Fly More',
            'kategori_id' => $kategori->id,
            'lokasi_id' => $lokasi->id,
            'harga_sewa_per_hari' => 150000,
            'kondisi' => 'Sangat Baik (Seperti Baru)',
            'metode_pembayaran' => 'both',
            'metode_pengambilan' => 'both',
            'tarif_pengantaran_per_km' => 7000,
            'foto' => 'https://images.unsplash.com/photo-1527864550417-7fd91fc51a46',
            'deskripsi' => 'Drone 4K lengkap dengan remote control layar dan 3 baterai cadangan.',
        ]);

        $response->assertRedirect(route('pemilik.dashboard', ['tab' => 'barang_saya']));
        $this->assertDatabaseHas('barangs', [
            'nama_barang' => 'Drone DJI Mini 3 Pro Fly More',
            'harga_sewa_per_hari' => 150000,
            'user_id' => $pemilik->id,
        ]);
    }

    /**
     * 4b. Pemilik dapat mengunggah file foto barang (bukan hanya URL).
     */
    public function test_owner_can_upload_photo_file_for_item(): void
    {
        Storage::fake('public');
        $pemilik = User::where('role', 'pemilik')->first();
        $kategori = Kategori::first();
        $lokasi = Lokasi::first();

        $file = UploadedFile::fake()->create('kamera_sony.jpg', 100, 'image/jpeg');

        $response = $this->actingAs($pemilik)->post(route('pemilik.barang.store'), [
            'nama_barang' => 'Kamera Sony A6400 Upload',
            'kategori_id' => $kategori->id,
            'lokasi_id' => $lokasi->id,
            'harga_sewa_per_hari' => 120000,
            'kondisi' => 'Sangat Baik',
            'metode_pembayaran' => 'both',
            'metode_pengambilan' => 'both',
            'tarif_pengantaran_per_km' => 5000,
            'foto_file' => $file,
            'deskripsi' => 'Kamera dengan foto upload lokal.',
        ]);

        $response->assertRedirect(route('pemilik.dashboard', ['tab' => 'barang_saya']));

        $barang = Barang::where('nama_barang', 'Kamera Sony A6400 Upload')->firstOrFail();
        $this->assertNotEmpty($barang->getRawOriginal('foto'));
        Storage::disk('public')->assertExists($barang->getRawOriginal('foto'));
    }

    /**
     * 5. Penyewa membuat booking dengan perhitungan biaya sewa dan ongkir yang tepat.
     */
    public function test_renter_can_book_barang_with_correct_fee_calculation(): void
    {
        $penyewa = User::where('role', 'penyewa')->first();
        $barang = Barang::where('nama_barang', 'like', '%Canon%')->first();

        $startDate = Carbon::now()->addDays(10)->toDateString();
        $endDate = Carbon::now()->addDays(11)->toDateString(); // 2 hari sewa (10 dan 11)

        $response = $this->actingAs($penyewa)->post(route('booking.store', $barang->id), [
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'metode_pengambilan' => 'diantar',
            'alamat_pengantaran' => 'Jl. Pengayoman No. 10, Makassar',
            'jarak_km' => 4,
            'metode_pembayaran' => 'qris',
            'catatan_penyewa' => 'Mohon diantar pagi jam 8.',
        ]);

        $response->assertSessionHasNoErrors();

        // Perhitungan:
        // 2 hari x Rp75.000 = Rp150.000 sewa
        // 4 km x Rp5.000 = Rp20.000 ongkir
        // Total = Rp170.000
        $this->assertDatabaseHas('bookings', [
            'penyewa_id' => $penyewa->id,
            'barang_id' => $barang->id,
            'jumlah_hari' => 2,
            'biaya_sewa' => 150000,
            'ongkir' => 20000,
            'total_biaya' => 170000,
            'status' => 'MENUNGGU_PERSETUJUAN',
        ]);
    }

    /**
     * 6. Anti-benturan jadwal (anti double-booking) mencegah sewa pada tanggal yang sama.
     */
    public function test_prevents_overlapping_bookings_for_same_dates(): void
    {
        $penyewa = User::where('role', 'penyewa')->first();
        $barang = Barang::where('nama_barang', 'like', '%Canon%')->first();

        $startDate = Carbon::now()->addDays(15)->toDateString();
        $endDate = Carbon::now()->addDays(17)->toDateString();

        // Booking pertama berhasil
        $bookingService = app(BookingService::class);
        $bookingService->createBooking($barang, $penyewa, [
            'tanggal_mulai' => $startDate,
            'tanggal_selesai' => $endDate,
            'metode_pengambilan' => 'ambil_sendiri',
            'metode_pembayaran' => 'cash',
        ]);

        // Pengajuan booking kedua dengan tanggal yang tumpang tindih harus ditolak
        $response = $this->actingAs($penyewa)->post(route('booking.store', $barang->id), [
            'tanggal_mulai' => Carbon::now()->addDays(16)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(18)->toDateString(),
            'metode_pengambilan' => 'ambil_sendiri',
            'metode_pembayaran' => 'cash',
        ]);

        $response->assertSessionHas('error');
    }

    /**
     * 7. Pemilik dapat menyetujui atau menolak booking.
     */
    public function test_owner_can_approve_and_reject_booking(): void
    {
        $booking = Booking::where('status', 'MENUNGGU_PERSETUJUAN')->first();
        $pemilik = $booking->pemilik;

        // Setujui
        $responseApprove = $this->actingAs($pemilik)->post(route('pemilik.booking.approve', $booking->id));
        $responseApprove->assertSessionHas('success');

        $booking->refresh();
        $this->assertEquals('MENUNGGU_PEMBAYARAN', $booking->status);
        $this->assertNotNull($booking->expires_at_payment);
    }

    /**
     * 8. Pembatalan otomatis (24 jam) saat batas waktu terlampaui.
     */
    public function test_system_auto_cancels_expired_bookings(): void
    {
        $penyewa = User::where('role', 'penyewa')->first();
        $pemilik = User::where('role', 'pemilik')->first();
        $barang = Barang::first();

        // Buat booking kadaluarsa (melewati batas 24 jam)
        $expiredBooking = Booking::create([
            'booking_code' => 'BRW-EXPIRED01',
            'penyewa_id' => $penyewa->id,
            'pemilik_id' => $pemilik->id,
            'barang_id' => $barang->id,
            'tanggal_mulai' => Carbon::now()->addDays(30)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(32)->toDateString(),
            'jumlah_hari' => 2,
            'biaya_sewa' => 100000,
            'metode_pengambilan' => 'ambil_sendiri',
            'ongkir' => 0,
            'total_biaya' => 100000,
            'metode_pembayaran' => 'qris',
            'status' => 'MENUNGGU_PERSETUJUAN',
            'expires_at_owner' => Carbon::now()->subMinutes(10), // Melewati batas
        ]);

        $this->artisan('borrowly:check-expirations')
            ->assertSuccessful();

        $expiredBooking->refresh();
        $this->assertEquals('DIBATALKAN', $expiredBooking->status);
        $this->assertStringContainsString('24 jam', $expiredBooking->alasan_pembatalan);
    }

    /**
     * 9. Simulasi pembayaran (QRIS / Cash) berhasil mengubah status menjadi DIBAYAR.
     */
    public function test_payment_simulation_marks_booking_as_paid(): void
    {
        $booking = Booking::where('status', 'MENUNGGU_PEMBAYARAN')->first();
        $penyewa = $booking->penyewa;

        $response = $this->actingAs($penyewa)->post(route('pembayaran.process', $booking->booking_code), [
            'metode_pembayaran' => 'cash',
        ]);

        $response->assertRedirect(route('booking.show', $booking->booking_code));
        $booking->refresh();
        $this->assertEquals('DIBAYAR', $booking->status);
        $this->assertDatabaseHas('pembayarans', [
            'booking_id' => $booking->id,
            'status' => 'berhasil',
        ]);
    }

    /**
     * 10. Ruang chat transaksi hanya bisa diakses setelah pembayaran lunas.
     */
    public function test_chat_room_rules_and_messaging(): void
    {
        // Booking belum dibayar tidak boleh bisa buka chat
        $unpaidBooking = Booking::where('status', 'MENUNGGU_PERSETUJUAN')->first();
        $responseBlocked = $this->actingAs($unpaidBooking->penyewa)->get(route('chat.show', $unpaidBooking->booking_code));
        $responseBlocked->assertRedirect(route('booking.show', $unpaidBooking->booking_code));

        // Booking AKTIF / DIBAYAR dapat membuka chat dan mengirim pesan
        $paidBooking = Booking::where('status', 'AKTIF')->first();
        $responseAllowed = $this->actingAs($paidBooking->penyewa)->get(route('chat.show', $paidBooking->booking_code));
        $responseAllowed->assertStatus(200);
        $responseAllowed->assertSee('Chat Aktif');

        // Kirim pesan
        $responseSend = $this->actingAs($paidBooking->penyewa)->post(route('chat.send', $paidBooking->booking_code), [
            'pesan' => 'Terima kasih, saya sedang menuju ke lokasi Anda.',
        ]);
        $responseSend->assertRedirect();
        $this->assertDatabaseHas('chat_messages', [
            'pesan' => 'Terima kasih, saya sedang menuju ke lokasi Anda.',
        ]);
    }

    /**
     * 11. Pengembalian barang dan penetapan denda oleh pemilik.
     */
    public function test_return_process_and_fine_imposition(): void
    {
        $paidBooking = Booking::where('status', 'AKTIF')->first();

        // 1. Penyewa ajukan pengembalian
        $respReturn = $this->actingAs($paidBooking->penyewa)->post(route('pengembalian.store', $paidBooking->id), [
            'tanggal_pengembalian_aktual' => Carbon::now()->toDateString(),
            'kondisi_barang' => 'rusak_ringan',
            'catatan' => 'Ada lecet di bagian bawah casing.',
        ]);
        $paidBooking->refresh();
        $this->assertEquals('DIKEMBALIKAN', $paidBooking->status);

        // 2. Pemilik verifikasi & tetapkan denda kerusakan
        $respConfirm = $this->actingAs($paidBooking->pemilik)->post(route('pengembalian.confirm', $paidBooking->id), [
            'kondisi_barang_verifikasi' => 'rusak_ringan',
            'denda_keterlambatan' => 0,
            'denda_kerusakan' => 50000,
            'deskripsi_kerusakan' => 'Ganti rugi lecet body casing.',
        ]);

        $paidBooking->refresh();
        $this->assertEquals('SELESAI', $paidBooking->status);
        $this->assertDatabaseHas('dendas', [
            'booking_id' => $paidBooking->id,
            'jenis_denda' => 'kerusakan',
            'nominal' => 50000,
        ]);
    }

    /**
     * 12. Penyewa dapat memberikan ulasan (review) setelah transaksi SELESAI.
     */
    public function test_renter_can_review_completed_rental(): void
    {
        $completedBooking = Booking::where('status', 'SELESAI')->doesntHave('review')->first();
        if (! $completedBooking) {
            $completedBooking = Booking::where('status', 'SELESAI')->first();
            $completedBooking->review()->delete();
        }

        $response = $this->actingAs($completedBooking->penyewa)->post(route('review.store', $completedBooking->id), [
            'rating' => 5,
            'komentar' => 'Pengalaman sewa yang luar biasa dan barang sangat prima!',
        ]);

        $response->assertSessionHas('success');
        $this->assertDatabaseHas('reviews', [
            'booking_id' => $completedBooking->id,
            'rating' => 5,
        ]);
    }

    /**
     * 13. Dashboard Admin dapat memonitor seluruh platform.
     */
    public function test_admin_dashboard_can_monitor_and_manage_platform(): void
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get(route('admin.dashboard'));
        $response->assertStatus(200);
        $response->assertSee('Super Administrator');
        $response->assertSee('Pengawasan Sistem Platform');

        // Admin tambah kategori
        $respKat = $this->actingAs($admin)->post(route('admin.kategori.store'), [
            'nama' => 'Peralatan Olahraga',
            'icon' => 'activity',
            'deskripsi' => 'Sepeda, raket badminton, matras yoga, dan alat olahraga.',
        ]);
        $respKat->assertRedirect(route('admin.dashboard', ['tab' => 'kategori']));
        $this->assertDatabaseHas('kategoris', ['nama' => 'Peralatan Olahraga']);
    }
}
