<?php
/*
 * 
 */
class SearchController extends BaseController {
	
	function __constructor() {
		
	}
	
	//Physical Listing of Search in new page.
	public function postResult() {
		$term = Input::get('term');
		
		//Grab results from Solr
		$results = SolariumHelper::searchSolr($term, false);//false = not for AJAX
		
		$ids = array();
		foreach($results['posts'] as $result ) {
			array_push($ids, $result->id);
		}
		
		if(count($ids)) {
			//get from the result set.
			$posts = Post::whereIn('id', $ids)->get();
			return View::make('generic.index')
					->with('posts',$posts);
		} else {
			return View::make('generic.error')
					->with('message', 'No Match Found');
		}
		
	}
	
	//Ajax listing
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