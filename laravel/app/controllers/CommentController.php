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
		$comment = CommentLogic::comment_object_input_filter();
		$validator = $comment->validate($comment->toArray());//validation happens as an array
		
		if($validator->passes()) {
			$comment->save();
			$post = Post::find(Request::segment(3));			
			//Notification code for new comments
			NotificationLogic::comment($post, $comment);
			
			return Redirect::to('posts/'.$comment->post->alias.'#comment-'.$comment->id);
		} else {
			return Redirect::to('posts/'.$comment->post->alias)
							->withErrors($validator)
							->withInput();
		} 
	}
		
	/**
	 * Creates a form to be injected as a reply.  Not the best method, but we'll improve on this once we go full app for users.
	 */
	public function getCommentForm($post_id, $reply_id = false) 
	{
		$post = Post::find($post_id);
		return View::make('generic/commentform')
				->with('post', $post)
				->with('reply_id', $reply_id);
	}	

}
