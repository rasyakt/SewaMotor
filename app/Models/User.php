<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'nama',
        'email',
        'no_tlpn',
        'role',
        'password',
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

    // Relasi: User sebagai pemilik motor
    public function motors()
    {
        return $this->hasMany(Motor::class, 'pemilik_id');
    }

    // Relasi: User sebagai penyewa
    public function penyewaans()
    {
        return $this->hasMany(Penyewaan::class, 'penyewa_id');
    }

    // Scope untuk role
    public function scopePemilik($query)
    {
        return $query->where('role', 'pemilik');
    }

    public function scopePenyewa($query)
    {
        return $query->where('role', 'penyewa');
    }

    public function scopeAdmin($query)
    {
        return $query->where('role', 'admin');
    }
}