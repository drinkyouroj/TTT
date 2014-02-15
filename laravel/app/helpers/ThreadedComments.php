<?php
/**
 * This had to be written as blade templates can't really render threaded shit very well.
 * since its a recursive.
 */

class ThreadedComments {

	public static function echo_comments($comments) {
		if(count($comments)) {
			foreach($comments as $comment) {
				
				$delete = '';
				
				//Let's make sure you're logged in.
				if(Auth::check()) {
					$reply = '<a class="reply" data-replyid="'.$comment->id.'" data-postid="'.$comment->post->id.'">Reply to '.$comment->user->username.'</a>';
					$vote = '<a title="Vote Up" class="vote up" data-upid="'.$comment->id.'"><span>Up Vote</span></a>';
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
				
				echo '<li class="the-comment" id="comment-'.$comment->id.' published-'.$comment->published.'">
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