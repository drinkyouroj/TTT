<?php namespace AppStorage\Category;

use Category,DB, Request;

class EloquentCategoryRepository implements CategoryRepository {

	public function __construct(
							Category $category
							) {
		$this->category = $category;
	}

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
		return $this->category->all();
	}
	

	//Update
	public function update($input) {

	}
	
	public function publish($category_id, $user_id) {}
	public function unpublish($category_id, $user_id) {}
	
	//Delete
	public function delete($id) {}
}