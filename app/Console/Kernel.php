<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('auto-close-job')->everyMinute()->appendOutputTo(storage_path('logs/close_job.log'));
        $schedule->command('advertise-expire')->everyMinute()->appendOutputTo(storage_path('logs/advertise_expire.log'));
        $schedule->command('auto-expire-coins')->everyMinute()->appendOutputTo(storage_path('logs/expire_coins.log'));
        $schedule->command('telescope:prune')->everySecond();
    }
    protected function commands(): void
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
