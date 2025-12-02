<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewaan_id',
        'jumlah',
        'metode_pembayaran',
        'status',
        'tanggal',
        'bukti_pembayaran'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    // Relasi ke penyewaan
    public function penyewaan()
    {
        return $this->belongsTo(Penyewaan::class);
    }
}