<?php
namespace AppStorage\ProfilePost;

interface ProfilePostRepository {

	//Instance
	public function instance();

	//Create
	public function create($data);

	public function findByUserId($user_id, $paginate, $page, $rest);

	//Delete
	public function delete($data);

	public function publish($user_id, $post_id);
}