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

	/**
	 * Grabs Results in a page for both Posts and Users
	 * @param string $term String that you're searching for.
	 */
	public function getSearchPage() {
		
		if ( Input::has('search') ) {
			$term = Input::has('search') ? Input::get('search') : '';
			$page = Input::has('page') ? intval( Input::get('page') ) : 1;
			$filter = Input::has('filter') ? Input::get('filter') : 'posts';
			if ( !$filter == 'posts' || !$filter == 'users' ) {
				$filter = 'posts';
			}
			$page = $page < 1 ? 1 : $page;
			if ( $filter == 'posts' ) {
				$users_page = 1;	
			} else if ( $filter == 'users' ) {
				$users_page = $page;
				$page = 1;
			}
			
			// Basically, if we are filtering for posts, then we want the users tab to
			// be on page 1!
			$other_page = 1;
			// Fetch results
			$user_results = $this->search->searchUsers( $term, $users_page );
			$post_results = $this->search->searchPosts( $term, $page );
			// Get counts for paginate
			$user_count = count ( $user_results );
			$post_count = count ( $post_results );
			// Get array of ids
			$post_ids = self::reduceToIds( $post_results );
			$user_ids = self::reduceToIds( $user_results );
			
			$posts = count( $post_ids ) ? $this->post->allByPostIds($post_ids, 1) : array();
			$users = count( $user_ids ) ? $this->user->allByIds($user_ids) : array();

			return View::make('v2.search.search')
					   ->with('posts', $posts)
					   ->with('users', $users)
					   ->with('post_count', $post_count)
					   ->with('user_count', $user_count)
					   ->with('page', $page)
					   ->with('users_page', $users_page)
					   ->with('filter', $filter)
					   ->with('term', $term);
		} else {
			return View::make('v2.search.search')
						->with('default', true);
		}
	}

		private function reduceToIds( $content ) {
			$results = array();
			foreach ($content as $value) {
				array_push($results, $value['fields']['id'][0]);
			}
			return $results;
		}
	
}