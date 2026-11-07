<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('role')->default('penyewa')->after('phone');
            $table->foreignId('lokasi_id')->nullable()->after('role')->constrained('lokasis')->nullOnDelete();
            $table->text('alamat_lengkap')->nullable()->after('lokasi_id');
            $table->string('avatar')->nullable()->after('alamat_lengkap');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['lokasi_id']);
            $table->dropColumn(['phone', 'role', 'lokasi_id', 'alamat_lengkap', 'avatar']);
        });
    }
};
