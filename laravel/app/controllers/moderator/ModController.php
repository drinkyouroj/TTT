<?php
class ModController extends Controller {

	public function __construct(
								UserRepository $user,
								PostRepository $post,
								CommentRepository $comment,
								CategoryRepository $category
								) {
		$this->user = $user;
		$this->post = $post;
		$this->comment = $comment;
	}


	/**
	 *	Delete a given post
	 */
	function deletePost ( $post_id ) {
		$this->post->delete( $post_id );
		return Response::json(
				array( 'success' => true ),
				200 );
	}

	/**
	 *	Delete a given post
	 */
	function undeletePost ( $post_id ) {
		$this->post->undelete( $post_id );
		return Response::json(
				array( 'success' => true ),
				200 );
	}

	/**
	 *	Delete a category from given post
	 */
	function deletePostCategory ( $post_id, $category_id ) {
		$post = $this->post->findById( $post_id );
		$success = false;
		if ( $post instanceof Post ) {
			$post->categories()->detach( $category_id );
			$success = true;
		}
		return Response::json(
			array( 'success' => $success ),
			200 );
	}

	
}