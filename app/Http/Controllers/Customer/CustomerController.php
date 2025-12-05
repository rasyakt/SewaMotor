<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\TarifRental;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:penyewa']);
    }

    // public function dashboard()
    // {
    // $user = Auth::user();
    //     $stats = [
    //         'total_penyewaan' => $user->penyewaans()->count(),
    //         'penyewaan_aktif' => $user->penyewaans()->where('status', 'dikonfirmasi')->count(),
    //         'penyewaan_selesai' => $user->penyewaans()->where('status', 'selesai')->count(),
    //     ];

    //     return view('customer.dashboard', compact('stats'));
    // }

    // Di dalam CustomerController class - update method dashboard()
public function dashboard()
{
    $user = Auth::user();
    
    $totalPenyewaan = $user->penyewaans()->count();
    $penyewaanAktif = $user->penyewaans()->whereIn('status', ['dikonfirmasi', 'dibayar'])->count();
    $penyewaanSelesai = $user->penyewaans()->where('status', 'selesai')->count();
    
    // Hitung total pengeluaran
    $totalPengeluaran = $user->penyewaans()
        ->where('status', '!=', 'dibatalkan')
        ->sum('harga_total');

    $stats = [
        'total_penyewaan' => $totalPenyewaan,
        'penyewaan_aktif' => $penyewaanAktif,
        'penyewaan_selesai' => $penyewaanSelesai,
        'total_pengeluaran' => $totalPengeluaran,
    ];

    return view('customer.dashboard', compact('stats'));
}

    public function motors()
    {
        // paginate untuk mendukung pagination di view jika diperlukan
        $motors = Motor::with('tarifRental')
            ->where('status', 'tersedia')
            ->paginate(9);

        return view('customer.motors.index', compact('motors'));
    }

    public function showMotor($id)
    {
        $motor = Motor::with('tarifRental')->findOrFail($id);
        return view('customer.motors.show', compact('motor'));
    }

    public function bookMotor(Request $request, $id)
    {
        $request->validate([
            'tanggal_mulai' => 'required|date|after:today',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'tipe_durasi' => 'required|in:harian,mingguan,bulanan',
        ]);

        $motor = Motor::with('tarifRental')->findOrFail($id);

        // Hitung harga total
        $durasi = \Carbon\Carbon::parse($request->tanggal_mulai)
            ->diffInDays($request->tanggal_selesai);

        $tarif = $motor->tarifRental->getTarif($request->tipe_durasi);
        $hargaTotal = $tarif * $durasi;

        // Create penyewaan
        $penyewaan = Penyewaan::create([
            'penyewa_id' => Auth::id(),
            'motor_id' => $motor->id,
            'tanggal_mulai' => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'tipe_durasi' => $request->tipe_durasi,
            'harga_total' => $hargaTotal,
            'status' => 'pending',
        ]);

        // Update status motor
        $motor->update(['status' => 'pending_sewa']);

        return redirect()->route('customer.bookings.history')
            ->with('success', 'Pemesanan berhasil dibuat. Silakan lakukan pembayaran.');
    }

    public function bookingHistory()
    {
    $penyewaans = Auth::user()->penyewaans()
            ->with(['motor', 'transaksi'])
            ->latest()
            ->get();

        return view('customer.bookings.history', compact('penyewaans'));
    }

    public function payment($id)
    {
        $penyewaan = Penyewaan::with('motor')->findOrFail($id);
        return view('customer.payments.create', compact('penyewaan'));
    }

    public function processPayment(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required|in:transfer,cash,qris',
            'bukti_pembayaran' => 'required_if:metode_pembayaran,transfer|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $penyewaan = Penyewaan::findOrFail($id);

        // Upload bukti pembayaran jika transfer
        $buktiPath = null;
        if ($request->hasFile('bukti_pembayaran')) {
            $buktiPath = $request->file('bukti_pembayaran')->store('payments', 'public');
        }

        // Create transaksi
        Transaksi::create([
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

        return redirect()->route('customer.bookings.history')
            ->with('success', 'Pembayaran berhasil diproses.');
    }

    /**
     * Return motor (kembalikan motor)
     */
    public function returnMotor(Request $request, $id)
    {
        $request->validate([
            'kondisi_motor' => 'required|in:baik,rusak',
            'catatan' => 'nullable|string|max:500',
        ]);

        $penyewaan = Penyewaan::findOrFail($id);

        // Validasi hanya bisa return motor sendiri
        if ($penyewaan->penyewa_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        // Validasi status harus aktif/sudah dibayar
        if (!in_array($penyewaan->status, ['dikonfirmasi', 'dibayar'])) {
            return back()->with('error', 'Motor tidak dapat dikembalikan dengan status saat ini.');
        }

        // Handle return
        $isRusak = $request->kondisi_motor === 'rusak';
        $penyewaan->handleReturn(now()->toDateString(), $isRusak);

        // Buat record di Transaksi jika ada denda
        if ($penyewaan->denda_keterlambatan > 0) {
            Transaksi::create([
                'penyewaan_id' => $penyewaan->id,
                'jumlah' => $penyewaan->denda_keterlambatan,
                'metode_pembayaran' => 'denda',
                'status' => 'pending',
                'tanggal' => now(),
                'bukti_pembayaran' => null,
            ]);
        }

        $message = 'Motor berhasil dikembalikan.';
        if ($penyewaan->status_pengembalian === 'terlambat') {
            $message .= " Terlambat {$penyewaan->hari_terlambat} hari. Denda: Rp " . number_format($penyewaan->denda_keterlambatan, 0, ',', '.');
        }

        return redirect()->route('customer.bookings.history')
            ->with('success', $message);
    }
}