<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use \Carbon\Carbon;

class AdminControlCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'admin:control';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'add or remove users from admin roles';

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
		$username = $this->argument('username');
		$action = $this->option('action');

		$query = User::where('username', $username);
		if($query->count()) {
			$user = $query->first();
			switch($action) {
				default:
				case 'add':
					$this->addUser($user);
				break;
				case 'delete':
					$this->delUser($user);
				break;
			}
		} else {
			$this->line("can't find that person");
		}
	}

	private function addUser($user) {
		$admin = Role::where('name', 'Admin')->first();
		$mod = Role::where('name', 'Moderator')->first();
		$user->attachRole($admin);
		$user->attachRole($mod);
	}

	private function delUser($user) {
		$admin = Role::where('name', 'Admin')->first();
		$mod = Role::where('name', 'Moderator')->first();
		$user->detachRole($admin);
		$user->detachRole($mod);
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('username', InputOption::VALUE_OPTIONAL, 'username'),
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
			array('action', null, InputOption::VALUE_OPTIONAL, 'add or delete?', null),
		);
	}

}
