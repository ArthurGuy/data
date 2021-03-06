<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\SyncDevices::class,
        \App\Console\Commands\AutoHeating::class,
        \App\Console\Commands\CalculateParentLocationValues::class,
        \App\Console\Commands\AutoLighting::class,
        \App\Console\Commands\CheckLocationLastSensorUpdate::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('devices:sync')
            ->everyFiveMinutes()
            ->thenPing('http://beats.envoyer.io/heartbeat/rUvTAoCyZfuvD1o');

        $schedule->command('location:manage-auto-heating')
            ->everyMinute()
            ->thenPing('http://beats.envoyer.io/heartbeat/mTKr7ltLo0DRhxB');

        $schedule->command('location:calculate-home-values')
            ->everyMinute()
            ->thenPing('http://beats.envoyer.io/heartbeat/X8HrPEQJBQMzCWr');

        $schedule->command('location:lighting')
            ->everyMinute()
            ->thenPing('http://beats.envoyer.io/heartbeat/AHVhwcdXDgNtynS');

        //$schedule->command('location:check-last-update')
        //    ->everyThirtyMinutes()
        //    ->thenPing('http://beats.envoyer.io/heartbeat/g5vlxnj4cukHvBO');
    }
}
