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
                            ->with( 'digest_id', $data['digest_id'] )
    						->render();

    	$html = View::make('v2/emails/weekly_digest_html')
    						->with( 'featured_post', $data['featured_post'] )
    						->with( 'post_2', $data['post_2'] )
    						->with( 'post_3', $data['post_3'] )
    						->with( 'post_4', $data['post_4'] )
    						->with( 'post_5', $data['post_5'] )
                            ->with( 'digest_id', $data['digest_id'] )
    						->render();

        // Now send out all the emails
        $users = $this->user->all(true);
        //$test = '';

        foreach ($users as $user) {
        	if ( isset( $user->email ) ) {
                //$test.= $user->email.', ';
                $email_data = array(
                    'from' => 'Sondry <no_reply@sondry.com>',
                    'to' => array($user->email),
                    'subject' => 'Sondry - Weekly Digest',
                    'plaintext' => $plaintext,
                    'html'  => $html
                );
        		$this->email->create( $email_data );
        	}
        }
        /*
        // This is just for testing
        $email_data = array(
            'from' => 'Sondry <no_reply@sondry.com>',
            'to' => array(),
            'subject' => 'Sondry - Weekly Digest TEST',
            'plaintext' => $plaintext,
            'html'  => $html
        );
        $this->email->test( $email_data );
        */
        $job->delete();
	}

    /**
     *  Add random view counts to all posts
     */
    function addRandomViewCounts( $job, $data ) {
        $posts = $this->post->allPublished();
        foreach ($posts as $post) {
            if ( $post instanceof Post ) { // safety check
                $random_views = 1; // rand( 1, 5 )
                $new_view_count = $post->views + $random_views;
                $this->post->updateViewCount( $post->id, $new_view_count );
            }
        }
        $job->delete();
    }

	function messageAll($job, $data) {
		$users = User::select('id')->get();//get the entire list.
		$job->delete();
	}

}