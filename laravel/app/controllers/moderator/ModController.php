<?php
class ModController extends Controller {

	public function __construct(
								UserRepository $user,
								PostRepository $post,
								CommentRepository $comment
								) {
		$this->user = $user;
		$this->post = $post;
		$this->comment = $comment;
	}


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
}