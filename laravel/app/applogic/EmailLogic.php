<?php namespace AppLogic\EmailLogic;

//Replace with repositories when we can... 
use App,
	Auth,
	View
	;

class EmailLogic {
	
	public function __construct() {
		$this->user = App::make('AppStorage\User\UserRepository');
		$this->post = App::make('AppStorage\Post\PostRepository');
		$this->comment = App::make('AppStorage\Comment\CommentRepository');
		$this->email = App::make('AppStorage\Email\EmailRepository');
		//$this->emailpref = 
	}

	public function post_view($post_id, $post_view) {
		$post = $this->post->findById($post_id);
		$plain = View::make('v2/emails/notifications/post_view_plain')
						->with('post', $post)
						->render();

		$html = View::make('v2/emails/notifications/post_view_html')
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
		$plain = View::make('v2/emails/notifications/like_plain')
						->with('user', $user)
						->with('post', $post)
						->render();

		$html = View::make('v2/emails/notifications/like_html')
						->with('user', $user)
						->with('post', $post)
						->render();

		$email_data = array(
                'from' => 'Two Thousand Times <no_reply@twothousandtimes.com>',
                'to' => array($post->user->email),
                'subject' => 'A New Like on Two Thousand Times!',
                'plaintext' => $plain,
                'html'  => $html
			);
		$this->email->create($email_data);
	}

	public function repost($post, $user) {
		$plain = View::make('v2/emails/notifications/repost_plain')
						->with('user', $user)
						->with('post', $post)
						->render();

		$html = View::make('v2/emails/notifications/repost_html')
						->with('user', $user)
						->with('post', $post)
						->render();

		$email_data = array(
                'from' => 'Two Thousand Times <no_reply@twothousandtimes.com>',
                'to' => array($post->user->email),
                'subject' => 'A New Like on Two Thousand Times!',
                'plaintext' => $plain,
                'html'  => $html
			);
		$this->email->create($email_data);
	}

	public function follow($user_id, $follower_id) {
		$user = $this->user->find($user_id);
		$follower = $this->user->find($follower_id);

		$plain = View::make('v2/emails/notifications/follow_plain')
						->with('user', $user)
						->with('follower', $follower)
						->render();

		$html = View::make('v2/emails/notifications/follow_html')
						->with('user', $user)
						->with('follower', $follower)
						->render();

		$email_data = array(
                'from' => 'Two Thousand Times <no_reply@twothousandtimes.com>',
                'to' => array($user->email),
                'subject' => 'A New Like on Two Thousand Times!',
                'plaintext' => $plain,
                'html'  => $html
			);
		$this->email->create($email_data);
	}

	public function comment($comment, $user) {
		$plain = View::make('v2/emails/notifications/comment_plain')
					->with('comment',$comment)
					->with('user', $user)
					->render();

		$html = View::make('v2/emails/notifications/comment_html')
					->with('comment',$comment)
					->with('user', $user)
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
		$parent = $this->comment->findById($comment->parent_comment);
		$parent_user = $this->user->find($parent->author['user_id']);

		$plain = View::make('v2/emails/notifications/reply_plain')
					->with('parent', $parent)
					->with('parent_user', $parent_user)
					->with('comment',$comment)
					->with('user', $user)
					->render();

		$html = View::make('v2/emails/notifications/reply_html')
					->with('parent', $parent)
					->with('parent_user', $parent_user)
					->with('comment',$comment)
					->with('user', $user)
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