<?php
namespace AppStorage\Follow;

interface FollowRepository {

	//Instance
	public function instance();

	//Check
	public function exists($user_id, $follower_id);
	
	//Delete
	public function delete($user_id, $follower_id);
	
}