<?php namespace AppStorage\Like;

use Like, DB, Request, Auth;

class EloquentLikeRepository implements LikeRepository {

	public function __construct(Like $like)
	{
		$this->like = $like;
	}

	public function instance() {
		return new Like;
	}

	public function create($user_id, $post_id) {
		$like = self::instance();
		$like->post_id = $post_id;
		$like->user_id = $user_id;
		$like->save();
		return $like;
	}

	public function exists($user_id, $post_id) {
		return $this->like->where('post_id', $post_id )
						->where('user_id', $user_id )
						->count();
	}

	public function has_liked($user_id, $post_id) {
		return $this->like->where('user_id', $user_id)
							->where('post_id', $post_id)
							->count();
	}

	public function delete($user_id, $post_id) {
		$this->like->where('post_id', $post_id )
					->where('user_id', $user_id )
					->delete();	
	}


}