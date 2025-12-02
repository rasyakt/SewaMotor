<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Penyewaan extends Model
{
    use HasFactory;

    protected $fillable = [
        'penyewa_id',
        'motor_id',
        'tanggal_mulai',
        'tanggal_selesai',
        'tipe_durasi',
        'harga_total',
        'status'
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relasi ke penyewa
    public function penyewa()
    {
        return $this->belongsTo(User::class, 'penyewa_id');
    }

    // Relasi ke motor
    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    // Relasi ke transaksi
    public function transaksi()
    {
        return $this->hasOne(Transaksi::class, 'penyewaan_id');
    }

    // Relasi ke bagi hasil
    public function bagiHasil()
    {
        return $this->hasOne(BagiHasil::class, 'penyewaan_id');
    }
    // Method untuk menghitung durasi
    public function hitungDurasi()
    {
        if (!$this->tanggal_mulai || !$this->tanggal_selesai) {
            return 0;
        }
    
        $start = Carbon::parse($this->tanggal_mulai);
        $end = Carbon::parse($this->tanggal_selesai);
    
        return $start->diffInDays($end);
    }
}