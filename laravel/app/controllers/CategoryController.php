<?php

class CategoryController extends BaseController {

	public function __construct() {
		
	}

    /**
     * Get Category
	 * 	I wished this function was a bit less complex.
     */
    public function getCategory($alias, $request = false)
    {
    	
		$data = self::categoryData($alias, $request);
		
		$posts = $data['posts'];
		$cat_title = $data['cat_title'];
		
		if(!count($posts)) {
			$posts = 'No Posts in this Category!';
		}
				
		return View::make('generic.index')
					->with('cat_title', $cat_title)
					->with('posts', $posts);
    }

	//A REST version of the above.
	public function getRestCategory($alias, $request = false) {
		
		$data = self::categoryData($alias, $request);
		
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
	
		//Both functions above are ways to access this same data set.
		private function categoryData($alias, $request,$page = 1) {
			
			$paginate = 12;//number of items to load at a time.
			
			if(Request::get('page')) {
				$page = abs(Request::get('page'));//just to be sure.
			} else {
				$page = 1;
			}
			
			if($alias != 'all') {
		    	//figure out the category id from alias	
		    	$cat = Category::where('alias', $alias)->first();
				
				$cat_title = $cat->title;
				
				$model = Category::find($cat->id);
						
				
				//check for sort by
				if(!strlen($request)) {
					$request = 'recent';
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
				
				$posts = $posts->skip(($page-1)*$paginate)->take($paginate)->get();
				/*
				$queries = DB::getQueryLog();
				$last_query = end($queries);
				dd($queries);
				*/
			} else {
				$cat_title = 'All';
				
				if(!strlen($request)) {
					$request = 'popular';
				}
				$model = Post::where('published', true);
				
				switch($request) {
					default:
					case 'popular':
						$posts = $model->orderBy('like_count','DESC');
					break;
					case 'viewed':
						$posts = $model->orderBy('views', 'DESC');
					break;
					case 'recent':
						$posts = $model->orderBy('created_at','DESC');
					break;
					case 'discussed':
						$posts = $model->orderBy('comment_count','DESC');
					break;
					case 'longest':
						$posts = $model->orderBy(DB::raw('LENGTH(body)'),'DESC');
					break; 
					case 'shortest':
						$posts = $model->orderBy(DB::raw('LENGTH(body)'),'ASC');
					break;  
				}
				
				$posts = $posts->skip(($page-1)*$paginate)->take($paginate)->get();
				
			}
			
			//Gotta pack up the data.
			$data = array(
					'posts' => $posts,
					'cat_title' => $cat_title
				);
			
			return $data;
		}

}