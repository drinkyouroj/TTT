<?php
namespace AppStorage\Post;

interface PostRepository {

	//Instance
	public function instance();

	//Create
	public function create($input);

	public function input();

	//Read
	public function findById($id, $published = true, $replationships = array());
	
	public function findByAlias($alias, $published = true);
	
	public function findByUserId($user_id, $published = true);
	
	public function findByTitle($title, $published = true);
	
	public function lastPostUserId($user_id, $published = true);
	
	public function random();
	
	//Read Multi
	public function all();
	
	public function allFeatured();
	
	public function allByUserId($user_id, $published = true);
	
	public function allByUserIds($user_ids, $published = true);
	
	//Count
	public function countPublished();
	
	//Check
	public function owns($post_id, $user_id);

	public function checkEditable($published_at);

	//Update
	public function update($input);
	
	public function publish($id);
	public function unpublish($id);
	
	public function restore($user_id);
	public function archive($user_id);
	
	public function incrementView($alias);
	
	public function incrementComment($id);
	
	public function incrementLike($id);
	public function decrementLike($id);
	
	//Delete
	public function delete($id);
	public function undelete($id);
	
}