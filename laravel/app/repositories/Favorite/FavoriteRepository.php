<?php
namespace AppStorage\Favorite;

interface FavoriteRepository {

	//Instance
	public function instance();

	//Create
	public function create($user_id, $post_id);

	public function allByUserId($user_id, $paginate);

	public function exists($user_id, $post_id);

	public function has_favorited($user_id, $post_id);

	public function delete($user_id, $post_id);
}