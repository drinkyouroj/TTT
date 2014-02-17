<?php

class HomeController extends BaseController {
	//The slash!
	public function getIndex()
	{	
		$featured = Featured::get();
		
		return View::make('home.index')
					->with('featured',$featured)
					;
	}

	//Our Story Type Page
	public function getAbout()
	{
		return View::make('static.about');
	}
	
	public function getPrivacy()
	{
		return View::make('static.privacy');
	}
	
	public function getContact()
	{
		return View::make('static.contact');
	}
	
	public function getTerms()
	{
		return View::make('static.terms');
	}
	
}