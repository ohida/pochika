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
        //'App\Console\Commands\Inspire',
        'App\Console\Commands\PochikaNew',
        'App\Console\Commands\PochikaClearCache',
        'App\Console\Commands\PochikaInstall',
        'App\Console\Commands\ThemePublish',
        'App\Console\Commands\ThemeList',
        'App\Console\Commands\PochikaList',
        'App\Console\Commands\EmojiUpdate',
        'App\Console\Commands\PostNew',
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();
    }
}
