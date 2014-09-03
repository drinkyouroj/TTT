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
	 *	Rest route for posting a comment
	 *	@param 
	 */
	public function postRestComment () {
		// Make sure there is authenticated user 
		$user = Auth::check() ? Auth::user() : null;
		
		if ( $user == null || empty($user->id) )  {
			return Response::json( array(
					'error' => 'unauthenticated'
				), 200);
		}
		// Proceed to create the comment
		$comment = $this->comment->create( $user->id, $user->username );
		if ( $comment == null ) {
			// Could not comment due to time time restrictions
			return Response::json( array(
					'error' => 'You must wait at least 10 seconds before commenting'
				), 200);
		}

		$validator = $comment->validate( $comment->toArray() );//validation happens as an array
		if( $validator->passes() ) {
			// Proceed to increment comment count for the given post
	
			$post = $this->post->findById( intval( Request::get( 'post_id' ) ) );
			if($post->user_id != $user->user_id) {
				//Should the comment counter be incremented if you're the owner? no!
				$this->post->incrementComment($post->id);
			}
			//Notification code for new comments
			NotificationLogic::comment($post, $comment);
			
			$is_mod = $user->hasRole('Moderator');

			return Response::json( array(
					'comment' => $comment->toArray(),
					'is_mod' => $is_mod,
					'active_user_id' => $user->id
				), 200);
		} else {

			return Response::json( array(
					'error' => 'invalid input'
				), 200);
		}
	}

	/**
	 * 	The Post Comment Form. Creates a comment, pushes out the proper notifications,
	 *	and then redirects back to the view.
	 */
	public function postCommentForm () {
		$user = Auth::user();
		$user_id = $user->id;
		$username = $user->username;

		//$comment = CommentLogic::comment_object_input_filter();
		$comment = $this->comment->create( $user_id, $username );

		$validator = $comment->validate( $comment->toArray() );//validation happens as an array
		
		if( $validator->passes() ) {
			// Proceed to increment comment count for the given post
			$post = $this->post->findById(Request::segment(3));			
			if($post->user_id != $user_id) {
				//Should the comment counter be incremented if you're the owner? no!
				$this->post->incrementComment($post->id);
			}
			//Notification code for new comments
			NotificationLogic::comment($post, $comment);
			
			return Redirect::to('posts/'.$comment->post->alias.'#comment-'.$comment->id);
		} else {
			return Redirect::to('posts/'.$comment->post->alias)
							->withErrors($validator)
							->withInput();
		}
	}
		
	/**
	 * Creates a form to be injected as a reply.  Not the best method, but we'll improve on this once we go full app for users.
	 */
	public function getCommentForm($post_id, $reply_id = false) 
	{
		$post = $this->post->findById($post_id);
		return View::make('v2/posts/commentform')
				->with('post', $post)
				->with('reply_id', $reply_id);
	}	

}
