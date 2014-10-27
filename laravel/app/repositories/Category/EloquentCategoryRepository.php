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
	public function create( $title, $description ) {

		$category = new Category;
		$category->title = $title;
		$category->alias = preg_replace('/[^A-Za-z0-9]/', '', $title );
		$category->description = $description;

		$validator = $category->validate( $category->toArray() );
		if ( !$validator->fails() ) {
			// dd($category);
			$category->save();
		}
	}

	//Read
	public function findById($id) {
		return $this->category->where('id', '=', $id)->first();
	}
	
	//Read Multi
	public function all() {
		return $this->category->all();
	}
	

	//Update
	public function update($input) {
		$new_title = isset($input['new_title']) ? $input['new_title'] : null;
		$new_description = isset($input['new_description']) ? $input['new_description'] : null;
		$category_alias = isset($input['category_alias']) ? $input['category_alias'] : null;
		
		$category = $this->category->where('alias', '=', $category_alias)->first();
		if ( $category instanceof Category ) {
			if ( $new_title != null ) {
				$category->title = $new_title;
			}
			if ( $new_description != null ) {
				$category->description = $new_description;
			}
			$category->save();
			return true;
		} else {
			return false;
		}
	}
	
	public function publish($category_id, $user_id) {}
	public function unpublish($category_id, $user_id) {}
	
	//Delete
	public function delete($id) {}
}