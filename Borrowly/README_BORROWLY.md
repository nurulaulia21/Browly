# Borrowly — Petunjuk Menjalankan

Borrowly adalah aplikasi Laravel untuk penyewaan barang lokal. Data demo dibuat oleh seeder sehingga halaman daftar akun memiliki pilihan lokasi dan katalog barang awal tersedia.

## Persyaratan
- PHP 8.3 atau lebih baru dengan ekstensi PDO SQLite, OpenSSL, Mbstring, Tokenizer, XML, Ctype, JSON, dan Fileinfo.
- Composer.
- Node.js dan npm.

## Instalasi pertama kali
Jalankan perintah dari folder proyek `Borrowly`.

1. Pasang dependency PHP:
   ```bash
   composer install
   ```
2. Buat file konfigurasi lokal:
   - Windows PowerShell: `Copy-Item .env.example .env`
   - macOS/Linux: `cp .env.example .env`
   Jika `.env` sudah ada, jangan ditimpa.
3. Buat application key:
   ```bash
   php artisan key:generate
   ```
4. Pastikan file database SQLite ada:
   - Windows PowerShell: `New-Item database/database.sqlite -ItemType File -Force`
   - macOS/Linux: `touch database/database.sqlite`
5. Jalankan migrasi dan data awal:
   ```bash
   php artisan migrate --seed
   ```
6. Pasang dan bangun aset frontend:
   ```bash
   npm install
   npm run build
   ```
7. Jalankan aplikasi:
   ```bash
   php artisan serve
   ```
   Buka alamat yang ditampilkan oleh Laravel, biasanya `http://127.0.0.1:8000`.

> Jika database lama memiliki skema yang rusak dan datanya boleh dihapus, `php artisan migrate:fresh --seed` akan menghapus seluruh tabel lalu membuatnya kembali. Jangan gunakan perintah tersebut jika ada data penting.

## Akun demo
Jalankan `php artisan db:seed` untuk menyiapkan ulang akun demo. Kata sandi akun demo setelah seeding adalah `Borrowly123!`.

| Peran | Email |
|---|---|
| Admin | `admin@borrowly.test` |
| Pemilik | `pemilik@borrowly.test` |
| Penyewa | `penyewa@borrowly.test` |
| Pemilik tambahan | `andi@borrowly.test` |

Akun demo hanya untuk pengembangan/presentasi lokal, bukan untuk dipakai pada server publik.

## Menjalankan aturan batas waktu 24 jam
Agar booking yang kedaluwarsa dibatalkan otomatis, jalankan proses scheduler di terminal kedua:
```bash
php artisan schedule:work
```
Biarkan proses tersebut aktif selama aplikasi diuji. Di server produksi, jadwalkan `php artisan schedule:run` melalui cron/task scheduler.

## Catatan fitur
- Registrasi membutuhkan nama, email unik, nomor HP, peran, lokasi, dan kata sandi yang dikonfirmasi.
- Pembayaran QRIS dan cash adalah simulasi, bukan transaksi nyata.
- Chat aktif setelah pembayaran berhasil.
- Lokasi menggunakan kota/kecamatan; tidak memakai GPS real-time atau layanan kurir.
