<?php
use AppStorage\Post\PostRepository;
class ProfileImageRestController extends \BaseController {

	public function __construct(PostRepository $post) {
		$this->post = $post;
	}
	
	public function show($id)
	{
		if($id) {
			$user_featured = User::where('id', $id)->select('featured')->first()->featured;
			
			if(!$user_featured) {
				return Response::download(public_path().'/img/profile/default-profile.jpg');
			}
			
			$post = $this->post->findById($user_featured);
			
			if($post->image) {
				return Response::download(public_path().'/uploads/final_images/'.$post->image);
			}
		} else {
			return Response::download(public_path().'/img/profile/default-profile.jpg');
		}
	}
}