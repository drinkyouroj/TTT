<?php namespace AppLogic\PostLogic;

use App, 
	AppStorage\Post\PostRepository
	;

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

}