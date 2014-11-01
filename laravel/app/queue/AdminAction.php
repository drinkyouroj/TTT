<?php
class AdminAction {
	
	/**
	 * Constructor
	 */
	public function __construct() {
		$this->email = App::make('AppStorage\Email\EmailRepository');
		$this->user = App::make('AppStorage\User\UserRepository');
        $this->post = App::make('AppStorage\Post\PostRepository');
	}

	/**
	 * Send out weekly digest to all authenticated users ( users with emails )
	 */
	function weeklyDigest( $job, $data ) {
        
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
            'from' => 'Two Thousand Times <no_reply@twothousandtimes.com>',
            'to' => array(),
            'subject' => 'Two Thousand Times - Weekly Digest',
            'plaintext' => $plaintext,
            'html'  => $html
        );

        // Now send out all the emails
        // $users = $this->user->all();
        // foreach ($users as $user) {
        // 	if ( isset( $user->email ) ) {
        // 		$email_data['to'] = array($user->email);
        // 		$this->email->create( $email_data );
        // 	}
        // }
        
        $this->email->test( $email_data );

        $job->delete();
	}

    /**
     *  Add random view counts to all posts
     */
    function addRandomViewCounts( $job, $data ) {
        $posts = $this->post->all( false );
        foreach ($posts as $post) {
            if ( $post instanceof Post ) { // safety check
                $random_views = 1; // rand( 1, 5 )
                $new_view_count = $post->views + $random_views;
                $this->post->updateViewCount( $post->id, $new_view_count );
                // TODO: Reeally need to factor this out (its in 3 diff places now!)
                // TODO: Need to add a catch for this case: post has 7 views, we add 5, post now has 12 views and user was not notified
                // because of logic below...
                $intervals = array(10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000);
                if( in_array($new_view_count, $intervals) ) {
                    //Send the user a notification on the system.
                    NotificationLogic::postview($post->id);
                    if($post->useremail->email) {
                        EmailLogic::post_view($post, $new_view_count);
                    }
                }
            }
        }
        $job->delete();
    }

	function messageAll($job, $data) {
		$users = User::select('id')->get();//get the entire list.
		$job->delete();
	}

}