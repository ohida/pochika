<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Pochika;
use Page;
use Post;

class PochikaList extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'pochika:list';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'List entries';

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		Pochika::init();
		
		$this->info('[posts]');
		$this->render(Post::all());
		
		$this->info('[pages]');
		$this->render(Page::all());
	}

	protected function render($col)
	{
		$col->each(function($item) {
			if ($item->date) {
				$date = date("Y-m-d", $item->date);
			} else {
				$date = '<no date> ';
			}
			$line = sprintf("%s %s %s", $date, $item->title, $item->published ? '' : '*');
			$this->line($line);
		});
	}

}
