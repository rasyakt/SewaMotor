<?php

namespace App\Console\Commands;

use App\Models\Penyewaan;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CheckMotorReturn extends Command
{
    protected $signature = 'motor:check-return';
    protected $description = 'Check dan tandai motor yang sudah melewati tanggal selesai penyewaan';

    public function handle()
    {
        $this->info('🚍 Mengecek pengembalian motor yang sudah melewati waktu...');

        // Cari penyewaan yang sudah melewati tanggal selesai tapi belum di-return
        $overdueRentals = Penyewaan::where('status', '!=', 'selesai')
            ->where('status', '!=', 'dibatalkan')
            ->where('tanggal_selesai', '<', Carbon::now()->toDateString())
            ->get();

        $count = $overdueRentals->count();
        $this->info("Ditemukan {$count} penyewaan yang melewati waktu.");

        foreach ($overdueRentals as $rental) {
            $hariTerlambat = Carbon::parse($rental->tanggal_selesai)->diffInDays(Carbon::now());
            
            $this->line("⏰ Penyewaan #{$rental->id} - Motor: {$rental->motor->nama_motor}");
            $this->line("   Penyewa: {$rental->penyewa->nama}");
            $this->line("   Terlambat: {$hariTerlambat} hari");

            // Mark sebagai overdue (auto-return)
            $rental->handleReturn(Carbon::now()->toDateString(), false);

            $this->line("   ✅ Status diperbarui menjadi: {$rental->getStatusPengembalianLabel()}");
            
            if ($rental->denda_keterlambatan > 0) {
                $this->line("   💰 Denda Keterlambatan: Rp " . number_format($rental->denda_keterlambatan, 0, ',', '.'));
            }
        }

        $this->info("\n✓ Pengecekan selesai!");

        return self::SUCCESS;
    }
}
