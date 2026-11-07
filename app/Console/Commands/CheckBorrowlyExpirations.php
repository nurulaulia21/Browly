<?php

namespace App\Console\Commands;

use App\Services\BookingService;
use Illuminate\Console\Command;

class CheckBorrowlyExpirations extends Command
{
    protected $signature = 'borrowly:check-expirations';

    protected $description = 'Memeriksa dan membatalkan booking yang melebihi batas 24 jam untuk respon pemilik dan pembayaran penyewa';

    public function handle(BookingService $bookingService): int
    {
        $this->info('Memeriksa batas waktu booking...');
        $canceled = $bookingService->cancelExpiredBookings();
        $this->info("Selesai. Sebanyak {$canceled} booking kedaluwarsa telah dibatalkan otomatis.");

        return Command::SUCCESS;  
    }
}
