<?php

class CommentController extends BaseController {
	
/********************************************************************
 * Comments
*/

	public function postCommentForm()
	{
		$post_id = Request::segment(3);
		$comment = self::comment_object_input_filter();
		$validator = $comment->validate($comment->toArray());
		
		if($validator->passes()) {
			$comment->save();
			
			//Grab the post.
			$post = Post::where('id', $post_id)->first();
			
			//Check to make sure that you don't own the post.
			if($post->user_id != Auth::user()->id) {
				//Place in the notification if you're posting on other people's posts.
				$notification = new Notification;
				$notification->post_id = $post_id;
				$notification->user_id = $post->user->id;
				$notification->action_id = Auth::user()->id;
				$notification->notification_type = 'comment';
				$notification->comment_id = $comment->id;
				$notification->save();
				
				$mot = Motification::where('post_id', $post_id)//Post id									
									->where('user_id', $post->user->id)//person getting notified
									->where('notification_type', 'comment');
				
				if(!$mot->count()) {
					$mot = new Motification;
					$mot->post_id = Request::segment(3);
					$mot->post_title = $post->title;
					$mot->post_alias = $post->alias;
					$mot->user_id = $post->user->id;//Who this notification si going to.
					$mot->noticed = 0;
					$mot->comment_id = $comment->id;
					$mot->notification_type = 'comment';
					$mot->save();
				} else {
					//if it exists, just update.
					$mot->update(array('noticed' => 0));
				}
				$mot->push('users', Auth::user()->username,true);
				
				//Should the comment counter be incremented if you're the owner? no!
				Post::where('id', $post_id)->increment('comment_count',1);
			}
			
			
			//Also notify the person that you replied to:
			if($comment->parent_id != 0) {//if this is not an original comment.
				$orig_comment = Comment::where('id', $comment->parent_id)->first();
				//Gotta make sure to not notify you replying to you.
				if($orig_comment->user_id != Auth::user()->id) {
					$reply = new Notification;
					$reply->post_id = $post_id;
					$reply->user_id = $orig_comment->user_id;
					$reply->action_id = Auth::user()->id;
					$reply->notification_type = 'reply';
					$reply->comment_id = $comment->id;
					$reply->save();
					
					$mot = Motification::where('post_id', $post_id)//Post id									
										->where('user_id', $orig_comment->user_id)//person getting notified
										->where('notification_type', 'reply');
					
					if(!$mot->count()) {
						$mot = new Motification;
						$mot->post_id = Request::segment(3);
						$mot->post_title = $post->title;
						$mot->post_alias = $post->alias;
						$mot->user_id = $orig_comment->user_id;//Who this notification si going to.
						$mot->noticed = 0;
						$mot->comment_id = $comment->id;
						$mot->notification_type = 'reply';
						$mot->save();
					} else {
						//if it exists, just update.
						$mot->update(array('noticed' => 0));
					}
					$mot->push('users', Auth::user()->username,true);
					
				}
			}
			
			return Redirect::to('posts/'.$comment->post->alias.'#comment-'.$comment->id);
		} else {
			return Redirect::to('posts/'.$comment->post->alias)
							->withErrors($validator)
							->withInput();
		} 
	}


		private function comment_object_input_filter()
		{
			$comment = new Comment;
			$comment->user_id = Auth::user()->id;
			$comment->post_id = Request::segment(3);
			$comment->published = 1;//This sets the comment to be "deleted"  We did this so we don't lose the tree structure.
			if(Request::get('reply_id')) {
				$comment->parent_id = Request::get('reply_id');
			}
			$comment->body = Request::get('body');
			return $comment;
		}
		
		/****
		 * Below function is a future function for filtering the body
		 * so that we can define links within the comments to profiles.
		 */ 
		private function comment_body_filter($body)
		{
			
		}
		

	public function getCommentForm($post_id, $reply_id = false) {
		$post = Post::find($post_id);
		return View::make('generic/commentform')
				->with('post', $post)
				->with('reply_id', $reply_id);
	}
	

}
