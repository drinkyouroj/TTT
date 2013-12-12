<?php
class SearchController extends BaseController {
	
	function __constructor() {
		
	}
	
	public function postResult() {
		$term = Input::get('term');
		
		//Grab results from Solr
		$results = SolariumHelper::searchSolr($term, false);//false = not for AJAX
		
		$ids = array();
		foreach($results as $result ) {
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
	
	public function getResult($term) {
		//$term = Request::segment(2);
		$results = SolariumHelper::searchSolr($term, true);//returns title and taglines
		
		$result_array = $results->getData();
		//dd($result_array['response']['docs']);
		if(count($result_array)){
			return Response::json(
					$result_array['response']['docs'],
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