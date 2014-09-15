<?php
use AppStorage\Post\PostRepository;
class ProfileImageRestController extends \BaseController {

	public function __construct(PostRepository $post) {
		$this->post = $post;
	}
	
	public function show($id)
	{
		if($id) {
			$user = User::where('id', $id)->select('image')->first();
			
			if($user->image && $image->image != '0') {
				//We'll need to put in enviro detect here for CDN.
				return Redirect::to(Config::get('app.imageurl').'/'.$user->image);
				//return Response::download(public_path().'/uploads/final_images/'.$post->image);
			} else {
				return Redirect::to('/images/profile/avatar-default.png');
			}
		} else {
			return Redirect::to('/images/profile/avatar-default.png');
		}
	}
}