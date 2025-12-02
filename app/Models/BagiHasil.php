<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BagiHasil extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'bagi_hasil_pemilik',
        'bagi_hasil_admin',
        'settled_at',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date',
        'settled_at' => 'datetime',
    ];

    // Relasi ke penyewaan
    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }
}