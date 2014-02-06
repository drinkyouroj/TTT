<?php

class HomeController extends BaseController {
	//The slash!
	public function getIndex()
	{	
			
		$featured = Post::where('featured', '=', 1)->orderby('featured_date','desc')->take(5)->get();
		$past_featured = Post::where('featured', '=',  true)->orderBy('featured_date','desc')->take(5)->skip(5)->get();//Skip the first 5.
		
		return View::make('home.index')
					->with('featured',$featured)	//Featured Posts
					->with('past_featured',$past_featured)//Past Featured
					;
	}

	//Our Story Type Page
	public function getAbout()
	{
		return View::make('static.about');
	}
}