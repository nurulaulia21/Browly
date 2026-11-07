<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Sederhanakan role: hanya 'admin' dan 'pengguna'.
     * Akun lama ber-role 'penyewa' / 'pemilik' diubah menjadi 'pengguna'.
     */
    public function up(): void
    {
        DB::table('users')->whereIn('role', ['penyewa', 'pemilik'])->update(['role' => 'pengguna']);
    }

    public function down(): void
    {
        DB::table('users')->where('role', 'pengguna')->update(['role' => 'penyewa']);
    }
};
