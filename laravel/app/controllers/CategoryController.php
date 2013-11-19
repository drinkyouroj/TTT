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
		//Grab the right posts.
		$posts = Category::find($cat->id)->posts; 
		
		if(!count($posts)) {
		
			$posts = 'No Posts in this Category!';
		}

        return View::make('generic.index')
						->with('posts', $posts);
    }

}