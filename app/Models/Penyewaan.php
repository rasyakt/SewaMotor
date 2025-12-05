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
        'tanggal_kembali_aktual' => 'date',
        'notifikasi_dikirim_at' => 'datetime',
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

    /**
     * Method untuk handle return motor
     */
    public function handleReturn($tanggalKembali = null, $statusRusak = false)
    {
        if (!$tanggalKembali) {
            $tanggalKembali = Carbon::now()->toDateString();
        }

        $tanggalSelesai = Carbon::parse($this->tanggal_selesai);
        $tanggalKembaliActual = Carbon::parse($tanggalKembali);

        // Update tanggal kembali aktual
        $this->tanggal_kembali_aktual = $tanggalKembali;

        // Tentukan status pengembalian
        if ($statusRusak) {
            $this->status_pengembalian = 'rusak';
        } elseif ($tanggalKembaliActual->isAfter($tanggalSelesai)) {
            $this->status_pengembalian = 'terlambat';
            $this->hari_terlambat = $tanggalKembaliActual->diffInDays($tanggalSelesai);
            
            // Hitung denda keterlambatan (default: 10% dari tarif harian per hari terlambat)
            $hariSewa = $this->hitungDurasi();
            if ($hariSewa > 0) {
                $tarifHarian = $this->harga_total / $hariSewa;
                $this->denda_keterlambatan = $tarifHarian * 0.1 * $this->hari_terlambat;
            }
        } else {
            $this->status_pengembalian = 'tepat_waktu';
        }

        // Update status penyewaan
        $this->status = 'selesai';

        // Update status motor kembali tersedia
        $this->motor->update(['status' => 'tersedia']);

        $this->save();

        return $this;
    }

    /**
     * Check apakah penyewaan sudah melewati tanggal selesai
     */
    public function isOverdue()
    {
        return Carbon::now()->isAfter($this->tanggal_selesai) && 
               $this->status != 'selesai' && 
               $this->status != 'dibatalkan';
    }

    /**
     * Get status terlambat dalam bahasa Indonesia
     */
    public function getStatusPengembalianLabel()
    {
        $labels = [
            'pending' => 'Belum Dikembalikan',
            'tepat_waktu' => 'Tepat Waktu',
            'terlambat' => 'Terlambat',
            'rusak' => 'Rusak',
        ];

        return $labels[$this->status_pengembalian] ?? 'Tidak Diketahui';
    }
}