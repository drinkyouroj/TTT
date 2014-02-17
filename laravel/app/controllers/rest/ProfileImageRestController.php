<?php
class ProfileImageRestController extends \BaseController {

	public function index()
	{

		
	}
	
	public function show($id)
	{
		if($id) {
			$user_featured = User::where('id', $id)->select('featured')->first()->featured;
			
			if(!$user_featured) {
				return Response::download(public_path().'/img/profile/default-profile.jpg');
			}
			
			$post = Post::where('id', $user_featured)->select('image')->first();
			
			if($post->image) {
				return Response::download(public_path().'/uploads/final_images/'.$post->image);
			}
		} else {
			return Response::download(public_path().'/img/profile/default-profile.jpg');
		}
	}
}