<?php
/*
 * The Search Controller!
 */
class SearchController extends BaseController {
	
	function __constructor() {
		
	}
	
	/**
	 * Grabs Results in a page for both Posts and Users
	 * 
	 */
	public function postResult() {
		$term = Input::get('term');
		
		//Grab results from Solr
		$results = SolariumHelper::searchSolr($term, false);//false = not for AJAX
		
		$posts = $results['posts']->getData();
		$users = $results['users']->getData();
		
		$ids = self::flatten($posts['response']['docs']);
		$user_ids = self::flatten($users['response']['docs']);
		
		if(count($ids) || count($user_ids)) {
			
			//This is out here incase we don't get the right stuff.
			$users = $posts = $message = 'No Match Found';
			
			//get from the result set.
			if(count($ids)) {
				$posts = Post::whereIn('id', $ids)
					->where('published', 1)
					->get();
			}
			if(count($user_ids)) {
				$users = User::whereIn('id', $user_ids)
					->where('banned', 0)
					->get();
			}
			
			return View::make('generic.search')
					->with('posts',$posts)
					->with('users',$users)
					->with('term', $term);
		} else {
			return View::make('generic.error')
					->with('message', $message);
		}
	}
	
		/**
		 * This will need to be placed in a more generalized location.
		 */
		private function flatten(array $array) {
		    $return = array();
		    array_walk_recursive($array, function($a) use (&$return) { $return[] = $a; });
		    return $return;
		}
	
	/**
	 * Async listing for the top search bar
	 * @param string $term String that you're searching for.
	 */
	public function getResult($term) {
		$results = SolariumHelper::searchSolr($term, true);//returns title and taglines
		
		//Below couple of lines kind of blows, but the data structure is as such.
		$posts = $results['posts']->getData();
		$users = $results['users']->getData();
		
		if(count($posts['response']['docs']) || 
		   count($users['response']['docs'])){
			return Response::json(
				$result_array,
				200//response is OK!
			);
		} else {
			return Response::json(
				0,
				200
			);
		}
	}
	
}