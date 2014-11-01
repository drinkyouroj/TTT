<?php

class AdminController extends Controller {
	
	public function __construct(
						UserRepository $user,
						PostRepository $post,
						CommentRepository $comment,
						FeaturedRepository $featured,
						EmailRepository $email,
						CategoryRepository $category,
						FeaturedUserRepository $featureduser,
						PromptRepository $prompt ) {
		$this->user = $user;
		$this->post = $post;
		$this->comment = $comment;
		$this->featured = $featured;
		$this->email = $email;
		$this->category = $category;
		$this->featureduser = $featureduser;
		$this->prompt = $prompt;
	}

	function getPrompts () {
		$prompts = $this->prompt->getAll();
		$view = View::make('v2/admin/prompts');
		$view->with('prompts', $prompts);
		return $view;
	}

	function createPrompt () {
		$body = Input::has('body') ? Input::get('body') : false;
		$link = Input::has('link') ? Input::get('link') : false;
		$data = array(
			'body' => $body,
			'link' => $link
		);
		// Create the prompt
		$this->prompt->create( $data );
		return Redirect::action('AdminController@getPrompts'); 
	}

	function deletePrompt () {
		$prompt_id = Input::has('prompt_id') ? Input::get('prompt_id') : false;
		if ( $prompt_id ) {
			$this->prompt->delete( $prompt_id );
		}
		return Response::json( array( 'success' => true ), 200 );
	}

	function togglePromptActive () {
		$prompt_id = Input::has('prompt_id') ? Input::get('prompt_id') : false;
		$active = Input::has('active') ? Input::get('active') : false;
		if ( $active == 'true' ) {
			$this->prompt->activate( $prompt_id );
		} else {
			$this->prompt->deactivate( $prompt_id );
		}
		return Response::json( array( 'success' => true ), 200 );
	}

	/**
	 *	Submit the weekly digest
	 */
	function sendWeeklyDigest () {
		$featured_post = Input::has('digest_featured_post') ? Input::get('digest_featured_post') : false;
		$post_2 = Input::has('digest_post_2') ? Input::get('digest_post_2') : false;
		$post_3 = Input::has('digest_post_3') ? Input::get('digest_post_3') : false;
		$post_4 = Input::has('digest_post_4') ? Input::get('digest_post_4') : false;
		$post_5 = Input::has('digest_post_5') ? Input::get('digest_post_5') : false;

		if ( $featured_post && $post_2 && $post_3 && $post_4 && $post_5 ) {
			// Check to make sure that all 5 posts are valid!
			$featured_post = $this->post->findByAlias( $featured_post );
			$post_2 = $this->post->findByAlias( $post_2 );
			$post_3 = $this->post->findByAlias( $post_3 );
			$post_4 = $this->post->findByAlias( $post_4 );
			$post_5 = $this->post->findByAlias( $post_5 );

			if ( $featured_post && $post_2 && $post_3 && $post_4 && $post_5 ) {
				// Proceed to send the weekly newsletter
				Queue::push('AdminAction@weeklyDigest', 
								array(
									'featured_post' => $featured_post,
									'post_2' => $post_2,
									'post_3' => $post_3,
									'post_4' => $post_4,
									'post_5' => $post_5
									)
								);

				return Response::json( array( 'success' => true ), 200);
			} else {
				// Invalid post alias
				return Response::json( array( 'error' => 'Invalid alias' ), 200);
			}

		} else {
			// Invalid input params
			return Response::json( array( 'error' => 'Invalid parameters' ), 200);
		}
	}

	/**
	 *	Set a post to be part of the weekly digest. For now this is just stored
	 *	in a session, can be stored in db later on if necassary.
	 */
	function setDigestPost () {
		$position = Input::has('position') ? Input::get('position') : false;
		$alias = Input::has('alias') ? Input::get('alias') : false;
		if ( $position != false && $alias != false ) {
			// Stuff in session
			Session::put( $position, $alias );
			return Response::json( array( 'success' => true ), 200);
		} else {
			return Response::json( array( 'error' => true ), 200);
		}
	}

