<?php

class HomeController extends BaseController {

	//Gonna be using Blade
	protected $layout = 'layouts.master';

	//The slash!
	public function getIndex()
	{
		$posts = Post::all();//Will replace with actual model once its built.
		$this->layout->content = View::make('home.index')->with('posts',$posts);
	}

	//Our Story Type Page
	public function getAbout()
	{
		$this->layout->content = View::make('home.about');
	}
	
}