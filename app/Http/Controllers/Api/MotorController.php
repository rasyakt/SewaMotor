<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\TarifRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MotorController extends Controller
{
    public function index()
    {
        $motors = Motor::with('tarifRental')
            ->where('status', 'tersedia')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $motors
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'merk' => 'required|string|max:255',
            'tipe_cc' => 'required|in:100,125,150',
            'no_plat' => 'required|string|unique:motors',
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'dokumen_kepemilikan' => 'required|file|mimes:pdf,jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string',
            'tarif_harian' => 'required|numeric|min:0',
            'tarif_mingguan' => 'required|numeric|min:0',
            'tarif_bulanan' => 'required|numeric|min:0',
        ]);

        // Upload files
        $photoPath = $request->file('photo')->store('motors/photos', 'public');
        $dokumenPath = $request->file('dokumen_kepemilikan')->store('motors/dokumen', 'public');

        // Create motor
        $motor = Motor::create([
            'pemilik_id' => $request->user()->id ?? null,
            'merk' => $request->merk,
            'tipe_cc' => $request->tipe_cc,
            'no_plat' => $request->no_plat,
            'photo' => $photoPath,
            'dokumen_kepemilikan' => $dokumenPath,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
        ]);

        // Create tarif
        TarifRental::create([
            'motor_id' => $motor->id,
            'tarif_harian' => $request->tarif_harian,
            'tarif_mingguan' => $request->tarif_mingguan,
            'tarif_bulanan' => $request->tarif_bulanan,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Motor berhasil ditambahkan',
            'data' => $motor->load('tarifRental')
        ], 201);
    }

    public function show($id)
    {
        $motor = Motor::with(['tarifRental', 'pemilik'])->find($id);

        if (!$motor) {
            return response()->json([
                'success' => false,
                'message' => 'Motor tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $motor
        ]);
    }

    // Tambahkan method ini ke MotorController.php
public function verify($id)
{
    $motor = Motor::findOrFail($id);
    
    if ($motor->status !== 'pending') {
        return response()->json([
            'success' => false,
            'message' => 'Motor sudah diverifikasi sebelumnya'
        ], 400);
    }

    $motor->update([
        'status' => 'verified',
        // Set harga default jika belum ada tarif
    ]);

    // Jika belum ada tarif, buat tarif default
    if (!$motor->tarifRental) {
        TarifRental::create([
            'motor_id' => $motor->id,
            'tarif_harian' => 50000,
            'tarif_mingguan' => 300000,
            'tarif_bulanan' => 1000000,
        ]);
    }

    return response()->json([
        'success' => true,
        'message' => 'Motor berhasil diverifikasi',
        'data' => $motor->load('tarifRental')
    ]);
}
}