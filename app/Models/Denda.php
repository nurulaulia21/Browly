<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Denda extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'pengembalian_id',
        'jenis_denda',
        'nominal',
        'deskripsi',
        'status_pembayaran',
    ];

    protected $casts = [
        'nominal' => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function pengembalian(): BelongsTo
    {
        return $this->belongsTo(Pengembalian::class);
    }

    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis_denda) {
            'keterlambatan' => 'Denda Keterlambatan',
            'kerusakan' => 'Denda Kerusakan Barang',
            default => 'Denda Lainnya',
        };
    }
}

