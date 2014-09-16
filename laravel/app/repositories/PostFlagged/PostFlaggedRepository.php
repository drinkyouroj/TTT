<?php
namespace AppStorage\PostFlagged;

interface PostFlaggedRepository {

	//Instance
	public function instance();

	public function create($user_id, $post_id);
	public function delete($user_id, $post_id);

	public function count($post_id);

	public function exists($user_id, $post_id);
}