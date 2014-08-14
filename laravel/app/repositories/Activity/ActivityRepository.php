<?php
namespace AppStorage\Activity;

interface ActivityRepository {

	//Instance
	public function instance();

	//Create
	public function create($data);

	//Read
	public function findByUserId($user_id, $paginate, $page, $rest);
	
	//Read Multi
	public function all();
	

	//Update
	public function update($input);
	
	//Delete

	public function delete($data);

	public function deleteAll($user_id, $post_id);
	
}