<?php namespace AppLogic\PostLogic;

//Below will be replaced with Repositories when we have the chance.
use App, Auth, Request, AppStorage\Post\PostRepository;

/**
 * This class holds many of the business logic for the Post Controller
 */
class PostLogic {

	public function __construct() {
		//Below sucks compared to how the interface is usually implemented, but its having issues so we're doing it this way.
		$this->post = App::make('AppStorage\Post\PostRepository');
	}
	
	/**
	 * Divide the body text for display
	 * @param int $longString The body text
	 * @param int $maxLineLength The max length of each portion
	 * @return array $arrayOutput The output is the divided text. 
	 */
	public function divide_text($longString, $maxLineLength)
	{		
		$arrayWords = explode(' ', $longString);
		
		// Auxiliar counters, foreach will use them
		$currentLength = 0;
		$index = 0;
		$arrayOutput = array();
		$arrayOutput[0]= '';
		foreach($arrayWords as $k => $word)
		{
		    // +1 because the word will receive back the space in the end that it loses in explode()
			$wordLength = strlen($word) + 1;
		
			if( ( $currentLength + $wordLength ) <= $maxLineLength ) {
				
			    $arrayOutput[$index] .= $word . ' ';
	        	$currentLength += $wordLength;
				
		    } else {
		    	
		        $index += 1;
				$c = false;
				
		        $currentLength = $wordLength;
				
				//Below is to counter this weird thing where it gets rid of a space.
				if($c) {
					$arrayOutput[$index] = $word;
				} else {
					$arrayOutput[$index] = $word.' ';
				}
				$c++;
		    }
		}
		return $arrayOutput;
	}
	
	/**
	 * Input filtering.  (Probably move this piece to the repository)
	 * @param boolean $new Is this a new post?
	 * @param object $post Post object (for updates)
	 */
	public function post_object_input_filter($new = false,$post = false)
	{	
		if($new) {
			//Creates a new post
			$post = $this->post->instance();
			$post->user_id = Auth::user()->id;
			
			//Gotta make sure to make the alias only alunum.  Don't change alias on the update.  We don't want to have to track this change.
			$post->alias = preg_replace('/[^A-Za-z0-9]/', '', Request::get('title')).'-'.str_random(5).'-'.date('m-d-Y');//makes alias.  Maybe it should exclude other bits too...
			$post->story_type = Request::get('story_type');
			
			$post->category = serialize(Request::get('category'));
			$post->image = Request::get('image','0');//If 0, then it means no photo.
		}
		
		$post->title = Request::get('title');
		$post->tagline_1 = Request::get('tagline_1');
		$post->tagline_2 = Request::get('tagline_2');
		$post->tagline_3 = Request::get('tagline_3');
		
		$post->body = Request::get('body');//Body is the only updatable thing in an update scenario.
		$post->published = 1;
		
		return $post;
	}
	

}