<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Motor;
use App\Models\Penyewaan;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function dashboard()
    {
        $stats = [
            'total_motor' => Motor::count(),
            'total_penyewa' => User::penyewa()->count(),
            'total_pemilik' => User::pemilik()->count(),
            'total_penyewaan' => Penyewaan::count(),
            'pendapatan_bulan_ini' => Transaksi::where('status', 'sukses')
                ->whereMonth('tanggal', now()->month)
                ->sum('jumlah'),
        ];

        return view('admin.dashboard', compact('stats'));
    }

    public function motors()
    {
        // Filter
        $query = Motor::with(['pemilik', 'tarifRental']);
        if (request('status')) {
            $query->where('status', request('status'));
        }
        if (request('merk')) {
            $query->where('merk', 'like', '%' . request('merk') . '%');
        }
        if (request('pemilik_id')) {
            $query->where('pemilik_id', request('pemilik_id'));
        }
        $motors = $query->paginate(9);
        return view('admin.motors.index', compact('motors'));
    }

    public function verifyMotor($id)
    {
        $motor = Motor::findOrFail($id);
        // Setelah admin verifikasi, set status menjadi 'tersedia'
        // agar motor muncul pada listing untuk customer/penyewa
        $motor->update(['status' => 'tersedia']);
        
        return redirect()->back()->with('success', 'Motor berhasil diverifikasi');
    }

    public function users()
    {
        $users = User::where('role', '!=', 'admin')->get();
        return view('admin.users.index', compact('users'));
    }

    public function penyewaans()
    {
        // Eager load transaksi so we can check payment method/status without extra queries
        $penyewaans = Penyewaan::with(['penyewa', 'motor', 'transaksi'])->get();
        return view('admin.penyewaans.index', compact('penyewaans'));
    }

    public function confirmPenyewaan(Request $request, $id = null)
    {
        // Support both route parameter or request payload to ensure $id is defined
        if (is_null($id)) {
            // use the global request() helper so the code doesn't depend on a local $request variable
            $id = request()->route('id') ?? request()->input('id');
        }

        $penyewaan = Penyewaan::findOrFail($id);
        $penyewaan->update(['status' => 'dikonfirmasi']);

        // Jika ada transaksi (mis. cash) ubah status transaksi menjadi sukses
        if ($penyewaan->transaksi) {
            $penyewaan->transaksi->update(['status' => 'sukses']);
        }

        // Update status motor menjadi disewa
        $penyewaan->motor->update(['status' => 'disewa']);

        return redirect()->back()->with('success', 'Penyewaan berhasil dikonfirmasi');
    }

    public function reports()
    {
        $revenueData = Transaksi::where('status', 'sukses')
            ->select(DB::raw('MONTH(tanggal) as month, SUM(jumlah) as total'))
            ->groupBy('month')
            ->get();

        $bagiHasil = DB::table('bagi_hasils')
            ->select(DB::raw('SUM(bagi_hasil_admin) as total_admin, SUM(bagi_hasil_pemilik) as total_pemilik'))
            ->first();

        return view('admin.reports.index', compact('revenueData', 'bagiHasil'));
    }
}