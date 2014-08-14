<?php namespace AppLogic\CategoryLogic;

//All models will be replaced with Repositories when we have the chance.
use App,
	AppStorage\Category\CategoryRepository,
	AppStorage\Post\PostRepository
	;


/**
 * This class holds all the business logic for the Category Controller
 */
class CategoryLogic {
	
	public function __construct() {
		$this->post = App::make('AppStorage\Post\PostRepository');
		$this->category = App::make('AppStorage\Category\CategoryRepository');
	}

	/**
	 * Gets paginated data for Category filtered posts
	 * @param string $alias The Category Alias
	 * @param string $request The filter type (popular, recent, etc)
	 * @param integer $page The Page that we're on.
	 * @param integer $paginate How much to paginate by
	 * @return array $data Title and Post data.
	 */
	public function data($alias, $request, $page = 1, $paginate) {
		
		/**
		 * The way the filters work we've unfortunately implemented the system this way.
		 */
		if($alias != 'all') {
	    	//figure out the category id from alias	
	    	//(note, we're using the instance for now, but we'll move this to the repository in the future)
	    	$cat_instance = $this->category->instance();
	    	$cat = $cat_instance->where('alias', $alias)->first();
			$cat_title = $cat->title;
			
			if(!strlen($request)) {
				$request = 'recent';//set default filter
			}
			
			//set the model against the right category.
			$model = $cat_instance->find($cat->id);
			
			
		} else {
			//Set the category title
			$cat_title = 'All';
			
			if(!strlen($request)) {
				$request = 'popular';//set default filter
			}
			
			//set the model
			$model = $this->post->instance();
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
