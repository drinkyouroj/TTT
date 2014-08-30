<?php
namespace AppStorage\Follow;

interface FollowRepository {

	//Instance
	public function instance();

	public function create($user_id, $follower_id);

	//Check
	public function exists($user_id, $follower_id);
	
	public function followersByUserId($user_id);

	public function followingByUserId($user_id);

	public function jsonFollowers($user_id);

	public function jsonFollowing($user_id);

	public function restFollowers($user_id, $page );
	public function restFollowing($user_id, $page );

	public function follower_count($user_id);

	public function following_count($user_id);

	public function is_follower($my_id, $other_id);

	public function is_following($my_id, $other_id);

	public function mutual($my_id, $other_id);
	
	public function mutual_list($my_id);

	//Delete
	public function delete($user_id, $follower_id);
	
}