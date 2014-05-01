<?php
class FavoriteRestController extends \BaseController {

	public function __construct(PostRepository $post) {
		$this->post = $post;
	}

	public function index()
	{
		
	}

	public function store()
	{
		
	}
	
	//not the best usage (using get), but this works
	public function show()
	{
		if(Request::segment(3) != 0) {
			$exists = Favorite::where('post_id', '=', Request::segment(3))
							->where('user_id', '=', Auth::user()->id)
							->count();
							
			$owns = $this->post->owns(Request::segment(3), Auth::user()->id);
			
			if(!$exists && !$owns) {//Relationship doesn't exist and the user doesn't own this.
				//Crete a new follow
				$favorite = new Favorite;
				$favorite->post_id = Request::segment(3);
				$favorite->user_id = Auth::user()->id;//Gotta be from you.
				$favorite->save();
				
				$post = $this->post->findById(Request::segment(3));
				
				//Add to activity
				$profilepost = new ProfilePost;
				$profilepost->post_id = Request::segment(3);
				$profilepost->profile_id = Auth::user()->id;
				$profilepost->user_id = $post->user->id;
				$profilepost->post_type = 'favorite';
				$profilepost->save();
				
				
				//TODO get rid of the original notifcation code soon.
				$notification = new Notification;
				$notification->post_id = Request::segment(3);
				$notification->user_id = $post->user->id;
				$notification->action_id = Auth::user()->id;
				$notification->notification_type = 'favorite';
				$notification->save();
				
				
				$mot = Motification::where('post_id', Request::segment(3))//Post id									
									->where('user_id', $post->user->id)//person getting notified
									->where('notification_type', 'favorite');
				
				if(!$mot->count()) {
					$mot = new Motification;
					$mot->post_id = Request::segment(3);
					$mot->post_title = $post->title;
					$mot->post_alias = $post->alias;
					$mot->user_id = $post->user->id;//Who this notification si going to.
					$mot->noticed = 0;
					$mot->notification_type = 'favorite';
					$mot->save();
				}
				$mot->push('users', Auth::user()->username,true);
				
				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			
			} elseif($exists) {//Relationship already exists, should this be an unfavorite?
			
				$post = $this->post->findById(Request::segment(3));
			
				//Delete from Favorites
				Favorite::where('post_id', '=', Request::segment(3))
						->where('user_id', '=', Auth::user()->id)
						->delete();
				
				//Delete from My Posts
				ProfilePost::where('profile_id', '=', Auth::user()->id)
						->where('post_id', '=', Request::segment(3))
						->where('user_id', '=', $post->user->id)
						->delete();
				
				//Delete from Notifications
				//TODO get rid of the original notifcation code soon.
				Notification::where('post_id', '=', Request::segment(3))
						->where('action_id', '=', Auth::user()->id)
						->where('user_id', '=', $post->user->id)
						->where('notification_type', '=', 'favorite')
						->delete();
				
				$mot = Motification::where('post_id', Request::segment(3))
						->where('notification_type', 'favorite')
						->where('user_id',$post->user->id);
				if($mot->count() >= 1) {
					$mot->pull('users', Auth::user()->username);
					if(count($mot->first()->users) == 0) {
						$mot->delete();
					}
				}
	
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			}
		}
		return Response::json(
			array('result'=>'fail'),
			200//response is OK!
		);
	}

}