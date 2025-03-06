<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        Commands\SyncPermissions::class,
        Commands\CreateModule::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * These schedules are run in a default, single-server environment.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Her gün gece yarısı yedek al ve hataları kaydet
        $schedule->command('backup:database')
                ->dailyAt('00:00')
                ->appendOutputTo(storage_path('logs/backup.log'))
                ->onFailure(function () {
                    // Hata durumunda bildirim gönderilebilir
                    // Log::error('Yedekleme işlemi başarısız oldu');
                });
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
} 