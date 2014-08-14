<?php
class UserRestController extends \BaseController {

	public function __construct(PostRepository $post) {
		$this->post = $post;
	}
	
	/**
	 * Delete this user.
	 */	
	public function destroy($id) {
		//Was the ID passed and is it the Authenticated user?
		if($id && Auth::user()->id == $id) {
			User::where('id', $id)
				->delete();
			
			//archive all posts by user
			$this->post->archive($id);

			//Add Comment Delete Here also.
			
			return Response::json(
				array('result'=>'success'),
				200//response is OK!
			);
		} else {
			return Response::json(
				array('result'=>'failed'),
				200//response is OK!
			);
		}
	}
}