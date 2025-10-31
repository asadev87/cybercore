<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\{
    DeduplicateModules,
    SyncModuleNotes,
    RegenerateCertificates
};

class Kernel extends ConsoleKernel
{
    protected $commands = [
        DeduplicateModules::class,
        SyncModuleNotes::class,
        RegenerateCertificates::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
        // Define scheduled commands here if needed.
    }

    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
