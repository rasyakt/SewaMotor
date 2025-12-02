<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Motor;
use App\Models\TarifRental;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Admin
        User::create([
            'nama' => 'Admin Sistem',
            'email' => 'admin@sewamotor.com',
            'no_tlpn' => '081234567890',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create Sample Pemilik
        $pemilik = User::create([
            'nama' => 'Budi Santoso',
            'email' => 'budi@pemilik.com',
            'no_tlpn' => '081234567891',
            'role' => 'pemilik',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create Sample Penyewa
        User::create([
            'nama' => 'Sari Indah',
            'email' => 'sari@penyewa.com',
            'no_tlpn' => '081234567892',
            'role' => 'penyewa',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);

        // Create Sample Motor
        $motor = Motor::create([
            'pemilik_id' => $pemilik->id,
            'merk' => 'Honda Beat',
            'tipe_cc' => '125',
            'no_plat' => 'B 1234 ABC',
            'status' => 'tersedia',
            'photo' => 'motors/photos/sample.jpg',
            'dokumen_kepemilikan' => 'motors/dokumen/sample.pdf',
            'deskripsi' => 'Motor dalam kondisi baik, siap pakai',
        ]);

        // Create Tarif Rental
        TarifRental::create([
            'motor_id' => $motor->id,
            'tarif_harian' => 50000,
            'tarif_mingguan' => 300000,
            'tarif_bulanan' => 1000000,
        ]);

        // Create more sample data...
        // No additional seeders to call currently (MotorSeeder not present)
        $this->call([
            // MotorSeeder::class,
        ]);
    }
}