<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Motor extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemilik_id',
        'merk',
        'tipe_cc',
        'no_plat',
        'status',
        'photo',
        'dokumen_kepemilikan',
        'deskripsi'
    ];

    // Relasi ke pemilik
    public function pemilik()
    {
        return $this->belongsTo(User::class, 'pemilik_id');
    }

    // Relasi ke tarif rental
    public function tarifRental()
    {
        return $this->hasOne(TarifRental::class);
    }

    // Relasi ke penyewaan
    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class);
    }

    // Scope untuk motor tersedia
    public function scopeTersedia($query)
    {
        return $query->where('status', 'tersedia');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }
}