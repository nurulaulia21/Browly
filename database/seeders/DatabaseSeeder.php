<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /** Seed data awal yang aman dijalankan berulang kali. */
    public function run(): void
    {
        $this->call(BorrowlyDemoSeeder::class);
    }
}
