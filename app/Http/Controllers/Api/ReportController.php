<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BagiHasil;
use App\Models\Transaksi;
use App\Models\Penyewaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function ownerRevenue(Request $request)
    {
        $user = auth()->user();

        $bagiHasil = BagiHasil::with(['penyewaan.motor'])
            ->whereHas('penyewaan.motor', function($query) use ($user) {
                $query->where('pemilik_id', $user->id);
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPendapatan = $bagiHasil->sum('bagi_hasil_pemilik');

        // Data untuk chart
        $chartData = BagiHasil::whereHas('penyewaan.motor', function($query) use ($user) {
                $query->where('pemilik_id', $user->id);
            })
            ->select(DB::raw('MONTH(tanggal) as month, SUM(bagi_hasil_pemilik) as total'))
            ->groupBy('month')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'bagi_hasil' => $bagiHasil,
                'total_pendapatan' => $totalPendapatan,
                'chart_data' => $chartData
            ]
        ]);
    }

    public function adminRevenue(Request $request)
    {
        // Data pendapatan per bulan
        $revenueData = Transaksi::where('status', 'sukses')
            ->select(DB::raw('MONTH(tanggal) as month, YEAR(tanggal) as year, SUM(jumlah) as total'))
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->get();

        // Data bagi hasil
        $bagiHasil = DB::table('bagi_hasils')
            ->select(DB::raw('SUM(bagi_hasil_admin) as total_admin, SUM(bagi_hasil_pemilik) as total_pemilik'))
            ->first();

        // Statistik penyewaan
        $bookingStats = Penyewaan::select(
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status = "selesai" THEN 1 ELSE 0 END) as selesai'),
                DB::raw('SUM(CASE WHEN status = "dikonfirmasi" THEN 1 ELSE 0 END) as aktif')
            )
            ->first();

        return response()->json([
            'success' => true,
            'data' => [
                'revenue_data' => $revenueData,
                'bagi_hasil' => $bagiHasil,
                'booking_stats' => $bookingStats
            ]
        ]);
    }
}