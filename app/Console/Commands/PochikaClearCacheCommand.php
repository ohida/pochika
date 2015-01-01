<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Pochika;

class PochikaClearCacheCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'pochika:clear';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Clear caches';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		Pochika::clearCache();
		printf('cache cleared'.PHP_EOL);
	}

}