	/**
	 *  Post is NSFW-IYWSCE (not safe for work if you work at a shitty corporate environment)
	 */
	function setNSFW($post_id) {
		$post = $this->post->findById($post_id);
		if(is_object($post)) {
			if($post->nsfw){
				$this->post->unsetNSFW($post_id);
				return Response::json(
					array( 'nsfw' => false),
					200
				);
			} else {
				$this->post->setNSFW($post_id);
				return Response::json(
					array( 'nsfw' => true),
					200
				);
			}
		} else {
			return Response::json(
					array( 'failed' => true),
					200
				);
		}
	}

	function updatePostViewCount () {
		$new_count = Input::has('new_count') ? Input::get('new_count') : -1;
		$post_id = Input::has('post_id') ? Input::get('post_id') : -1;
		$post = $this->post->findById( $post_id );
		if ( $new_count < 0 || !($post instanceof Post) ) {
			return Response::json( array( 'error' => 'invalid input' ), 200 );
		} else {
			$this->post->updateViewCount( $post_id, $new_count );
			// NOTE: I coppied this code from PostController
			$intervals = array(10, 25, 50, 100, 250, 500, 1000, 2500, 5000, 10000, 25000, 50000);
			if( in_array($new_count, $intervals) ) {
				//Send the user a notification on the system.
				NotificationLogic::postview($post_id);
				if($post->useremail->email) {
					EmailLogic::post_view($post, $new_count);
				}
			}
			return Response::json( array( 'success' => true ), 200 );
		}
	}

	// Think the funciton name explains it all
	function addRandomViewCountsToAllPosts () {
		Queue::push('AdminAction@addRandomViewCounts', array() );
		return Response::json( array( 'success' => true ), 200);
	}

	function createCategory () {
		$new_category_name = Input::has('new_category_name') ? Input::get('new_category_name') : false;
		$new_category_description = Input::has('new_category_description') ? Input::get('new_category_description') : false;
		if ( $new_category_name && $new_category_description ) {
			$this->category->create( $new_category_name, $new_category_description );
			return Response::json( array( 'success' => true ), 200 );
		} else {
			return Response::json( array( 'error' => 'invalid input' ), 200 );
		}
	}

	/**
	 *	Edit a category description
	 */
	function editCategoryDescription () {
		$category_alias = Input::has('category_alias') ? Input::get('category_alias') : false;
		$new_description = Input::has('new_description') ? Input::get('new_description') : false;
		$new_title = Input::has('new_title') ? Input::get('new_title') : false;
		if ( $category_alias && ($new_description || $new_title) ) {
			$input = array( 'category_alias' => $category_alias );
			if ( $new_description ) {
				$input['new_description'] = $new_description;
			}
			if ( $new_title && (strtolower($new_title) != 'new'|| strtolower($new_title) != 'all'))  {
				$input['new_title'] = $new_title;
			}
			$result = $this->category->update( $input );
			return Response::json( array( 'success' => $result ), 200 );
		} else {
			return Response::json( array( 'success' => false ), 200 );
		}
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
		$post = $this->post->findById( $post_id );
		if ( $body ) { $post->body = $body; }
		if ( $title ) { $post->title = $title; }
		if ( $tagline_1 ) { $post->tagline_1 = $tagline_1; }
		if ( $tagline_2 ) { $post->tagline_2 = $tagline_2; }
		if ( $tagline_3 ) { $post->tagline_3 = $tagline_3; }
		$post->save();
		return Response::json( array( 'success' => true ), 200 );
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

	//Set the fucking featured user
	function setFeaturedUser() {
		$request = Request::all();
		$result = $this->featureduser->create($request['user_id'], $request['excerpt']);
		if($result) {
			return Response::json(
					array('success' => true),
					200
				);
		} else {
			return Response::json(
					array('error'),
					400
				);
		}
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