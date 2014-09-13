<?php
/*
 * The Search Controller!
 */
class SearchController extends BaseController {
	
	public function __construct(PostRepository $post, 
								SearchRepository $search,
								UserRepository $user ) {
		$this->post = $post;
		$this->search = $search;
		$this->user = $user;
	}
	
	public function searchUsers( $search_string, $page ) {
		$user_results = $this->search->searchUsers( $search_string, $page );
		$user_ids = self::reduceToIds( $user_results );
		$users = count( $user_ids ) ? $this->user->allByIds($user_ids)->toArray() : array();
		return Response::json( array( 'users' => $users ), 200 );
	}

	public function searchPosts( $search_string, $page ) {
		$post_results = $this->search->searchPosts( $search_string, $page );
		$post_ids = self::reduceToIds( $post_results );
		$posts = count( $post_ids ) ? $this->post->allByPostIds($post_ids, 1)->toArray() : array();
		return Response::json( array( 'posts' => $posts ), 200 );
	}

	public function getSearchPage() {
		return View::make('v2.search.search')
			->with('default', true);
	}
	/**
	 * Grabs Results in a page for both Posts and Users
	 * @param string $term String that you're searching for.
	 */
	public function postResult() {
		$term = Input::has('search') ? Input::get('search') : '';
		$page = Input::has('page') ? Input::get('page') : 1;
		// Fetch results
		$user_results = $this->search->searchUsers( $term, $page );
		$post_results = $this->search->searchPosts( $term, $page );
		// Get array of ids
		$post_ids = self::reduceToIds( $post_results );
		$user_ids = self::reduceToIds( $user_results );
		
		$posts = count( $post_ids ) ? $this->post->allByPostIds($post_ids, 1) : array();
		$users = count( $user_ids ) ? $this->user->allByIds($user_ids) : array();

		return View::make('v2.search.search')
				   ->with('posts', $posts)
				   ->with('users', $users)
				   ->with('term', $term);
	}

		private function reduceToIds( $content ) {
			$results = array();
			foreach ($content as $value) {
				array_push($results, $value['fields']['id'][0]);
			}
			return $results;
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
		
		$result_array = array();
		$result_array['posts'] = $posts['response']['docs'];
		$result_array['users'] = $users['response']['docs'];
		
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