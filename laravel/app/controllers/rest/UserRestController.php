<?php
class UserRestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{}
	
	/**
	 * Delete this user.
	 */	
	public function destroy($id) {
		//Was the ID passed and is it the Authenticated user?
		if($id && Auth::user()->id == $id) {
			User::where('id', $id)
				->delete();
			
			Post::where('user_id', $id)
				->update(
					array('published'=>0)
					);
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