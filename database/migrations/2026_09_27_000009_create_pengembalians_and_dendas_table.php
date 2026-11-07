<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pengembalians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->dateTime('tanggal_pengembalian_aktual');
            $table->string('kondisi_barang')->default('sesuai_awal');
            $table->text('catatan')->nullable();
            $table->unsignedInteger('hari_keterlambatan')->default(0);
            $table->string('foto_bukti')->nullable();
            $table->string('status')->default('diajukan');
            $table->timestamps();
        });

        Schema::create('dendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->cascadeOnDelete();
            $table->foreignId('pengembalian_id')->nullable()->constrained('pengembalians')->nullOnDelete();
            $table->string('jenis_denda');
            $table->unsignedInteger('nominal');
            $table->text('deskripsi');
            $table->string('status_pembayaran')->default('belum_dibayar');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dendas');
        Schema::dropIfExists('pengembalians');
    }
};
