<?php

class HomeController extends BaseController {
	
	private $paginate = 12;
	
	//The slash!
	public function getIndex()
	{	
		$featured = Featured::orderBy('order', 'ASC')
							->take($this->paginate)
							->get();
		
		return View::make('home.index')
					->with('featured',$featured)
					;
	}
	
	public function getRestFeatured() {
		
		if(Request::get('page')) {
			$page = abs(Request::get('page'));//just to be sure.
		} else {
			$page = 1;
		}
		
		$featured = Featured::orderBy('order', 'ASC')
							->with('post.user')
							->skip(($page-1)*$this->paginate)
							->take($this->paginate)
							->get();
		
		if(!count($featured)) {
			return Response::json(
				array('error' => true),
				200
			);
		} else {
			//We'll do the below for now so that the app runs faster at this point.  We'll re-do the client side rendering when time allows.
			/*return View::make('partials.feature-render')
					->with('featured',$featured);
			 * 
			 */
			return Response::json(
				$featured->toArray(),
				200
			);
		}
	}
	
	//This is a little weird fix to the invitation system.
	public function postIndex() {
		return Redirect::to('featured');
	}

	//Our Story Type Page
	public function getAbout()
	{
		return View::make('static.about');
	}
	
	public function getEtiquette()
	{
		return View::make('static.etiquette');
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