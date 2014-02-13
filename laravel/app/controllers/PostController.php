<?php
class PostController extends BaseController {

	public function getIndex() {
		//maybe some function that takes you to a random post here?
	}

    /**
     * Get Post
     */
    public function getPost($alias)
    {	
        $post = Post::where('alias', $alias)->first();
		
		//Do the post math here
		$body_array = self::divide_text($post->body, 3000);//the length is currently set to 300 chars
		$user_id = $post->user->id;
		
		$is_following = Follow::where('follower_id', '=', Session::get('user_id'))
							->where('user_id', '=', $user_id)
							->count();
		$is_follower = Follow::where('follower_id', '=', $user_id)
							->where('user_id', '=', Session::get('user_id'))
							->count();
		
		//Add the fact that the post has been viewed if you're not the owner and you're logged in.
		if($user_id != Session::get('user_id') && Auth::check()) {
			$postview = PostView::where('user_id', Session::get('user_id'))
					->where('post_id', $post->id)
					->count();
			//If the record doesn't exist, increment on the post view count and also add to the "viewed" in PostView
			if(!$postview) {
				$pv = new PostView;
				$pv->user_id = Session::get('user_id');
				$pv->post_id = $post->id;
				$pv->save();
				Post::where('alias', $alias)->increment('views', 1);//increment on this post.
			}
		}
		
        return View::make('generic.post')
					->with('post', $post)
					->with('is_following', $is_following)//you are following this profile
					->with('is_follower', $is_follower)//This profile follows you.
					->with('bodyarray', $body_array)
					;
    }
	
		//Straight out of compton (or stackoverflow)
		private function divide_text($longString, $maxLineLength)
		{		
			$arrayWords = explode(' ', $longString);
			
			// Auxiliar counters, foreach will use them
			$currentLength = 0;
			$index = 0;
			$arrayOutput = array();
			$arrayOutput[0]= '';
			foreach($arrayWords as $word)
			{
			    // +1 because the word will receive back the space in the end that it loses in explode()
				$wordLength = strlen($word) + 1;
			
				if( ( $currentLength + $wordLength ) <= $maxLineLength ) {
					
				    $arrayOutput[$index] .= $word . ' ';
		        	$currentLength += $wordLength;
					
			    } else {
			    	
			        $index += 1;
			        $currentLength = $wordLength;
			        $arrayOutput[$index] = $word;
					
			    }
			}
			return $arrayOutput;
		}


}