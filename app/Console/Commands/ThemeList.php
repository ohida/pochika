<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

use Conf;
use Finder;

class ThemeList extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'theme:list';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'List installed themes';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		try {
			$current = Conf::get('theme');

			$themes_dir = source_path('themes');

			$finder = new Finder;
			$finder->directories()->in($themes_dir)->depth('==0');

			foreach ($finder as $file) {
				$name = $file->getFilename();
				if ($current == $name) {
					$name .= ' *';
				}
				$this->line($name);
			}
		} catch (\ErrorException $e) {
			$this->error("error: ".$e->getMessage());
		}
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			//['name', InputArgument::REQUIRED, 'Theme name'],
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			//['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
