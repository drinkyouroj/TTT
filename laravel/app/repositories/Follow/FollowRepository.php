<?php
namespace AppStorage\Follow;

interface FollowRepository {

	//Instance
	public function instance();

	public function create($user_id, $follower_id);

	//Check
	public function exists($user_id, $follower_id);
	
	public function followers($user_id);

	//Delete
	public function delete($user_id, $follower_id);
	
}