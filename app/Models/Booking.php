<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'barang_id',
        'penyewa_id',
        'pemilik_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'jumlah_hari',
        'biaya_sewa',
        'metode_pengambilan',
        'alamat_pengantaran',
        'jarak_km',
        'ongkir',
        'total_biaya',
        'metode_pembayaran',
        'status',
        'alasan_penolakan',
        'alasan_pembatalan',
        'expires_at_owner',
        'expires_at_payment',
        'catatan_penyewa',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'jumlah_hari' => 'integer',
        'biaya_sewa' => 'integer',
        'jarak_km' => 'float',
        'ongkir' => 'integer',
        'total_biaya' => 'integer',
        'expires_at_owner' => 'datetime',
        'expires_at_payment' => 'datetime',
    ];

    public function barang(): BelongsTo
    {
        return $this->belongsTo(Barang::class);
    }

    public function penyewa(): BelongsTo
    {
        return $this->belongsTo(User::class, 'penyewa_id');
    }

    public function pemilik(): BelongsTo
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    public function pembayaran(): HasOne
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function chatRoom(): HasOne
    {
        return $this->hasOne(ChatRoom::class);
    }

    public function pengembalian(): HasOne
    {
        return $this->hasOne(Pengembalian::class);
    }

    public function dendas(): HasMany
    {
        return $this->hasMany(Denda::class);
    }

    public function review(): HasOne
    {
        return $this->hasOne(Review::class);
    }

    public function isExpiredOwner(): bool
    {
        return $this->status === 'MENUNGGU_PERSETUJUAN'
            && $this->expires_at_owner
            && Carbon::now()->greaterThan($this->expires_at_owner);
    }

    public function isExpiredPayment(): bool
    {
        return $this->status === 'MENUNGGU_PEMBAYARAN'
            && $this->expires_at_payment
            && Carbon::now()->greaterThan($this->expires_at_payment);
    }

    public function isChatActive(): bool
    {
        return in_array($this->status, ['DIBAYAR', 'AKTIF', 'DIKEMBALIKAN', 'SELESAI']);
    }

    public function canBeManagedByOwner(int $userId): bool
    {
        return $this->pemilik_id === $userId && $this->status === 'MENUNGGU_PERSETUJUAN';
    }

    public function canBePaidByPenyewa(int $userId): bool
    {
        return $this->penyewa_id === $userId && $this->status === 'MENUNGGU_PEMBAYARAN';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'MENUNGGU_PERSETUJUAN' => 'Menunggu Persetujuan Pemilik',
            'DISETUJUI', 'MENUNGGU_PEMBAYARAN' => 'Menunggu Pembayaran',
            'DITOLAK' => 'Ditolak Pemilik',
            'DIBAYAR' => 'Pembayaran Berhasil',
            'AKTIF' => 'Sedang Disewa (Aktif)',
            'DIKEMBALIKAN' => 'Barang Dikembalikan',
            'SELESAI' => 'Selesai',
            'DIBATALKAN' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getDurasiHariAttribute(): int
    {
        return $this->jumlah_hari ?? 1;
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'MENUNGGU_PERSETUJUAN' => 'yellow',
            'DISETUJUI', 'MENUNGGU_PEMBAYARAN' => 'blue',
            'DITOLAK', 'DIBATALKAN' => 'red',
            'DIBAYAR', 'AKTIF' => 'emerald',
            'DIKEMBALIKAN' => 'purple',
            'SELESAI' => 'green',
            default => 'gray',
        };
    }
}

