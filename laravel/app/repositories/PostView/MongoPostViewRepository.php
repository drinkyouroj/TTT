<?php namespace AppStorage\PostView;

use MongoPostView;

class MongoPostViewRepository implements PostViewRepository {

	public function __construct(MongoPostView $postview)
	{
		$this->postview = $postview;
	}

	public function instance() {
		return new MongoPostView;
	}

	public function create($data) {
		$view = self::instance();
		$view->session_id = $data['session_id'];
		$view->post_id = $data['post_id'];
		$view->save();
		return $view;
	}

	public function exists($session_id, $post_id) {
		return $this->postview
					->where('session_id', $session_id)
					->where('post_id', $post_id)
					->count();
	}

}