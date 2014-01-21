<?php

class HomeController extends BaseController {
	//The slash!
	public function getIndex()
	{	
			
		$featured = Post::where('featured', '=', 1)->take(5)->get();//Will replace with actual model once its built.
		$past_featured = Post::where('featured', '=',  true)->take(5)->skip(5)->get();//Skip the first 5.
		
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