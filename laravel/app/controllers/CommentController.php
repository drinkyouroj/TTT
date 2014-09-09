<?php
/**
 * Comments Class handles all comment related issues.
 */
class CommentController extends BaseController {

	public function __construct(
								PostRepository $post, 
								CommentRepository $comment
								) {
		$this->post = $post;
		$this->comment = $comment;
	}

	/**
	 *	Get comments via the rest route...
	 *	@param post_id: the post id
	 *	@param paginate: number of comments to pull
	 *	@param page: which page of comments to get
	 */
	public function getRestComments ( $post_id, $paginate = 5, $page = 1 ) {
		$comments = $this->comment->findByPostId( $post_id, $paginate, $page );
		if ( count( $comments ) ) {
			if ( Auth::check() ) {
				$user = Auth::user();
				$is_mod = $user->hasRole('Moderator');
				$active_user_id = $user->id;
			} else {
				$is_mod = false;
				$active_user_id = false;
			}
			

			return Response::json(
					array('comments'=> $comments->toArray(),
						  'is_mod' => $is_mod,
						  'active_user_id' => $active_user_id ),
					200//response is OK!
				);
		} else {
			return Response::json(
					array('comments'=>array()),
					200//response is OK!
				);
		}
	}

	/**
	 *	Retrieve all comments up until the target comment with id == $comment_id
	 *	is reached. This allows us to save/view specific comments while keeping
	 *	things dynamic. If the comment id is not found, all comments are returned.
	 *	@param $post_id: the post id in which the comment refers
	 *	@param $comment_id: the target comment
	 */
	public function getDeepLinkedComments ( $post_id, $comment_id ) {
		// Basically we keep pulling comments in order until we reach the target comment, then
		// send back the current pagination info so that we can pick up at the right point on
		// the front end. 
		$paginate = 10;

		$result = $this->comment->findByCommentAndPostId( $comment_id, $post_id, $paginate );
		if ( $result ) {
			if ( Auth::check() ) {
				$user = Auth::user();
				$is_mod = $user->hasRole('Moderator');
				$active_user_id = $user->id;
			} else {
				$is_mod = false;
				$active_user_id = false;
			}
			$result['is_mod'] = $is_mod;
			$result['active_user_id'] = $active_user_id;
			return Response::json( $result, 200 );
		} else {
			return Response::json( array( 'error' => true ), 200 );
		}

	}

	/**
	 *	Rest route for posting a comment
	 *	@param 
	 */
	public function postRestComment () {
		// We can assume there is an authenticated user at this point (route filter)
		$user = Auth::user();
		// Proceed to create the comment
		$reply_id = Input::has('reply_id') ? Input::get('reply_id') : null;
		$post_id = Input::has('post_id') ? Input::get('post_id') : null;
		$comment_body = Input::has('body') ? Input::get('body') : null;

		$comment = $this->comment->create( $user->id, $user->username, $reply_id, $post_id, $comment_body );
		if ( $comment == null ) {
			// Could not comment due to time time restrictions
			// NOTE: $comment == null could mean several things (invalid comment, failed save)
			return Response::json( array(
					'error' => 'You must wait at least 10 seconds before commenting'
				), 200);
		} else {
			// Proceed to increment comment count for the given post
			$post = $this->post->findById( intval( $post_id ) );
			if($post->user_id != $user->user_id) {
				//Should the comment counter be incremented if you're the owner? no!
				$this->post->incrementComment( $post->id );
			}
			//Notification code for new comments
			NotificationLogic::comment($post, $comment);
			
			$is_mod = $user->hasRole('Moderator');

			return Response::json( array(
					'comment' => $comment->toArray(),
					'is_mod' => $is_mod,
					'active_user_id' => $user->id
				), 200);
		}
	}

	/**
	 *	Like a comment
	 *	@param $comment_id: the comment id
	 */
	public function likeComment ( $comment_id ) {
		$success = $this->comment->like( Auth::user()->id, $comment_id );
		return Response::json(
			array( 'success' => $success ),
			200);
	}

	/**
	 *	Unlike a comment
	 *	@param $comment_id: the comment id
	 */
	public function unlikeComment ( $comment_id ) {
		$success = $this->comment->unlike( Auth::user()->id, $comment_id );
		return Response::json( 
			array( 'success' => $success ), 
			200);
	}

	/**
	 *	Flag a comment
	 *	@param $comment_id: the comment id
	 */
	public function flagComment ( $comment_id ) {
		$success = $this->comment->flag( Auth::user()->id, $comment_id );
		return Response::json( 
			array( 'success' => $success ), 
			200);
	}

	/**
	 *	Unflag a comment
	 *	@param $comment_id: the comment id
	 */
	public function unflagComment ( $comment_id ) {
		$success = $this->comment->unflag( Auth::user()->id, $comment_id );
		return Response::json(
			array( 'success' => $success ), 
			200);
	}
}
