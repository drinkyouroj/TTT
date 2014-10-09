<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ReservedDumpCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'launch:dump';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Sends out e-mails to reserved users.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->user = App::make('AppStorage\User\UserRepository');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		//
		$paginate = 50;//just to make sure that we only really do 1.
		$page = 0;//default;
		$total = 0;
		if($this->option('mock') == false) {
			$query = User::where('reserved',1)
				->whereNull('last_logged_in')
				->whereNotNull('email');

			$total = $query->count();

			$query->forceDelete();
		} else {
			User::where('reserved',1)
				->whereNull('last_logged_in')
				->whereNotNull('email');
			$total = $query->count();
		}

		$this->line("We've deleted ".$total." Reserved Users");

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
            array('page', InputOption::VALUE_OPTIONAL, 'What page are we processing from?'),

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
			array('mock', null, InputOption::VALUE_OPTIONAL, 'An example option.', true),
		);
	}

}
