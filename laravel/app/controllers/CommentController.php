<?php
/**
 * Comments Class handles all comment related issues.
 */
class CommentController extends BaseController {

	/**
	 * The Post Comment Form
	 */
	public function postCommentForm()
	{
		$post_id = Request::segment(3);
		$comment = self::comment_object_input_filter();
		$validator = $comment->validate($comment->toArray());
		
		if($validator->passes()) {
			$comment->save();
			$post = Post::find($post_id);			
			//Notification code for comments
			NotificationLogic::comment($post, $comment);
			
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
			$comment->body = strip_tags(Request::get('body'));
			return $comment;
		}
		
		/****
		 * Below function is a future function for filtering the body
		 * so that we can define links within the comments to profiles. (think of the @ on FB or Instagram)
		 */ 
		private function comment_body_filter($body)
		{
			
		}
		
	/**
	 * Creates a form to be injected as a reply.  Not the best method, but we'll improve on this once we go full app for users.
	 */
	public function getCommentForm($post_id, $reply_id = false) {
		$post = Post::find($post_id);
		return View::make('generic/commentform')
				->with('post', $post)
				->with('reply_id', $reply_id);
	}
	

}
