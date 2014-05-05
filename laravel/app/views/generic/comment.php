<?php
/**
 * Not the prettiest of the views I've written in my life.  
 * It sucks. but it works well.
 */
$result = '';
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
	
	
	if(!$comment->published) {
		$comment->user->username = 'nobody';
		$comment->body = '<span class="deleted">Comment was deleted</span>';
	}
	
	$result .= '<li class="the-comment published-'.$comment->published.'" id="comment-'.$comment->id.'">
		<span>by <a href="'.url('profile/'.$comment->user->username).'">'.$comment->user->username.'</a></span>
		<p class="comment-body">'.$comment->body.'</p>
		'.$vote.$reply.$delete.'
		<div class="reply-box"></div>';
	if(count($comment->children)) {
		
		$view = View::make('generic/comment')//self calling right here.
					->with('comments', $comment->children);
		$result .= '<ul>';
		$result .= $view->render();
		$result .= '</ul>';
		
	}
	$result .= '</li>';
}//foreach
echo $result;