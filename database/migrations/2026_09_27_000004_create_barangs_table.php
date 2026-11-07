<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barangs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kategori_id')->constrained('kategoris')->cascadeOnDelete();
            $table->foreignId('lokasi_id')->constrained('lokasis')->cascadeOnDelete();
            $table->string('nama_barang');
            $table->string('slug')->unique();
            $table->text('deskripsi');
            $table->string('kondisi')->default('Baik');
            $table->unsignedInteger('harga_sewa_per_hari');
            $table->string('foto')->nullable();
            $table->string('metode_pembayaran')->default('both');
            $table->string('metode_pengambilan')->default('both');
            $table->unsignedInteger('tarif_pengantaran_per_km')->default(5000);
            $table->string('status')->default('tersedia');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('barangs');
    }
};
