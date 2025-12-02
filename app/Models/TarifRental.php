<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TarifRental extends Model
{
    use HasFactory;

    protected $fillable = [
        'motor_id',
        'tarif_harian',
        'tarif_mingguan',
        'tarif_bulanan'
    ];

    // Relasi ke motor
    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    // Method untuk mendapatkan tarif berdasarkan tipe
    public function getTarif($tipe)
    {
        return match($tipe) {
            'harian' => $this->tarif_harian,
            'mingguan' => $this->tarif_mingguan,
            'bulanan' => $this->tarif_bulanan,
            default => 0
        };
    }
}