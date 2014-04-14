<?php

class CategoryController extends BaseController {

	public function __construct() {
		
	}

	private $paginate = 12;//Default Pagination amount for Category pulls.

    /**
     * Get Category Page
     */
    public function getCategory($alias, $request = false)
    {
    	
		$data = self::categoryData($alias, $request);//pull the data.
		
		$posts = $data['posts'];
		$cat_title = $data['cat_title'];
		
		if(!count($posts)) {
			$posts = 'No Posts in this Category!';
		}
				
		return View::make('generic.index')
					->with('cat_title', $cat_title)
					->with('posts', $posts);
    }

	/**
	 * Autoload function for the Category Pages.
	 */
	public function getRestCategory($alias, $request = false) {
		
		$data = self::categoryData($alias, $request);//pull the data.
		
		$posts = $data['posts'];
		
		if(!count($posts)) {
			return Response::json(
				array('error' => true),
				200
			);
		} else {
			return Response::json(
				$posts->toArray(),
				200
			);
		}
		
	}
	
		/**
		 * Gets paginated data for Category filtered posts
		 * @param string $alias The Category Alias
		 * @param integer $page The Page that we're on.
		 * @return array $data Title and Post data.
		 */
		private function categoryData($alias, $request,$page = 1) {
			
			//Figure out the page
			if(Request::get('page')) {
				$page = abs(Request::get('page'));//just to be sure.
			} else {
				$page = 1;
			}
			
			/**
			 * The way the filters work we've unfortunately implemented teh system this way.
			 */
			if($alias != 'all') {
		    	//figure out the category id from alias	
		    	$cat = Category::where('alias', $alias)->first();
				$cat_title = $cat->title;
				
				//set the model
				$model = Category::find($cat->id);
				
				if(!strlen($request)) {
					$request = 'recent';//set default
				}
			} else {
				$cat_title = 'All';
				
				if(!strlen($request)) {
					$request = 'popular';//set default
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
			
			$posts = $posts->skip(($page-1)*$this->paginate)->take($this->paginate)->get();
			
			//return the data.
			return  array(
						'posts' => $posts,
						'cat_title' => $cat_title
					);
		}

}