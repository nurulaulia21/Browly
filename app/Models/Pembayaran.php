<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'nomor_transaksi',
        'metode',
        'nominal',
        'status',
        'bukti_bayar',
        'waktu_bayar',
        'keterangan',
    ];

    protected $casts = [
        'nominal' => 'integer',
        'waktu_bayar' => 'datetime',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }
}

