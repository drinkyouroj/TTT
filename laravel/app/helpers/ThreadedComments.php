<?php
/**
 * This had to be written as blade templates can't really render threaded shit very well.
 * since its a recursive.
 */

class ThreadedComments {

	public static function echo_comments($comments) {
		if(count($comments)) {
			foreach($comments as $comment) {
				//Let's make sure you're logged in.
				if(Auth::check()) {
					$reply = '<a class="reply" data-replyid="'.$comment->id.'" data-postid="'.$comment->post->id.'">Reply to '.$comment->user->username.'</a>';
					$vote = '<a title="Vote Up" class="vote up" data-upid="'.$comment->id.'"><span>Up Vote</span></a>';
				} else  {
					$reply = '';//maybe place a login pop up form?
					$vote = '';
				}
				
				$delete = '';
				
				//Either this person owns the comment, or you're a mod.
				if( (Session::get('user_id') == $comment->user->id && $comment->published == 1) || 
					(Auth::user()->hasRole('Moderator') )) 
				{
					$delete = ' <a title="Delete Comment" class="delete" data-delid="'.$comment->id.'">Delete</a>';
				}
				
				if($comment->published) {
					$comment_body = $comment->body;
				} else {
					$comment_body = '<span class="deleted">Comment was deleted</span>';
				}
				
				echo '<li class="the-comment" id="comment-'.$comment->id.'">
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

?>