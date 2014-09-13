<?php

use \Carbon\Carbon;

class HomeController extends BaseController {
	
	private $paginate = 8;//pagination for the front page is set to 8 since it worked out better with the packery blocks.
	
	public function __construct(
							FeaturedRepository $featured,
							FeedRepository $feed,
							EmailRepository $email
							){
		$this->featured = $featured;
		$this->feed = $feed;
		$this->email = $email;
	}

	/**
	 * The featured page.
	 */
	public function getIndex()
	{
		//return Redirect::to('categories/all');
		//This page will get hit the hardest.  It has caching.
		
		if(Cache::has('featured') && !Session::get('admin') ) {
			$featured = Cache::get('featured');
		} else {
			$featured = $this->featured->findFront();

			$expiresAt = Carbon::now()->addMinutes(10);

			Cache::put('featured',$featured,$expiresAt);
		}
		$view = View::make('v2/featured/featured')
						->with('featured', $featured);

		if(Auth::check()) {
			$user = Auth::user();
			$from_feed = $this->feed->findOne($user->id, 'post');
			$view->with('from_feed', $from_feed);
		} else {
			$view->with('from_feed', false);
		}

		return $view;
		
	}
	
	//This is a little weird fix to the invitation system since it posts to the Index and needs to be redirected.
	public function postIndex() 
	{
		return View::make('v2.static.beta');
		//return Redirect::to('featured');
	}
	
	/**
	 * The featured page autoload. 
	 */
	public function getRestFeatured($page = 1 )
	{
		
		$featured = $this->featured->find(6, $page, true);
		
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
	* Error Form
	*/
	public function getErrorForm()
	{
		return View::make('v2.errors.form');
	}

	/**
	 * Static Pages below
	 */
	
	public function getAbout()
	{
		return View::make('v2.static.about');
	}
	
	public function getEtiquette()
	{
		return View::make('v2.static.etiquette');
	}
	
	public function getPrivacy()
	{
		return View::make('v2.static.privacy');
	}
	
	public function getContact()
	{
		return View::make('v2.static.contact');
	}
	
	public function getTerms()
	{
		return View::make('v2.static.terms');
	}
	
}