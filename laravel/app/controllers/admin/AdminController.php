<?php

class AdminController extends Controller {
	
	public function __construct(
						UserRepository $user,
						PostRepository $post,
						CommentRepository $comment,
						FeaturedRepository $featured
						) {
		$this->user = $user;
		$this->post = $post;
		$this->comment = $comment;
		$this->featured = $featured;
	}


	/**
	 *	Assign the Moderator role to a given user.
	 */
	function assignModerator( $user_id ) {
		$mod = Role::where('name','Moderator')->first();
		$user = $this->user->find( $user_id );
		$success = false;
		if ( $user instanceof User ) {
			$user->attachRole( $mod );
			$success = true;
		}
		return Response::json(
				array( 'success' => $success ),
				200 );
	}

	/**
	 *	Set a posts featured position
	 */
	function setFeaturedPosition ( $post_id, $position ) {
		$featured_item = $this->featured->findByPostId( $post_id );
		if ( $featured_item ) {
			if ( $featured_item->front ) {
				// Case 1: The item is already on the front page, so changing its position means
				//			swapping with the existing item.
				$this->featured->swapFeaturedItems ( $post_id, $position );
				$case = 1;
			} else {
				// Case 2: The item has been featured, but is not on the front. 
				//			Erase it and set it to front page.
				$this->featured->delete( $post_id );
				$this->featured->create( $post_id, $position );
				$case = 2;
			}
		} else {
			// Case 3: The item has not been featured.
			// This post was not already featured, just set its position/front
			$this->featured->create( $post_id, $position );
			$case = 3;
		}
		return Response::json(
				array( 'success' => true, 'case' => $case ),
				200 );
	}

	/**
	 *	Remove a post from the featured page
	 */
	function removeFromFeatured ( $post_id ) {
		$this->featured->delete( $post_id );
		return Response::json(
				array( 'success' => true ),
				200 );	
	}
}