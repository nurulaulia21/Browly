<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pengembalian extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'tanggal_pengembalian_aktual',
        'kondisi_barang',
        'catatan',
        'hari_keterlambatan',
        'foto_bukti',
        'status',
    ];

    protected $casts = [
        'tanggal_pengembalian_aktual' => 'datetime',
        'hari_keterlambatan' => 'integer',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function dendas(): HasMany
    {
        return $this->hasMany(Denda::class);
    }

    public function calculateLateDays(Carbon|string $expectedEndDate): int
    {
        $expected = Carbon::parse($expectedEndDate)->endOfDay();
        $actual = Carbon::parse($this->tanggal_pengembalian_aktual);

        if ($actual->greaterThan($expected)) {
            return (int) ceil($actual->diffInDays($expected));
        }

        return 0;
    }
}

