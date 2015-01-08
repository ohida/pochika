<?php namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

	/**
	 * The Artisan commands provided by your application.
	 *
	 * @var array
	 */
	protected $commands = [
		//'App\Console\Commands\Inspire',
		'App\Console\Commands\PochikaNewCommand',
		'App\Console\Commands\PochikaClearCacheCommand',
		'App\Console\Commands\PochikaInstallCommand',
		'App\Console\Commands\ThemePublishCommand',
		'App\Console\Commands\ThemeListCommand',
		'App\Console\Commands\PochikaListCommand',
		'App\Console\Commands\EmojiUpdateCommand',
		'App\Console\Commands\PostNewCommand',
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
