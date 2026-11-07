<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();
            $table->foreignId('barang_id')->constrained('barangs')->cascadeOnDelete();
            $table->foreignId('penyewa_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('pemilik_id')->constrained('users')->cascadeOnDelete();
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->unsignedInteger('jumlah_hari');
            $table->unsignedInteger('biaya_sewa');
            $table->string('metode_pengambilan');
            $table->text('alamat_pengantaran')->nullable();
            $table->decimal('jarak_km', 8, 2)->nullable();
            $table->unsignedInteger('ongkir')->default(0);
            $table->unsignedInteger('total_biaya');
            $table->string('metode_pembayaran');
            $table->string('status')->default('MENUNGGU_PERSETUJUAN');
            $table->text('alasan_penolakan')->nullable();
            $table->text('alasan_pembatalan')->nullable();
            $table->timestamp('expires_at_owner')->nullable();
            $table->timestamp('expires_at_payment')->nullable();
            $table->text('catatan_penyewa')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
