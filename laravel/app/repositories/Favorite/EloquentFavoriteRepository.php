<?php namespace AppStorage\Favorite;

use Favorite,DB;

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
		$favorite->read = 0;//not read yet!
		$favorite->save();
		return $favorite;
	}

	public function allByUserId($user_id, $paginate = 12, $page = 1, $rest = false) {
		$query =  $this->favorite
						->where('user_id', $user_id)
						->orderBy('updated_at', 'DESC')
						->skip(($page-1)*$paginate)
						->take($paginate);
		if($rest) {
			return $query->with('post.user')->get();
		} else {
			return $query->get();
		}
	}

	public function exists($user_id, $post_id) {
		return $this->favorite
						->where('post_id', $post_id)
						->where('user_id', $user_id)
						->count();
	}

	public function read($user_id, $post_id) {
		$favorite = $this->favorite
						->where('post_id', $post_id)
						->where('user_id', $user_id)
						->first();
		$favorite->read = 1;
		$favorite->save();
	}

	public function has_favorited($user_id, $post_id) {
		return $this->favorite
						->where('user_id', $user_id)
						->where('post_id', $post_id)
						->count();
	}

	public function delete($user_id, $post_id) {
		$this->favorite
					->where('post_id', $post_id)
					->where('user_id', $user_id)
					->delete();
	}

}