<?php namespace AppStorage\Category;

use Category,DB, Request;

class EloquentCategoryRepository implements CategoryRepository {

	//Instance
	public function instance() {
		return new Category;
	}


	//Create
	public function create($input) {

	}

	//Read
	public function findById($id) {

	}
	
	//Read Multi
	public function all() {

	}
	

	//Update
	public function update($input) {

	}
	
	public function publish($comment_id, $user_id) {}
	public function unpublish($comment_id, $user_id) {}
	
	//Delete
	public function delete($id) {}
}