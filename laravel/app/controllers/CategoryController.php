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
				case 'viewed':
					$posts = Category::find($cat->id)->postsviews;
				break;
				case 'favorited':
					$posts = Category::find($cat->id)->postsfavorites;
				break;
				case 'fucked':
					$posts = Category::find($cat->id)->postsfucks;
				break;
			}
		} else {
			//Grab the right posts.
			$posts = Category::find($cat->id)->posts;
		}
		
		
		if(!count($posts)) {
		
			$posts = 'No Posts in this Category!';
		}

        return View::make('generic.index')
						->with('posts', $posts);
    }

}