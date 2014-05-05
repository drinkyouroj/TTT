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
				
				NotificationLogic::repost($post);
				
				//This has to be outside 
				return Response::json(
					array('result'=>'success'),
					200//response is OK!
				);
			} elseif($exists) {//Relationship already exists
				
				Repost::where('post_id', '=', Request::segment(3))
					->where('user_id', '=', Auth::user()->id)
					->delete();
												
				NotificationLogic::unrepost(Request::segment(3));
				
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