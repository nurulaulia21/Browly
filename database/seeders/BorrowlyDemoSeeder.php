<?php

namespace Database\Seeders;

use App\Models\Barang;
use App\Models\BarangBlockedDate;
use App\Models\Booking;
use App\Models\ChatMessage;
use App\Models\ChatRoom;
use App\Models\Denda;
use App\Models\Kategori;
use App\Models\Lokasi;
use App\Models\Pembayaran;
use App\Models\Pengembalian;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BorrowlyDemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Master Lokasi Wilayah
        $lokasiData = [
            ['kota' => 'Makassar', 'kecamatan' => 'Panakkukang'],
            ['kota' => 'Makassar', 'kecamatan' => 'Rappocini'],
            ['kota' => 'Makassar', 'kecamatan' => 'Tamalanrea'],
            ['kota' => 'Makassar', 'kecamatan' => 'Mariso'],
            ['kota' => 'Makassar', 'kecamatan' => 'Ujung Pandang'],
            ['kota' => 'Gowa', 'kecamatan' => 'Somba Opu'],
        ];

        foreach ($lokasiData as $data) {
            Lokasi::firstOrCreate($data);
        }

        // 2. Master Kategori
        $kategoriData = [
            ['nama' => 'Fotografi & Kamera', 'slug' => 'fotografi-kamera', 'icon' => 'camera', 'deskripsi' => 'Kamera DSLR, mirrorless, lensa, tripod, dan perlengkapan foto/video.'],
            ['nama' => 'Camping & Outdoor', 'slug' => 'camping-outdoor', 'icon' => 'tent', 'deskripsi' => 'Tenda dome, carrier, matras, kompor portable, dan perlengkapan outdoor.'],
            ['nama' => 'Elektronik & Presentasi', 'slug' => 'elektronik-presentasi', 'icon' => 'projector', 'deskripsi' => 'Proyektor LCD, layar, mic wireless, dan perlengkapan presentasi.'],
            ['nama' => 'Pesta & Acara', 'slug' => 'pesta-acara', 'icon' => 'party-popper', 'deskripsi' => 'Speaker portable, sound system, lampu disko, dan dekorasi acara.'],
            ['nama' => 'Peralatan & Perkakas', 'slug' => 'peralatan-perkakas', 'icon' => 'wrench', 'deskripsi' => 'Bor listrik, tangga lipat, dan perkakas rumah tangga.'],
        ];

        foreach ($kategoriData as $data) {
            Kategori::firstOrCreate(['slug' => $data['slug']], $data);
        }

        $panakkukang = Lokasi::where('kota', 'Makassar')->where('kecamatan', 'Panakkukang')->firstOrFail();
        $rappocini = Lokasi::where('kota', 'Makassar')->where('kecamatan', 'Rappocini')->firstOrFail();
        $tamalanrea = Lokasi::where('kota', 'Makassar')->where('kecamatan', 'Tamalanrea')->firstOrFail();
        $mariso = Lokasi::where('kota', 'Makassar')->where('kecamatan', 'Mariso')->firstOrFail();

        // 3. Pengguna Demo (Password default: 'password')
        $hashedPassword = Hash::make('password');

        $users = [
            ['name' => 'Admin Borrowly', 'email' => 'admin@borrowly.test', 'phone' => '08111223344', 'role' => 'admin', 'lokasi_id' => $panakkukang->id, 'alamat_lengkap' => 'Jl. Boulevard No. 12, Panakkukang, Makassar'],
            ['name' => 'Budi Hartono', 'email' => 'pemilik@borrowly.test', 'phone' => '081234567890', 'role' => 'pengguna', 'lokasi_id' => $panakkukang->id, 'alamat_lengkap' => 'Jl. Pengayoman Blok F No. 8, Panakkukang, Makassar'],
            ['name' => 'Siti Rahma', 'email' => 'penyewa@borrowly.test', 'phone' => '082198765432', 'role' => 'pengguna', 'lokasi_id' => $rappocini->id, 'alamat_lengkap' => 'Jl. AP Pettarani No. 45, Rappocini, Makassar'],
            ['name' => 'Andi Pratama', 'email' => 'andi@borrowly.test', 'phone' => '085244556677', 'role' => 'pengguna', 'lokasi_id' => $tamalanrea->id, 'alamat_lengkap' => 'Jl. Perintis Kemerdekaan KM 10, Tamalanrea, Makassar'],
            ['name' => 'Dewi Lestari', 'email' => 'dewi@borrowly.test', 'phone' => '087788990011', 'role' => 'pengguna', 'lokasi_id' => $mariso->id, 'alamat_lengkap' => 'Jl. Cendrawasih No. 20, Mariso, Makassar'],
        ];

        foreach ($users as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [...$data, 'password' => $hashedPassword]
            );
        }

        $pemilik = User::where('email', 'pemilik@borrowly.test')->firstOrFail();
        $pemilikDua = User::where('email', 'andi@borrowly.test')->firstOrFail();
        $penyewa = User::where('email', 'penyewa@borrowly.test')->firstOrFail();
        $penyewaDua = User::where('email', 'dewi@borrowly.test')->firstOrFail();

        // 4. Katalog Barang Sewa
        $barangData = [
            ['owner' => $pemilik, 'kategori' => 'fotografi-kamera', 'lokasi' => $panakkukang, 'nama_barang' => 'Kamera DSLR Canon EOS 200D', 'slug' => 'canon-eos-200d-kit-18-55mm', 'deskripsi' => 'Kamera DSLR ringkas sangat cocok untuk vlogging, dokumentasi wisuda, liburan, dan acara keluarga. Termasuk tas dan baterai cadangan.', 'kondisi' => 'Sangat Baik (Mulus Seperti Baru)', 'harga_sewa_per_hari' => 75000, 'foto' => 'https://images.unsplash.com/photo-1516035069371-29a1b244cc32?auto=format&fit=crop&w=800&q=80', 'metode_pembayaran' => 'both', 'metode_pengambilan' => 'both', 'tarif_pengantaran_per_km' => 5000],
            ['owner' => $pemilik, 'kategori' => 'camping-outdoor', 'lokasi' => $panakkukang, 'nama_barang' => 'Tenda Dome Kapasitas 4 Orang Waterproof', 'slug' => 'tenda-dome-kapasitas-4-orang-waterproof', 'deskripsi' => 'Tenda camping kapasitas 4 orang dengan bahan double layer tahan hujan lebat dan angin.', 'kondisi' => 'Baik & Terawat', 'harga_sewa_per_hari' => 45000, 'foto' => 'https://images.unsplash.com/photo-1510312305653-8ed496efae75?auto=format&fit=crop&w=800&q=80', 'metode_pembayaran' => 'both', 'metode_pengambilan' => 'both', 'tarif_pengantaran_per_km' => 6000],
            ['owner' => $pemilik, 'kategori' => 'elektronik-presentasi', 'lokasi' => $panakkukang, 'nama_barang' => 'Proyektor Epson EB-X400 3300 Lumens', 'slug' => 'proyektor-epson-eb-x400-3300-lumens', 'deskripsi' => 'Proyektor profesional 3300 ANSI lumens, sangat terang untuk seminar dan presentasi skripsi.', 'kondisi' => 'Sangat Baik (Terang & Jernih)', 'harga_sewa_per_hari' => 120000, 'foto' => 'https://images.unsplash.com/photo-1517604931442-7e0c8ed2963c?auto=format&fit=crop&w=800&q=80', 'metode_pembayaran' => 'qris', 'metode_pengambilan' => 'both', 'tarif_pengantaran_per_km' => 5000],
            ['owner' => $pemilikDua, 'kategori' => 'fotografi-kamera', 'lokasi' => $tamalanrea, 'nama_barang' => 'Sony Alpha A7 Mark III Body Only', 'slug' => 'sony-alpha-a7-mark-iii-body-only', 'deskripsi' => 'Kamera mirrorless full-frame profesional dengan autofokus super cepat dan dynamic range luar biasa.', 'kondisi' => 'Sangat Baik (Sensor Bersih)', 'harga_sewa_per_hari' => 250000, 'foto' => 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=800&q=80', 'metode_pembayaran' => 'both', 'metode_pengambilan' => 'ambil_sendiri', 'tarif_pengantaran_per_km' => 0],
            ['owner' => $pemilikDua, 'kategori' => 'pesta-acara', 'lokasi' => $tamalanrea, 'nama_barang' => 'Speaker Portable Wireless JBL PartyBox', 'slug' => 'speaker-portable-wireless-jbl-partybox', 'deskripsi' => 'Speaker portable bertenaga dengan efek lampu LED dan 2 microphone wireless UHF.', 'kondisi' => 'Baik & Suara Menggelegar', 'harga_sewa_per_hari' => 150000, 'foto' => 'https://images.unsplash.com/photo-1545454675-3531b543be5d?auto=format&fit=crop&w=800&q=80', 'metode_pembayaran' => 'both', 'metode_pengambilan' => 'diantar', 'tarif_pengantaran_per_km' => 7000],
        ];

        foreach ($barangData as $data) {
            $ownerId = $data['owner']->id;
            $kategori = Kategori::where('slug', $data['kategori'])->firstOrFail();
            $lokasiId = $data['lokasi']->id;
            unset($data['kategori'], $data['lokasi'], $data['owner']);

            Barang::updateOrCreate(
                ['slug' => $data['slug']],
                [...$data, 'user_id' => $ownerId, 'kategori_id' => $kategori->id, 'lokasi_id' => $lokasiId, 'status' => 'tersedia']
            );
        }

        $barang1 = Barang::where('slug', 'canon-eos-200d-kit-18-55mm')->firstOrFail();
        $barang2 = Barang::where('slug', 'tenda-dome-kapasitas-4-orang-waterproof')->firstOrFail();
        $barang3 = Barang::where('slug', 'proyektor-epson-eb-x400-3300-lumens')->firstOrFail();

        // 5. Blokir Tanggal Pribadi (Bab 11 PRD)
        BarangBlockedDate::firstOrCreate([
            'barang_id' => $barang1->id,
            'start_date' => Carbon::now()->addDays(20)->toDateString(),
            'end_date' => Carbon::now()->addDays(22)->toDateString(),
        ], [
            'alasan' => 'Digunakan pemilik untuk acara keluarga',
        ]);

        // 6. Transaksi 1: MENUNGGU_PERSETUJUAN (Countdown 24 Jam Bab 14 PRD)
        Booking::firstOrCreate([
            'booking_code' => 'BRW-DEMO-01',
        ], [
            'penyewa_id' => $penyewa->id,
            'pemilik_id' => $pemilik->id,
            'barang_id' => $barang1->id,
            'tanggal_mulai' => Carbon::now()->addDays(2)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(4)->toDateString(),
            'jumlah_hari' => 2,
            'biaya_sewa' => 150000,
            'metode_pengambilan' => 'ambil_sendiri',
            'jarak_km' => 0,
            'ongkir' => 0,
            'total_biaya' => 150000,
            'metode_pembayaran' => 'qris',
            'catatan_penyewa' => 'Kamera akan digunakan untuk foto kelulusan teman.',
            'status' => 'MENUNGGU_PERSETUJUAN',
            'expires_at_owner' => Carbon::now()->addHours(23),
        ]);

        // 7. Transaksi 2: MENUNGGU_PEMBAYARAN (Batas 24 Jam Pembayaran Bab 16 PRD)
        Booking::firstOrCreate([
            'booking_code' => 'BRW-DEMO-02',
        ], [
            'penyewa_id' => $penyewaDua->id,
            'pemilik_id' => $pemilik->id,
            'barang_id' => $barang2->id,
            'tanggal_mulai' => Carbon::now()->addDays(5)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(7)->toDateString(),
            'jumlah_hari' => 2,
            'biaya_sewa' => 90000,
            'metode_pengambilan' => 'diantar',
            'jarak_km' => 5,
            'ongkir' => 30000,
            'total_biaya' => 120000,
            'metode_pembayaran' => 'cash',
            'catatan_penyewa' => 'Mohon diantar sebelum jam 2 siang.',
            'status' => 'MENUNGGU_PEMBAYARAN',
            'expires_at_payment' => Carbon::now()->addHours(20),
        ]);

        // 8. Transaksi 3: AKTIF (Chat Aktif Sesuai Bab 18 PRD)
        $bookingAktif = Booking::firstOrCreate([
            'booking_code' => 'BRW-AKTIF01',
        ], [
            'penyewa_id' => $penyewa->id,
            'pemilik_id' => $pemilik->id,
            'barang_id' => $barang3->id,
            'tanggal_mulai' => Carbon::now()->subDays(1)->toDateString(),
            'tanggal_selesai' => Carbon::now()->addDays(1)->toDateString(),
            'jumlah_hari' => 2,
            'biaya_sewa' => 240000,
            'metode_pengambilan' => 'ambil_sendiri',
            'jarak_km' => 0,
            'ongkir' => 0,
            'total_biaya' => 240000,
            'metode_pembayaran' => 'qris',
            'catatan_penyewa' => 'Untuk seminar kampus di ruang multimedia.',
            'status' => 'AKTIF',
        ]);

        Pembayaran::firstOrCreate([
            'booking_id' => $bookingAktif->id,
        ], [
            'nomor_transaksi' => 'QRIS-' . strtoupper(Str::random(10)),
            'metode' => 'qris',
            'nominal' => 240000,
            'status' => 'berhasil',
            'waktu_bayar' => Carbon::now()->subDays(1),
            'bukti_bayar' => 'simulasi_qris_paid.png',
            'keterangan' => 'Pembayaran simulasi QRIS berhasil diverifikasi sistem.',
        ]);

        $chatRoom = ChatRoom::firstOrCreate([
            'booking_id' => $bookingAktif->id,
        ], [
            'penyewa_id' => $penyewa->id,
            'pemilik_id' => $pemilik->id,
            'is_active' => true,
        ]);

        ChatMessage::firstOrCreate([
            'chat_room_id' => $chatRoom->id,
            'sender_id' => $penyewa->id,
            'pesan' => 'Halo Pak Budi, pembayaran sewa proyektor sudah saya lunasi via QRIS. Apakah proyektornya bisa saya ambil sore ini?',
        ], [
            'created_at' => Carbon::now()->subHours(5),
        ]);

        ChatMessage::firstOrCreate([
            'chat_room_id' => $chatRoom->id,
            'sender_id' => $pemilik->id,
            'pesan' => 'Halo Mbak Siti, baik sudah terkonfirmasi. Barang sudah siap di tempat saya di Panakkukang, silakan datang jam 4 sore ya.',
        ], [
            'created_at' => Carbon::now()->subHours(4),
        ]);

        // 9. Transaksi 4: SELESAI (Pengembalian, Denda, dan Review Sesuai Bab 19 & 20 PRD)
        $bookingSelesai = Booking::firstOrCreate([
            'booking_code' => 'BRW-SELESAI01',
        ], [
            'penyewa_id' => $penyewa->id,
            'pemilik_id' => $pemilik->id,
            'barang_id' => $barang1->id,
            'tanggal_mulai' => Carbon::now()->subDays(7)->toDateString(),
            'tanggal_selesai' => Carbon::now()->subDays(5)->toDateString(),
            'jumlah_hari' => 2,
            'biaya_sewa' => 150000,
            'metode_pengambilan' => 'ambil_sendiri',
            'jarak_km' => 0,
            'ongkir' => 0,
            'total_biaya' => 150000,
            'metode_pembayaran' => 'cash',
            'status' => 'SELESAI',
        ]);

        Pembayaran::firstOrCreate([
            'booking_id' => $bookingSelesai->id,
        ], [
            'nomor_transaksi' => 'CASH-' . strtoupper(Str::random(10)),
            'metode' => 'cash',
            'nominal' => 150000,
            'status' => 'berhasil',
            'waktu_bayar' => Carbon::now()->subDays(7),
            'bukti_bayar' => 'simulasi_tunai_diterima.png',
            'keterangan' => 'Pembayaran tunai telah diserahkan langsung saat serah terima barang.',
        ]);

        $pengembalian = Pengembalian::firstOrCreate([
            'booking_id' => $bookingSelesai->id,
        ], [
            'tanggal_pengembalian_aktual' => Carbon::now()->subDays(4),
            'kondisi_barang' => 'rusak_ringan',
            'hari_keterlambatan' => 1,
            'catatan' => 'Kembali terlambat 1 hari karena macet luar kota, lens cap ada sedikit goresan.',
            'status' => 'ada_denda',
        ]);

        Denda::firstOrCreate([
            'booking_id' => $bookingSelesai->id,
            'jenis_denda' => 'keterlambatan',
        ], [
            'pengembalian_id' => $pengembalian->id,
            'nominal' => 75000,
            'deskripsi' => 'Denda keterlambatan pengembalian 1 hari (1x harga sewa harian)',
            'status_pembayaran' => 'lunas',
        ]);

        Review::firstOrCreate([
            'booking_id' => $bookingSelesai->id,
        ], [
            'barang_id' => $barang1->id,
            'penyewa_id' => $penyewa->id,
            'pemilik_id' => $pemilik->id,
            'rating' => 5,
            'komentar' => 'Barang sangat bagus, lensa jernih, dan pemilik sangat ramah serta kooperatif dalam komunikasi!',
        ]);
    }
}
