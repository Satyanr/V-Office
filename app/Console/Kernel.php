<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */

    protected $commands = [
        \App\Console\Commands\DispatchProjectReminder::class,
    ];

    protected function schedule(Schedule $schedule): void
    {
          $schedule->command('project:dispatch-reminder')->dailyAt('08:00'); // jam masuk kantor we jam 8 pagi
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
