<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class LaunchEmailCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'launch:reserved';

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
		$this->email = App::make('AppStorage\Email\EmailRepository');
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

		$reserved_users = $this->reservedQuery($paginate, $page);//initial pull

		while ( count( $reserved_users ) > 0 ) {
			foreach($reserved_users as $k=>$user) {
				if(is_object($user) && isset($user->email)) {
					//reset and send out a password.
					$reset = $this->user->resetPassword($user->id);					
					$this->line($user->username. ' email sent');
					$this->sendEmail($user,$reset['new_password']);
				}
			}
			$page++;
			$reserved_users = $this->reservedQuery($paginate, $page);
		}

	}

		private function reservedQuery($paginate, $page) {
			return User::where('reserved',1)
						->whereNotNull('email')
						->select('id','username', 'email','reserved')
						->skip( $paginate * $page )
						->take( $paginate )
						->get();
		}

		private function sendEmail($user,$pass) {
			$plain = View::make('v2/emails/launch_plain')
						->with('user', $user)
						->with('pass', $pass)
						->render();

			$html = View::make('v2/emails/launch_html')
						->with('user', $user)
						->with('pass', $pass)
						->render();

			$data = array(
					'from' => 'Two Thousand Times <no_reply@twothousandtimes.com>',
					'to' => array($user->email),
					'subject' => 'Welcome to Two Thousand Times!',
					'plaintext' => $plain,
					'html' => $html
				);

			$this->email->create($data);
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
			array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
		);
	}

}
