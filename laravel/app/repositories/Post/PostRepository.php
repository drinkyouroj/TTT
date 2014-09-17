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
	
	/**
	 * Finds a given post by alias. NOTE: includes trashed (soft deleted) posts.
	 */
	public function findByAlias($alias, $published = true);
	
	public function findByUserId($user_id, $published = true);
	
	public function findByTitle($title, $published = true);

	public function findByImage( $image, $with_trashed );
	
	public function lastPostUserId($user_id, $published = true);
	
	public function random();
	
	//Read Multi
	public function all( $with_trashed );
	
	public function allFeatured();
	
	public function allByUserId($user_id, $published = true);
	
	public function allByUserIds($user_ids, $published = true);

	public function allDraftsByUserId($user_id, $paginate, $page, $rest);
	
	public function allByPostIds($post_ids, $published = true);
	
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

	public function deleteAllByUserId($id);
	public function restoreAllByUserId($id);

	// Get all deleted posts for a given user
	public function allDeletedByUserId($user_id);
	// Admin stats
	public function getPublishedCount();
	public function getPublishedTodayCount();
	public function getDraftsTodayCount();
}