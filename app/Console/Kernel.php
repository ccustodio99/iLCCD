<?php

namespace App\Console;

use App\Models\License;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Log;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->call(function () {
            if (! license_table_exists()) {
                return;
            }

            $license = License::current();
            if ($license && $license->expires_at->isPast()) {
                $license->update(['active' => false]);
                Log::warning('License expired. System deactivated.');
            }
        })->daily();
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
