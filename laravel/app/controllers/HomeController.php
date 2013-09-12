<?php

class HomeController extends BaseController {

	//Gonna be using Blade
	protected $layout = 'layouts.master';

	//The slash!
	public function index()
	{
		$this->layout->content = View::make('home.index');
	}

	public function about()
	{
		$this->layout->content = View::make('home.about');
	}

}