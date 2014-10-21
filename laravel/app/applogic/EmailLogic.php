<?php namespace AppLogic\EmailLogic;

//Replace with repositories when we can... 
use App,
	Auth,
	DB,
	;

class EmailLogic {
	
	public function __construct() {
		$this->user = App::make('AppStorage\User\UserRepository');
		$this->post = App::make('AppStorage\Post\PostRepository');
		$this->comment = App::make('AppStorage\Comment\CommentRepository');
		$this->email = App::make('AppStorage\Email\EmailRepository');
	}


	public function post_view($post, $post_view) {
		$plain = View::make('v2/emails/notifications/post_view_plain')
						->with('user', $post->user)
						->with('post', $post)
						->render();

		$html = View::make('v2/emails/notifications/post_view_html')
						->with('user', $post->user)
						->with('post', $post)
						->render();

		$email_data = array(
                'from' => 'Two Thousand Times <no_reply@twothousandtimes.com>',
                'to' => array($post->user->email),
                'subject' => 'Your Broke '.$post_views.' views on Two Thousand Times!',
                'plaintext' => $plain,
                'html'  => $html
			);
		$this->email->create($email_data);
	}

	public function like($post, $user) {

	}

	public function repost($post, $user) {

	}

	public function follow($user, $follower) {

	}

	public function comment($comment, $user) {
		$plain = View::make('v2/emails/notifications/comment_plain')
					->with('comment',$comment)
					->with('user', $user)
					->with('reply', false)
					->render();

		$html = View::make('v2/emails/notifications/comment_html')
					->with('comment',$comment)
					->with('user', $user)
					->with('reply', false)
					->render();

		$email_data = array(
            'from' => 'Two Thousand Times <no_reply@twothousandtimes.com>',
            'to' => array($comment->post->user->email),
            'subject' => 'A New Comment on Two Thousand Times!',
            'plaintext' => $plain,
            'html'  => $html
		);
		$this->email->create($email_data);
		
	}

	public function reply($comment, $user) {
		$plain = View::make('v2/emails/notifications/reply_plain')
					->with('comment',$comment)
					->with('user', $user)
					->with('reply', true)
					->render();

		$html = View::make('v2/emails/notifications/reply_html')
					->with('comment',$comment)
					->with('user', $user)
					->with('reply', true)
					->render();

		$email_data = array(
            'from' => 'Two Thousand Times <no_reply@twothousandtimes.com>',
            'to' => array($parent->user->email),
            'subject' => 'A New Reply on Two Thousand Times!',
            'plaintext' => $plain,
            'html'  => $html
		);
		$this->email->create($email_data);
	}

}