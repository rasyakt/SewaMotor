<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penyewaan;
use App\Models\Motor;
use App\Models\BagiHasil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'motor_id' => 'required|exists:motors,id',
            'tanggal_mulai' => 'required|date|after:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan',
        ]);

        try {
            DB::beginTransaction();

            $motor = Motor::with('tarifRental')->findOrFail($request->motor_id);

            // Cek ketersediaan motor
            if ($motor->status !== 'tersedia') {
                return response()->json([
                    'success' => false,
                    'message' => 'Motor tidak tersedia untuk disewa'
                ], 400);
            }

            // Hitung durasi dan harga
            $tanggalMulai = \Carbon\Carbon::parse($request->tanggal_mulai);
            $tanggalSelesai = \Carbon\Carbon::parse($request->tanggal_selesai);
            $durasi = $tanggalMulai->diffInDays($tanggalSelesai);

            $tarif = $motor->tarifRental->getTarif($request->tipe_durasi);
            $hargaTotal = $tarif * $durasi;

            // Buat penyewaan
            $penyewaan = Penyewaan::create([
                'penyewa_id' => auth()->id(),
                'motor_id' => $motor->id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
                'tipe_durasi' => $request->tipe_durasi,
                'harga_total' => $hargaTotal,
                'status' => 'pending',
            ]);

            // Update status motor
            $motor->update(['status' => 'pending_sewa']);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Booking berhasil dibuat',
                'data' => $penyewaan->load(['motor', 'penyewa'])
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function history(Request $request)
    {
        $penyewaans = Penyewaan::with(['motor', 'transaksi'])
            ->where('penyewa_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $penyewaans
        ]);
    }

    public function confirm($id)
    {
        $penyewaan = Penyewaan::with('motor')->findOrFail($id);

        if ($penyewaan->status !== 'dibayar') {
            return response()->json([
                'success' => false,
                'message' => 'Hanya penyewaan yang sudah dibayar yang bisa dikonfirmasi'
            ], 400);
        }

        $penyewaan->update(['status' => 'dikonfirmasi']);
        $penyewaan->motor->update(['status' => 'disewa']);

        return response()->json([
            'success' => true,
            'message' => 'Penyewaan berhasil dikonfirmasi',
            'data' => $penyewaan
        ]);
    }
}