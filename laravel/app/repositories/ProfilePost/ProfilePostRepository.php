<?php
namespace AppStorage\ProfilePost;

interface ProfilePostRepository {

	//Instance
	public function instance();

	//Create
	public function create($user_id, $post, $type);

	public function findByUserId($user_id, $paginate, $page, $rest);

	//Delete
	public function delete($user_id, $post, $type);

	public function publish($user_id, $post_id);
}