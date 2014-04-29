<?php namespace AppLogic\CategoryLogic;

//Below will be replaced with Repositories when we have the chance.
use Category, Post;


/**
 * This class holds all the business logic for the Category Controller
 */
class CategoryLogic {
	
	/**
	 * Gets paginated data for Category filtered posts
	 * @param string $alias The Category Alias
	 * @param string $request The filter type (popular, recent, etc)
	 * @param integer $page The Page that we're on.
	 * @param integer $paginate How much to paginate by
	 * @return array $data Title and Post data.
	 */
	public function data($alias, $request, $page = 0, $paginate) {
			
		//Figure out if the page is being passed correctly
		if($page == false) {
			$page = 1;
		}
		
		/**
		 * The way the filters work we've unfortunately implemented the system this way.
		 */
		if($alias != 'all') {
	    	//figure out the category id from alias	
	    	$cat = Category::where('alias', $alias)->first();
			$cat_title = $cat->title;
			
			if(!strlen($request)) {
				$request = 'recent';//set default filter
			}
			
			//set the model against the right category.
			$model = Category::find($cat->id);
			
			
		} else {
			//Set the category title
			$cat_title = 'All';
			
			if(!strlen($request)) {
				$request = 'popular';//set default filter
			}
			
			//set the model
			$model = new Post;
		}
		
		switch($request) {
			default:
			case 'recent':
				$posts = $model->recent();
			break;
			case 'viewed':
				$posts = $model->postsviews();
			break;
			case 'popular':
				$posts = $model->postspopular();
			break;
			case 'discussed':
				$posts = $model->postsdiscussed();
			break;
			case 'longest':
				$posts = $model->longest();
			break; 
			case 'shortest':
				$posts = $model->shortest();
			break;  
		}
		
		$posts = $posts->skip(($page-1)*$paginate)
						->take($paginate)
						->get();
		
		//return the data.
		return  array(
					'posts' => $posts,
					'cat_title' => $cat_title
				);
	}
	
}
