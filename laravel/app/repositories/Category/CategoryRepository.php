<?php
namespace AppStorage\Category;

interface CategoryRepository {

	//Instance
	public function instance();

	//Create
	public function create( $title, $description );

	//Read
	public function findById($id);
	
	//Read Multi
	public function all();
	

	//Update
	public function update($input);
	
	public function publish($category_id, $user_id);
	public function unpublish($category_id, $user_id);
	
	//Delete
	public function delete($id);
	
}