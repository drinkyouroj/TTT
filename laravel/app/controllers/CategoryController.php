<?php

class CategoryController extends BaseController {

	public function __construct() {
		
	}

    /**
     * Get Category
     */
    public function getCategory($alias)
    {
    		
    	//figure out the category id from alias	
    	$cat = Category::where('alias', $alias)->take(1)->first();
		
		
		//check for sort by
		if(strlen(Request::segment(3))) {
			switch(Request::segment(3)) {
				default:
				case 'popular':
					$posts = Category::find($cat->id)->postspopular;
				break;
				case 'viewed':
					$posts = Category::find($cat->id)->postsviews;
				break;
				case 'recent':
					$posts = Category::find($cat->id)->recent;
				break;
				case 'discussed':
					$posts = Category::find($cat->id)->postsdiscussed;
				break;
				case 'longest':
					$posts = Category::find($cat->id)->longest;
				break; 
				case 'shortest':
					$posts = Category::find($cat->id)->shortest;
				break;  
			}
		} else {
			//Grab the right posts.
			$posts = Category::find($cat->id)->postspopular;
		}
		
		
		if(!count($posts)) {
		
			$posts = 'No Posts in this Category!';
		}

        return View::make('generic.index')
						->with('posts', $posts);
    }

}