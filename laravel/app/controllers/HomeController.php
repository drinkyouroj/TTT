<?php

class HomeController extends BaseController {
	//The slash!
	public function getIndex()
	{	
			
		$featured = Post::where('featured', '=', 1)
					->where('published',1)
					->orderby('featured_date','desc')
					->take(10)
					->get();
		
		return View::make('home.index')
					->with('featured',$featured)	//Featured Posts
					//->with('past_featured',$past_featured)//Past Featured
					;
	}

	//Our Story Type Page
	public function getAbout()
	{
		return View::make('static.about');
	}
}