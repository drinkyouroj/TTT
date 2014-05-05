<?php
/**
 * Comments Class handles all comment related issues.
 */
class CommentController extends BaseController {

	public function __construct(
								PostRepository $post, 
								CommentRepository $comment
								) {
		$this->post = $post;
		$this->comment = $comment;
	}

	/**
	 * The Post Comment Form
	 */
	public function postCommentForm()
	{
		$user_id = Auth::user()->id;
		//$comment = CommentLogic::comment_object_input_filter();
		$comment = $this->comment->input($user_id);
		$validator = $comment->validate($comment->toArray());//validation happens as an array
		
		if($validator->passes()) {
			$comment->save();
			$post = $this->post->findById(Request::segment(3));
			
			if($post->user_id != $user_id) {
				//Should the comment counter be incremented if you're the owner? no!
				$this->post->incrementComment($post->id);
			}
			
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
		$post = $this->post->findById($post_id);
		return View::make('generic/commentform')
				->with('post', $post)
				->with('reply_id', $reply_id);
	}	

}
