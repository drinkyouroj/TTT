<?php
//This controller is the REST Controller for Checking title names
class PostTitleRestController extends \BaseController {

	//Unfortunately using index since the jquery plugin doesn't seem to know to specify how to do the operation.
	public function index() {
		if(Post::where('title', '=', Input::get("title"))->count() != 0){
			return Response::json(
				false,
				200//response is OK!
			);
		} else {
			return Response::json(
				true,
				200//response is OK!
			);
		}
	}
	
	
	public function show($id) {
		if(Post::where('title', '=', Input::get("title"))->count() != 0){
			return Response::json(
				false,
				200//response is OK!
			);
		} else {
			return Response::json(
				true,
				200//response is OK!
			);
		}
	}
	
}