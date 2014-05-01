<?php
//This controller is the REST Controller for Checking title names
class PostTitleRestController extends \BaseController {

	public function __construct(PostRepository $post) {
		$this->post = $post;
	}

	//Unfortunately using index since the jquery plugin doesn't seem to know to specify how to do the operation.
	public function index() {
		$post = $this->post->findByTitle(Input::get("title"));
		if(property_exists($post, $id)){
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
		$post = $this->post->findByTitle(Input::get("title"));
		if(property_exists($post, $id)){
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