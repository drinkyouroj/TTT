<?php namespace AppStorage\PostFlagged;

use PostFlagged, DB;

class EloquentPostFlaggedRepository implements PostFlaggedRepository {

	public function __construct(PostFlagged $postflagged)
	{
		$this->postflagged = $postflagged;
	}

	public function instance() {
		return new PostFlagged;
	}

	public function create($user_id, $post_id) {
		// Make sure it doesnt exists first
		$flag = $this->postflagged->where('user_id', $user_id)
						  ->where('post_id', $post_id)
						  ->first();
		if ( !($flag instanceof PostFlagged) ) {
			$flag = self::instance();
			$flag->user_id = $user_id;
			$flag->post_id = $post_id;
			$flag->save();	
		}
		return $flag;
	}

	public function delete($user_id, $post_id) {
		$this->postflagged->where('user_id', $user_id)
						  ->where('post_id', $post_id)
						  ->delete();
	}

	public function count($post_id) {
		return $this->postflagged->where('post_id',$post_id)->count();
	}

	public function exists($user_id, $post_id) {
		return $this->postflagged
					->where('user_id', $user_id)
					->where('post_id', $post_id)
					->count() > 0 ? true : false;
	}

}