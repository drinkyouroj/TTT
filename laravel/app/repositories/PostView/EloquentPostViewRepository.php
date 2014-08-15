<?php namespace AppStorage\PostView;

use PostView, DB;

class EloquentPostViewRepository implements PostViewRepository {

	public function __construct(PostView $postview)
	{
		$this->postview = $postview;
	}

	public function instance() {
		return new PostView;
	}

	public function create($data) {
		$view = self::instance();
		$view->user_id = $data['user_id'];
		$view->post_id = $data['post_id'];
		$view->save();
		return $view;
	}

	public function exists($user_id, $post_id) {
		return $this->postview
					->where('user_id', $user_id)
					->where('post_id', $post_id)
					->count();
	}

}