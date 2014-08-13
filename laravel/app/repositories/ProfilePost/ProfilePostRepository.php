<?php
namespace AppStorage\ProfilePost;

interface ProfilePostRepository {

	//Instance
	public function instance();

	//Create
	public function create($user_id, $post, $type);

	//Delete
	public function delete($user_id, $post, $type);
}