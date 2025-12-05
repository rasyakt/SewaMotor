<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Run motor return check setiap hari jam 00:00
        $schedule->command('motor:check-return')
            ->daily()
            ->onSuccess(function () {
                \Illuminate\Support\Facades\Log::info('✓ Motor return check selesai dijalankan');
            })
            ->onFailure(function () {
                \Illuminate\Support\Facades\Log::error('✗ Motor return check gagal');
            });

        // Alternatif: jalankan setiap jam (untuk development/testing)
        // $schedule->command('motor:check-return')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
