<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class NameChangeEmailCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'sondry:namechange';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'This is the email sent to users about the name change.';

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
		$send_all_emails = $this->argument('send');

		// Render the emails (plain text and html). Only need to render once
		$plaintext = View::make('v2/emails/namechange_plain')->render();
    	$html = View::make('v2/emails/namechange_html')->render();

        // This is a test, only send emails to us
        if ( $send_all_emails == 'false' ) {
        	$email_data = array(
                'from' => 'Sondry <team@sondry.com>',
                'subject' => 'Two Thousand Times is now Sondry',
                'plaintext' => $plaintext,
                'html'  => $html
            );
	        $this->email->test( $email_data );
	        echo "Test emails sent...\n";

	    // Not a test, send out the actual emails!
        } else if ( $send_all_emails == 'true' ) {
        	// Now send out all the emails
        	$users = $this->user->all(true);
        	$count = 0;
        	foreach ($users as $user) {        		
	        	if ( isset( $user->email ) ) {
	        		$this->line($user->email);
	        		$count++;
	                $email_data = array(
	                    'from' => 'Sondry <team@sondry.com>',
	                    'to' => array($user->email),
	                    'subject' => 'Two Thousand Times is now Sondry',
	                    'plaintext' => $plaintext,
	                    'html'  => $html
	                );
	        		$this->email->create( $email_data );
	        	}
	        }
	        echo $count." emails sent!\n";

	    // Invalid argument    
        } else {
        	echo "Invalid argument. `true` or `false`\n";
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
			array('send', InputArgument::REQUIRED, 'Send out the live emails (true) or send out test emails to us (false).'),
		);
	}

}
