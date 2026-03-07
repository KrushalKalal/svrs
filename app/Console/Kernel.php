<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Register Artisan commands
     */
    protected $commands = [
        \App\Console\Commands\FirebaseTest::class,
        \App\Console\Commands\GenerateCoinCandle::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {   
        $schedule->command('generate:coin-candle')->everyFiveMinutes();
    }

    /**
     * Load commands
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}
