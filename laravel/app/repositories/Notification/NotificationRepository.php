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
	
	//Check
	public function check();

	//Delete
	public function delete($id);
	
}