<?php namespace AppLogic\PostLogic;

use App,
	Config,
	ArrayObject,
	AppStorage\Post\PostRepository,
	DaveChild\TextStatistics as TS
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

	/**
	*	Takes post body and does analysis as to how readable the text is.
	*/
	public function readability($body)
	{
		$ts = new TS\TextStatistics;
		$reading_ease = $ts->fleschKincaidReadingEase(strip_tags($body));
		return $reading_ease;
	}

	/**
	*	Takes post body and does analysis as to how readable the text is.
	*/
	public function grade($body)
	{
		$ts = new TS\TextStatistics;
		$reading_ease = $ts->fleschKincaidGradeLevel(strip_tags($body));
		return $reading_ease;
	}

	public function sentiment($body)
	{
		$words = urlencode($this->clean(strip_tags($body)));
		$data = false;
		/*
		try
		{
			$data = file_get_contents(Config::get('sentiment.server').'/sentiment/'.$words);
		}
		catch(Exception $e)
		{
			
		}
		*/
		if(!$data) {
			$data = new ArrayObject();
			$data->positive = 0;
			$data->negative = 0;
			return $data;
		}
		return json_decode($data);
	}

		private function clean($string)
		{
			return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
		}

}