<?php

namespace App\Services\Payments;

use App\Models\Booking;
use App\Models\Pembayaran;

interface PaymentMethodInterface
{
    public function getMethodName(): string;

    public function processPayment(Booking $booking, array $payload = []): Pembayaran;
}

