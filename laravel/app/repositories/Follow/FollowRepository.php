<?php
namespace AppStorage\Follow;

interface FollowRepository {

	//Instance
	public function instance();

	//Check
	public function exists($follow_id, $user_id);
	
	//Delete
	public function delete($id);
	
}