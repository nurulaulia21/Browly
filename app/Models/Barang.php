<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Barang extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'kategori_id',
        'lokasi_id',
        'nama_barang',
        'slug',
        'deskripsi',
        'kondisi',
        'harga_sewa_per_hari',
        'foto',
        'metode_pembayaran',
        'metode_pengambilan',
        'tarif_pengantaran_per_km',
        'status',
    ];

    protected $casts = [
        'harga_sewa_per_hari' => 'integer',
        'tarif_pengantaran_per_km' => 'integer',
    ];

    public function getFotoAttribute(?string $value): string
    {
        if (empty($value)) {
            return 'https://images.unsplash.com/photo-1526170375885-4d8ecf77b99f?auto=format&fit=crop&w=800&q=80';
        }

        if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
            return $value;
        }

        return asset('storage/' . $value);
    }

    protected static function booted(): void
    {
        static::creating(function ($barang) {
            if (empty($barang->slug)) {
                $barang->slug = Str::slug($barang->nama_barang) . '-' . Str::random(5);
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pemilik(): BelongsTo
    {
        return $this->user();
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(Kategori::class);
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function blockedDates(): HasMany
    {
        return $this->hasMany(BarangBlockedDate::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function isAvailableForRange(string $startDate, string $endDate, ?int $excludeBookingId = null): bool
    {
        if ($this->status !== 'tersedia') {
            return false;
        }

        $hasBlockedDate = $this->blockedDates()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('start_date', '<=', $endDate)
                    ->where('end_date', '>=', $startDate);
            })->exists();

        if ($hasBlockedDate) {
            return false;
        }

        $activeStatuses = [
            'MENUNGGU_PERSETUJUAN',
            'DISETUJUI',
            'MENUNGGU_PEMBAYARAN',
            'DIBAYAR',
            'AKTIF',
        ];

        $hasConflictBooking = $this->bookings()
            ->when($excludeBookingId, fn ($q) => $q->where('id', '!=', $excludeBookingId))
            ->whereIn('status', $activeStatuses)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where('tanggal_mulai', '<=', $endDate)
                    ->where('tanggal_selesai', '>=', $startDate);
            })->exists();

        return ! $hasConflictBooking;
    }

    public function calculateRentalPrice(int $days): int
    {
        return max(1, $days) * $this->harga_sewa_per_hari;
    }

    public function calculateDeliveryFee(float $distanceKm): int
    {
        return (int) round($distanceKm * $this->tarif_pengantaran_per_km);
    }

    public function getAverageRatingAttribute(): float
    {
        return round((float) $this->reviews()->avg('rating'), 1) ?: 5.0;
    }

    public function getReviewsCountAttribute(): int
    {
        return $this->reviews()->count();
    }
}
