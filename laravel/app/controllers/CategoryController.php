<?php

class CategoryController extends BaseController {

	public function __construct() {
		
	}

    /**
     * Get Category
     */
    public function getCategory($alias)
    {	
    	$cat = Category::where('alias', $alias)->take(1)->get();
		$cat = $cat->toArray();
        $posts = Post::where('category', $cat[0]['id'])->get();

        return View::make('generic.index')
							->with('posts', $posts);
    }

}