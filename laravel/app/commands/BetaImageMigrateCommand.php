<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use \Carbon\Carbon;

class BetaImageMigrateCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'betaimage:migrate';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'migrate beta featured post images to user images.';

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
	 * @return mixed
	 */
	public function fire()
	{
		//up to 327
		$this->line('migrating beta featured image data to users');
		$users = User::where('featured', '!=', 0)//Where the user has a featured item.
					->where('id', '<=', 327)//327 before we started accepthing more folks through the landing page.
					->where('last_logged_in', '<=', Carbon::createFromDate(2014, 8, 1)->format('d/m/Y H:i:s') )//logged in earlier than this date.
					->take(327)//total number of beta users.
					->get();
		foreach($users as $k => $user) {
			if(
				isset($user->featured) &&
				( !isset($user->image) || $user->image == 0 || !strlen($user->image) )
				  ) {
				$image = Post::find($user->featured)->image;
				$this->line($image);
				if(stlen($image)) {
					$user->image = $image;
					$user->save();
				}
			}
			$this->line($k. ' '.$user->username.' has been migrated');
		}

	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('example', InputOption::VALUE_OPTIONAL, 'An example argument.'),
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
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
