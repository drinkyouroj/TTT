<?php
class AdminAction {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->email = App::make('AppStorage\Email\EmailRepository');
		$this->user = App::make('AppStorage\User\UserRepository');
	}

	/**
	 * Send out weekly digest to all authenticated users ( users with emails )
	 */
	function weeklyDigest( $job, $data ) {
		// Fetch the users (TODO: reduce the users to only those with emails)
		// $users = $this->user->all();
        
		// Render the emails (plain text and html). Only need to render once
		$plaintext = View::make('v2/emails/weekly_digest_plain')
    						->with( 'featured_post', $data['featured_post'] )
    						->with( 'post_2', $data['post_2'] )
    						->with( 'post_3', $data['post_3'] )
    						->with( 'post_4', $data['post_4'] )
    						->with( 'post_5', $data['post_5'] )
    						->render();

    	$html = View::make('v2/emails/weekly_digest_html')
    						->with( 'featured_post', $data['featured_post'] )
    						->with( 'post_2', $data['post_2'] )
    						->with( 'post_3', $data['post_3'] )
    						->with( 'post_4', $data['post_4'] )
    						->with( 'post_5', $data['post_5'] )
    						->render();
    	// Setup the email data (everything but the 'to' field)
		$email_data = array(
            // 'from' => 'Two Thousand Times <no_reply@twothousandtimes.com>',
            'to' => array(),
            'subject' => 'Two Thousand Times - Weekly Digest',
            'plaintext' => $plaintext,
            'html'  => $html
        );
        // Now send out all the emails
        
        // foreach ($users as $user) {
        // 	if ( isset( $user->email ) ) {
        // 		$email_data['to'] = array($user->email);
        // 		$this->email->create( $email_data );
        // 	}
        // }
        
        $this->email->test( $email_data );

        $job->delete();
	}

	function messageAll($job, $data) {
		$users = User::select('id')->get();//get the entire list.
		$job->delete();
	}

}