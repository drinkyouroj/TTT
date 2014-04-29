<?php namespace AppLogic\CommentLogic;

//Below will be replaced with Repositories when we have the chance.
use Comment, Auth, Request, Session;


/**
 * This class holds all the business logic for the Comment Controller
 */
class CommentLogic {
	
	/**
	 * Fetches the inputs for the Comment object and preps it for saving.
	 */
	public function comment_object_input_filter()
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
	public function comment_body_filter($body)
	{
		
	}
	
	/**
	 * Echos out the comments in a threaded fashion.
	 * @param object $comment Comment object attached to a post.
	 * @return HTML returns a rendered view.  This needs to be separated at some point.
	 */
	public function echo_comments($comments) {
		if(count($comments)) {
			foreach($comments as $comment) {
				
				$delete = '';
				
				//Temporary Fix for users who do not exist.
                if(!isset($comment->user->username)) {
                        $comment->user = new stdClass();
                        $comment->user->username = 'nobody';
                        $comment->user->id = 1;
                }
				
				//Let's make sure you're logged in.
				if(Auth::check()) {
					$reply = '<a class="reply" data-replyid="'.$comment->id.'" data-postid="'.$comment->post->id.'">Reply to '.$comment->user->username.'</a>';
					//$vote = '<a title="Vote Up" class="vote up" data-upid="'.$comment->id.'"><span>Up Vote</span></a>';//saved incase we want to use it again
					$vote = '';
					//If you're a Mod, you can definitely do some stuff.
					if(Auth::user()->hasRole('Moderator'))
					{
						if($comment->published == 1) {
							$del_action = 'delete';
						} else {
							$del_action = 'undelete';
						}
						
						$delete = ' <a title="Delete Comment" class="mod-del-comment" data-delid="'.$comment->id.'">Moderator '.$del_action.'</a>';
					}
					
					//Either this person owns the comment, or you're a mod.
					if( Session::get('user_id') == $comment->user->id && $comment->published == 1 )
					{	
						$delete = ' <a title="Delete Comment" class="delete" data-delid="'.$comment->id.'">Delete</a>';
					}
				} else  {
					$reply = '';//maybe place a login pop up form?
					$vote = '';
				}
				
				
				if($comment->published) {
					$comment_body = $comment->body;
				} else {
					$comment_body = '<span class="deleted">Comment was deleted</span>';
				}
				
				echo '<li class="the-comment published-'.$comment->published.'" id="comment-'.$comment->id.'">
					<span>by <a href="'.url('profile/'.$comment->user->username).'">'.$comment->user->username.'</a></span>
					<p class="comment-body">'.$comment_body.'</p>
					'.$vote.$reply.$delete.'
					<div class="reply-box"></div>';
				if(count($comment->children)) {
					//var_dump($comment->children);
					
					echo '<ul>';
					self::echo_comments($comment->children);//self calling... Maybe we should have a delta that gets passed as to not make this too crazy
					echo '</ul>';
					
				}
				echo '</li>';
			}
		}
	}
	
}