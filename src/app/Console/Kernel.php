<?php

namespace App\Console;

use App\Utils\DateUtils;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [//
    ];

    /**
     * Define the application's command schedule.
     *
     * @param \Illuminate\Console\Scheduling\Schedule $schedule
     *
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('notifications:periodic-create')
            ->monthlyOn(1, '0:00');
        $schedule->command('tasks:periodic-create')
            ->monthlyOn(1, '0:00');
        $schedule->command('tasks:disable')
            ->dailyAt('0:00');
        $schedule->command('notifications:insert')
            ->monthlyOn(DateUtils::JOB_DAY, DateUtils::JOB_HOUR . ':00');
        $schedule->command('notifications:send')
            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');
        require base_path('routes/console.php');
    }
}
