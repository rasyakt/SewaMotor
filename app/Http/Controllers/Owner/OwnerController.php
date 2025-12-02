<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\BagiHasil;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\TarifRental;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class OwnerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:pemilik']);
    }

    public function dashboard()
    {
    $user = Auth::user();
        $stats = [
            'total_motor' => $user->motors()->count(),
            'motor_verified' => $user->motors()->where('status', 'verified')->count(),
            'motor_disewa' => $user->motors()->where('status', 'disewa')->count(),
            'total_pendapatan' => BagiHasil::whereHas('penyewaan.motor', function($query) use ($user) {
                $query->where('pemilik_id', $user->id);
            })->sum('bagi_hasil_pemilik'),
        ];

        return view('owner.dashboard', compact('stats'));
    }

    public function motors()
    {
        // paginate agar pagination di view dapat digunakan
    $motors = Auth::user()->motors()->with('tarifRental')->paginate(9);
        return view('owner.motors.index', compact('motors'));
    }

    public function createMotor()
    {
        return view('owner.motors.create');
    }

    public function storeMotor(Request $request)
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

        // Upload foto
        $photoPath = $request->file('photo')->store('motors/photos', 'public');
        $dokumenPath = $request->file('dokumen_kepemilikan')->store('motors/dokumen', 'public');

        // Create motor
        $motor = Motor::create([
            'pemilik_id' => Auth::id(),
            'merk' => $request->merk,
            'tipe_cc' => $request->tipe_cc,
            'no_plat' => $request->no_plat,
            'photo' => $photoPath,
            'dokumen_kepemilikan' => $dokumenPath,
            'deskripsi' => $request->deskripsi,
            'status' => 'pending',
        ]);

        // Create tarif rental
        TarifRental::create([
            'motor_id' => $motor->id,
            'tarif_harian' => $request->tarif_harian,
            'tarif_mingguan' => $request->tarif_mingguan,
            'tarif_bulanan' => $request->tarif_bulanan,
        ]);

        return redirect()->route('owner.motors')->with('success', 'Motor berhasil ditambahkan dan menunggu verifikasi admin');
    }

    // Di OwnerController.php - update method revenue()
    public function revenue()
    {
        $bagiHasil = BagiHasil::with(['penyewaan.motor'])
            ->whereHas('penyewaan.motor', function($query) {
                $query->where('pemilik_id', Auth::id());
            })
            ->orderBy('tanggal', 'desc')
            ->get();

        $totalPendapatan = $bagiHasil->sum('bagi_hasil_pemilik');

        return view('owner.revenue.index', compact('bagiHasil', 'totalPendapatan'));
    }
}