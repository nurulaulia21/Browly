<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
        'lokasi_id',
        'alamat_lengkap',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(Lokasi::class);
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }

    public function bookingsAsPenyewa(): HasMany
    {
        return $this->hasMany(Booking::class, 'penyewa_id');
    }

    public function bookingsAsPemilik(): HasMany
    {
        return $this->hasMany(Booking::class, 'pemilik_id');
    }

    public function reviewsGiven(): HasMany
    {
        return $this->hasMany(Review::class, 'penyewa_id');
    }

    public function reviewsReceived(): HasMany
    {
        return $this->hasMany(Review::class, 'pemilik_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Hanya ada 2 jenis akun: admin dan pengguna.
     * Pengguna dapat berperan sebagai penyewa ATAU pemilik (dipilih setelah login).
     * Role lama ('penyewa'/'pemilik') tetap dianggap pengguna.
     */
    public function isPengguna(): bool
    {
        return ! $this->isAdmin();
    }
}
