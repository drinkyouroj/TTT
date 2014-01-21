<?php
/**
 * This had to be written as blade templates can't really render threaded shit very well.
 * since its a recursive.
 */

class ThreadedComments {

	public static function echo_comments($comments) {
		if(count($comments)) {
			foreach($comments as $comment) {
				echo '<li class="the-comment">
					<span>by '.$comment->user->username.'</span>
					<p>'.$comment->body.'</p>
					<a data-replyid="'.$comment->id.'" data-postid="'.$comment->post->id.'">Reply to '.$comment->user->username.'</a>
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