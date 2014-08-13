<?php namespace AppStorage\Favorite;

use Favorite,DB, Request;

class EloquentFavoriteRepository implements FavoriteRepository {

	public function __construct(Favorite $favorite)
	{
		$this->favorite = $favorite;
	}

	public function instance() {
		return new Favorite;
	}

	public function create($user_id, $post_id) {
		$favorite = self::instance();
		$favorite->post_id = $post_id;
		$favorite->user_id = $user_id;//Gotta be from you.
		$favorite->save();
		return $favorite;
	}

	public function exists($user_id, $post_id) {
		return $this->favorite->where('post_id', $post_id)
							->where('user_id', $user_id)
							->count();
	}

	public function has_favorited($user_id, $post_id) {
		return $this->favorite->where('user_id', $user_id)
							->where('post_id', $post_id)
							->count();
	}

	public function delete($user_id, $post_id) {
		$this->favorite->where('post_id', $post_id)
						->where('user_id', $user_id)
						->delete();
	}

}