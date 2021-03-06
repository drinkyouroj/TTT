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
		if($alias == 'all' ) {
	    	//Set the category title
			$cat_title = 'All';
			$cat_desc = false;
			if(!strlen($request)) {
				$request = 'popular';//set default filter
			}
			
			//set the model
			$model = $this->post->instance();

		} elseif($alias == 'new') {
			$cat_title = 'New';
			$cat_desc = false;
			$request = 'recent';//will be set statically.
			$model = $this->post->instance();
			
		} else {
			
			//figure out the category id from alias	
	    	//(note, we're using the instance for now, but we'll move this to the repository in the future)
	    	$cat_instance = $this->category->instance();
	    	$cat = $cat_instance->where('alias', $alias)->first();
	    	if(!isset($cat->id)) {
	    		return false;
	    	}
			$cat_title = $cat->title;
			$cat_desc = $cat->description;
			
			if(!strlen($request)) {
				$request = 'recent';//set default filter
			}
			
			//set the model against the right category.
			$model = $cat_instance->find($cat->id);
		}

		switch($request) {			
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
			default:
				return false;
		}
		
		$posts = $posts->skip(($page-1)*$paginate)
						->select(
								array(
									'user_id',
									'title',
									'alias',
									'tagline_1',
									'tagline_2',
									'tagline_3',
									'story_type',
									'image',
									'published_at',
									'nsfw'
								)
							)

						->take($paginate)
						->get();
		
		//return the data.
		return  array(
					'posts' => $posts,
					'cat_title' => $cat_title,
					'cat_desc' => $cat_desc,
					'filter' => $request
				);
	}
	
}
