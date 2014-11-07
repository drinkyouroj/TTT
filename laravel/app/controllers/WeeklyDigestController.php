<?php

class WeeklyDigestController extends BaseController {

	public function __construct( WeeklyDigestRepository $digest, PostRepository $post ) {
		$this->digest = $digest;
		$this->post = $post;
	}

	/*
	 *	Admin view for digest statistics
	 */
	public function getWeeklyDigests () {
		$digests = $this->digest->all( true );
		return View::make( 'v2/admin/digests' )->with( 'digests', $digests );
	}

	/*
	 *	Route links in the mail to proper place after keeping track of it.
	 *	NOTE: currently do not track clicks to user profiles
	 */
	public function routeEmailLink ( $digest_id, $action, $alias ) {
		if ( $action == 'user' ) {
			return Redirect::to( 'profile/'.$alias );
		} else if ( $action == 'post' ) {
			$this->digest->incrementViews( $digest_id, $alias );
			// Need the post alias
			return Redirect::to( 'posts/'.$alias );
		}
	}

	/*
	 *	Get weekly digest from db
	 */
	public function getWeeklyDigest () {
		$weekly_digest = $this->digest->getWeeklyDigest();
		if ( $weekly_digest instanceof WeeklyDigest ) {
			return $weekly_digest;
		} else {
			return false;
		}
	}

	/*
	 *	Send out the weekly digest
	 */
	public function sendWeeklyDigest () {
		// Check that we have all required parameters...
		$weekly_digest = $this->digest->getWeeklyDigest();
		if ( $weekly_digest instanceof WeeklyDigest ) {
			$valid = true;
			$posts = array(5);
			for( $i = 0; $i < count( $weekly_digest->posts ); $i++ ) {
				if ( $weekly_digest->posts[$i]['post_alias'] != '' ) {
					$posts[$i] = $this->post->findByAlias( $weekly_digest->posts[$i]['post_alias'] );
				} else {
					// No post alias set
					$valid = false;
					break;
				}
			}

			if ( $valid ) {
				$weekly_digest->sent = true;
				$weekly_digest->save();
				Queue::push('AdminAction@weeklyDigest',
								array(
									'featured_post' => $posts[0],
									'post_2' => $posts[1],
									'post_3' => $posts[2],
									'post_4' => $posts[3],
									'post_5' => $posts[4]
									)
								);
				return Response::json( array( 'success' => true ), 200);
			} else {
				return Response::json( array( 'error' => 'Missing post. Requires all 5 posts.' ), 200);
			}
		} else {
			// Fail at life
			return Response::json( array( 'error' => 'Hmmm... Fail?' ), 200 );
		}
	}

	/*
	 *	Add a post to this weeks digest
	 */
	public function addPostToDigest () {
		$post_alias = Input::has('post_alias') ? Input::get('post_alias') : null;
		$position = Input::has('position') ? Input::get('position') : null;
		if ( $position != null && $post_alias != null ) {
			$this->digest->addPost( $post_alias, $position );
			return Response::json( array( 'success' => true ), 200 );	
		} else {
			return Response::json( array( 'success' => false ), 200 );	
		}
		
	}

}