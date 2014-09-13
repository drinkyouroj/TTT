<?php
namespace AppStorage\Search;

interface SearchRepository {

	public function searchPosts( $search_string, $page, $paginate );
	
	public function searchUsers( $search_string, $page, $paginate );
	
	/**
	 *	Indexes the given post. Update or create if already existing.
	 */
	public function updatePost( $post );
	
	/**
	 *	Indexes the given user. Update or create if already existing.
	 */
	public function updateUser( $user );

	public function deletePost( $post );

	public function deleteUser( $user );

}