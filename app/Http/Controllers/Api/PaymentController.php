<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'penyewaan_id' => 'required|exists:penyewaans,id',
            'metode_pembayaran' => 'required|in:transfer,cash,qris',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $penyewaan = Penyewaan::findOrFail($request->penyewaan_id);

            // Pastikan penyewaan milik user yang login
            // gunakan $request->user() agar analyzer tahu tipe user
            if ($penyewaan->penyewa_id !== ($request->user()?->id ?? null)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            // Upload bukti pembayaran jika transfer
            $buktiPath = null;
            if ($request->hasFile('bukti_pembayaran')) {
                $buktiPath = $request->file('bukti_pembayaran')->store('payments', 'public');
            }

            // Buat transaksi
            $transaksi = Transaksi::create([
                'penyewaan_id' => $penyewaan->id,
                'jumlah' => $penyewaan->harga_total,
                'metode_pembayaran' => $request->metode_pembayaran,
                'status' => $request->metode_pembayaran == 'cash' ? 'pending' : 'sukses',
                'tanggal' => now(),
                'bukti_pembayaran' => $buktiPath,
            ]);

            // Update status penyewaan
            $penyewaan->update([
                'status' => $request->metode_pembayaran == 'cash' ? 'pending' : 'dibayar'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pembayaran berhasil diproses',
                'data' => $transaksi
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}