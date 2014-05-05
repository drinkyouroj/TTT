<?php
namespace AppStorage\Notification;

interface NotificationRepository {

	//Instance
	public function instance();

	public function input($user_id);

	//Create
	public function create($input);

	//Read
	public function find($post_id, $user_id, $type);
	
	public function findById($id);
	
	//Read Multi
	public function all();
	public function allDesc($user_id);
	public function limited($user_id);
	
	//Check
	public function check();
	
	//Update
	public function noticed($array,$user_id);

	//Delete
	public function delete($user_id, $username, $post_id, $type);
	
}