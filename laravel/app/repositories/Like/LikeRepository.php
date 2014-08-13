<?php
namespace AppStorage\Like;

interface LikeRepository {

	//Instance
	public function instance();

	public function create($user_id, $post_id);

	public function exists($user_id, $post_id);

	public function has_liked($user_id, $post_id);

	public function delete($user_id, $post_id);

}