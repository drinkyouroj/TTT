<?php
class AdminController extends Controller {
	
	public function __construct() {
		
	}
	
	public function getIndex() {
		$ryuhei = User::where('username', '=', 'ryuhei')
				->first();
		
		
	}
	
	//Below sets a certain 
	public function getFeature() {
		$feature_id = Request::segment(3);
		$post = Post::where('id', '=', $feature_id)->first();
		
		//flip the featured
		if($post->featured) {
			$post->featured = false;
			$post->featured_date = date('Y-m-d');
		} else {
			$post->featured = true;
			$post->featured_date = date('Y-m-d');
		}
		$post->save();
		
		return Response::json(
			array(
				'status' => $post->featured 
			),
			200//response is OK!
		);
		
	} 
	
}