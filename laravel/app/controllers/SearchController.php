<?php
/*
 * 
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
		
		$ids = array();
		foreach($results['posts'] as $result ) {
			array_push($ids, $result->id);
		}
		
		$user_ids = array();
		foreach($results['users'] as $result ) {
			array_push($user_ids, $result->id);
		}
		
		$message = 'No Match Found';
		
		if(count($ids) || count($user_ids)) {
			
			$users = $posts = $message;
			
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
	 * Async listing for the top search bar
	 * @param string $term String that you're searching for.
	 */
	public function getResult($term) {
		$results = SolariumHelper::searchSolr($term, true);//returns title and taglines
		
		//Below kind of blows, but the data structure is as such.
		$result_array = array();
		$posts = $results['posts']->getData();
		$users = $results['users']->getData();
		$result_array['posts'] = $posts['response']['docs'];
		$result_array['users'] = $users['response']['docs']; 
		
		if(count($result_array['posts']) || count($result_array['users'])){
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