<?php
/**
 * This had to be written as blade templates can't really render threaded shit very well.
 * since its a recursive.
 */

class ThreadedComments {

	public static function echo_comments($comments) {
		if(count($comments)) {
			foreach($comments as $comment) {
				if(Auth::check()) {
					$reply = '<a class="reply" data-replyid="'.$comment->id.'" data-postid="'.$comment->post->id.'">Reply to '.$comment->user->username.'</a>';
					$vote = '<a title="Vote Up" class="vote up" data-upid="'.$comment->id.'"><span>Up Vote</span></a> <a title="Vote Down" class="vote down" data-downid="'.$comment->id.'"><span>Down Vote</span></a>';
				} else  {
					$reply = '';//maybe place a login pop up form?
					$vote = '';
				}
				echo '<li class="the-comment" id="comment-'.$comment->id.'">
					<span>by <a href="'.url('profile/'.$comment->user->username).'">'.$comment->user->username.'</a></span>
					<p>'.$comment->body.'</p>
					'.$vote.$reply.'
					<div class="reply-box"></div>';				
				if(count($comment->children)) {
					//var_dump($comment->children);
					
					echo '<ul>';
					self::echo_comments($comment->children);
					echo '</ul>';
					
				}
				echo '</li>';
			}
		}
	}

}

?>