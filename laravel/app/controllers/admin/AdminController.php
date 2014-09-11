<?php

class AdminController extends Controller {
	
	public function __construct(
						UserRepository $user,
						PostRepository $post,
						CommentRepository $comment,
						FeaturedRepository $featured,
						EmailRepository $email ) {
		$this->user = $user;
		$this->post = $post;
		$this->comment = $comment;
		$this->featured = $featured;
		$this->email = $email;
	}

	/**
	 *	Edit a post body, title, and/or taglines
	 */
	function editPost () {
		$post_id = Input::has('post_id') ? Input::get('post_id') : false;
		$body = Input::has('body') ? Input::get('body') : false;
		$title = Input::has('title') ? Input::get('title') : false;
		$tagline_1 = Input::has('tagline_1') ? Input::get('tagline_1') : false;
		$tagline_2 = Input::has('tagline_2') ? Input::get('tagline_2') : false;
		$tagline_3 = Input::has('tagline_3') ? Input::get('tagline_3') : false;
		// Trusting admins at this point, skip validation.
		$post = $this->post->findByPostId( $post_id );
		if ( $body ) { $post->body = $body; }
		if ( $title ) { $post->title = $title; }
		if ( $tagline_1 ) { $post->tagline_1 = $tagline_1; }
		if ( $tagline_2 ) { $post->tagline_2 = $tagline_2; }
		if ( $tagline_3 ) { $post->tagline_3 = $tagline_3; }
		$post->save();
		return Response::json( array( 'success', true ), 200 );
	}

	/**
	 *	Reset a user account. This includes the following:
	 *		password reset
	 *		email to user
	 *		no longer considered 'landing pre-signup'
	 */
	function resetUser ( $user_id ) {
		$results = $this->user->resetPassword( $user_id );
		if ( $results ) {
			// Send the appropriate email
			$data = array(
				'to' => array( $results['user']->email ),
				'subject' => 'Reset Password',
				'plaintext' => 'Your password has been reset to '.$results['new_password'].'.',
				'html' => '<p>Your password has been reset to '.$results['new_password'].'.</p>'
			);
			$this->email->create( $data );
			return Response::json( array( 'success' => true ), 200);
		} else {
			return Response::json( array( 'success' => false ), 200);
		}
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
			$user->save();
			$success = true;
		}
		return Response::json(
				array( 'success' => $success ),
				200 );
	}

	/**
	 *	Remove the Moderator role from a given user.
	 */
	function unassignModerator( $user_id ) {
		$mod = Role::where('name','Moderator')->first();
		$user = $this->user->find( $user_id );
		$success = false;
		if ( $user instanceof User ) {
			$user->detachRole( $mod );
			$user->save();
			$success = true;
		}
		return Response::json(
				array( 'success' => $success ),
				200 );
	}

	/**
	 *	Delete a user
	 */
	function deleteUser ( $user_id ) {
		$this->user->delete( $user_id );
		return Response::json(
				array( 'success' => true ),
				200 );
	}

	/**
	 *	Restore a deleted user
	 */
	function restoreUser ( $user_id ) {
		$success = $this->user->restore( $user_id );
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