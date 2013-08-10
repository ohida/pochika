<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PochikaPublishAssetsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'pochika:publish_assets';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Publish the pochika assets of theme.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function fire()
	{
        $configThemeName   = Conf::get('theme');
		$sourceAssetPath   = sprintf("%s/source/themes/%s/assets", base_path(), $configThemeName);
        $publishAssetPath  = sprintf("%s/assets", public_path());

        if (file_exists($sourceAssetPath) && is_dir($sourceAssetPath))
        {
    		unlink($publishAssetPath);
    		symlink($sourceAssetPath, $publishAssetPath);
        }
        else
        {
            throw new \Pochika\Exception\NotFoundException(
                sprintf("theme[%s] could not found.", $configThemeName));
        }

        $this->info(sprintf("theme[%s] published!", $configThemeName));
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
//			array('example', InputArgument::REQUIRED, 'An example argument.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
//			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}