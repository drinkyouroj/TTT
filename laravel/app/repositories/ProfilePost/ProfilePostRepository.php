<?php
namespace AppStorage\ProfilePost;

interface ProfilePostRepository {

	//Instance
	public function instance();

	//Create
	public function create($data);

	public function findByUserId($user_id, $type, $paginate, $page, $rest);

	//Delete
	public function delete($data);
	public function deleteAllByPostId($post_id);
	public function restoreAllByPostId($post_id);

	public function publish($user_id, $post_id);
}