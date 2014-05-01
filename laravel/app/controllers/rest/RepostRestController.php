<?php
class RepostRestController extends \BaseController {
	
	public function __construct(PostRepository $post) {
		$this->post = $post;
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show()
	{
		if(Request::segment(3) != 0) {
			$exists = Repost::where('post_id', '=', Request::segment(3))
							->where('user_id', '=', Auth::user()->id)
							->count();
			$owns = $this->post->owns(Request::segment(3), Auth::user()->id);
			
			if(!$exists && !$owns) {//Doesn't exists and you don't own it.
				//Crete a new repost
				$repost = new Repost;
				$repost->post_id = Request::segment(3);
				$repost->user_id = Auth::user()->id;//Gotta be from you.
				$repost->save();
									
				$post = $this->post->findById(Request::segment(3));
				
				//TODO get rid of the original notifcation code soon.
				$notification_exists = Notification::where('post_id', '=', Request::segment(3))
									->where('action_id', '=', Auth::user()->id)
									->count();
								
				if(!$notification_exists){	
					//Add to the OP's notification
					$notification = new Notification;
					$notification->post_id = Request::segment(3);
					$notification->user_id = $post->user_id;
					$notification->action_id = Auth::user()->id;
					$notification->notification_type = 'repost';
					$notification->save();
				}
				
				$mot = Motification::where('post_id', Request::segment(3))//Post id									
									->where('user_id', $post->user->id)//person getting notified
									->where('notification_type', 'repost');
				
				if(!$mot->count()) {
					$mot = new Motification;
					$mot->post_id = Request::segment(3);
					$mot->post_title = $post->title;
					$mot->post_alias = $post->alias;
					$mot->user_id = $post->user->id;//Who this notification si going to.
					$mot->noticed = 0;
					$mot->notification_type = 'repost';
					$mot->save();
				}
				$mot->push('users', Auth::user()->username,true);

				
				//Add to follower's notifications
				Queue::push('UserAction@repost', 
							array(
								'post_id' => Request::segment(3),
								'user_id' => Auth::user()->id,
								'username' => Auth::user()->username,
								)
							);
				
				//This has to be outside 
				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			} elseif($exists) {//Relationship already exists
				
				Repost::where('post_id', '=', Request::segment(3))
					->where('user_id', '=', Auth::user()->id)
					->delete();
				
				//Delrepost handles the rest of it.
								
				Queue::push('UserAction@delrepost', 
							array(
								'post_id' => Request::segment(3),
								'user_id' => Auth::user()->id,
								'username' => Auth::user()->username,
								)
							);
				
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

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}