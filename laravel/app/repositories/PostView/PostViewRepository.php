<?php
namespace AppStorage\PostView;

interface PostViewRepository {

	//Instance
	public function instance();

	public function create($data);

	public function exists($user_id, $post_id);
}