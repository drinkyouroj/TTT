<?php
namespace AppStorage\Comment;

interface CommentRepository {

	//Instance
	public function instance();

	public function input($user_id);

	//Create
	public function create($input);

	//Read
	public function findById($id);
	
	//Read Multi
	public function all();
	
	//Check
	public function owns($comment_id, $user_id);

	//Update
	public function update($input);
	
	public function publish($comment_id, $user_id);
	public function unpublish($comment_id, $user_id);
	
	//Delete
	public function delete($id);
	
}