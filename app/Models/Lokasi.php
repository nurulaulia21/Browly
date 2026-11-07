<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Lokasi extends Model
{
    use HasFactory;

    protected $fillable = [
        'kota',
        'kecamatan',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function barangs(): HasMany
    {
        return $this->hasMany(Barang::class);
    }

    public function getNamaLengkapAttribute(): string
    {
        return "{$this->kecamatan}, {$this->kota}";
    }
}
