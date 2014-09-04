<?php

class HomeController extends BaseController {
	
	private $paginate = 8;//pagination for the front page is set to 8 since it worked out better with the packery blocks.
	
	public function __construct(
							FeaturedRepository $featured,
							EmailRepository $email
							){
		$this->featured = $featured;
		$this->email = $email;
	}


	/**
	 * The featured page.
	 */
	public function getIndex()
	{
		$featured = $featured = $this->featured->find($this->paginate, 1, false);
		
		return View::make('home.index')
					->with('featured',$featured);
	}
	
	//This is a little weird fix to the invitation system since it posts to the Index and needs to be redirected.
	public function postIndex() 
	{
		return Redirect::to('featured');
	}
	
	/**
	 * The featured page autoload. 
	 */
	public function getRestFeatured() 
	{
		
		//set the default if page is not passed to you.
		if(Request::get('page')) {
			$page = abs(Request::get('page'));//just to be sure.
		} else {
			$page = 1;
		}
		
		$featured = $this->featured->find($this->paginate, $page, true);
		
		
		if(!count($featured)) {
			return Response::json(
				array('error' => true),
				200
			);
		} else {
			return Response::json(
				array('featured'=>$featured->toArray()),
				200
			);
		}
	}
	

	/**
	 * Static Pages below
	 */
	
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