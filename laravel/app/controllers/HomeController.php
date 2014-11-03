<?php

use \Carbon\Carbon;

class HomeController extends BaseController {
	
	private $paginate = 8;//pagination for the front page is set to 8 since it worked out better with the packery blocks.
	
	public function __construct(
							FeaturedRepository $featured,
							FeedRepository $feed,
							EmailRepository $email,
							PostRepository $post,
							FollowRepository $follow,
							FeaturedUserRepository $featureduser
							){
		$this->featured = $featured;
		$this->feed = $feed;
		$this->email = $email;
		$this->post = $post;
		$this->follow = $follow;
		$this->featureduser = $featureduser;
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

		if(Cache::has('featureduser') && !Session::get('admin') ) {
			$fuser = Cache::get('featureduser');
		} else {
			$fuser = $this->featureduser->find();
			$expiresAt = Carbon::now()->addMinutes(10);
			Cache::put('featureduser',$fuser,$expiresAt);
		}
		if ( is_object($fuser) ) {
			$post_count = $this->post->countUserPublished($fuser->user_id);
			$fuser_recent = $this->post->lastPostUserId($fuser->user_id, true);
		} else {
			$post_count = 0;
			$fuser_recent = null;
		}
		$view = View::make('v2/featured/featured')
						->with('featured', $featured)
						->with('fuser', $fuser)
						->with('post_count', $post_count)
						->with('fuser_recent',$fuser_recent)
						;

		if(Auth::check()) {
			$user = Auth::user();
			$from_feed = $this->feed->findOne($user->id, 'post');
			if(	
				isset($from_feed->post) &&
				$from_feed->post->deleted_at 
			   ) {
				$view->with('from_feed', $from_feed->post );
			} else {
				$random = $this->featured->random();
				$view->with('from_feed', $random->post);
			}
			if(is_object($fuser)) {
				$fuser_follow = $this->follow->is_following($user->id, $fuser->user_id);
				$view->with('fuser_follow', $fuser_follow);				
			} else {
				$view->with('fuser_follow', false);
			}
		} else {
			$view->with('from_feed', false)
				 ->with('fuser_follow', false);
		}

		//Get the randomized featured lis
		$randoms = $this->featured->allByRandom(12);
		$view->with('randoms',$randoms);

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
		
		$featured = $this->featured->find(8, $page, true);
		
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

	public function getNamechange() {
		return View::make('v2.static.namechange');
	}
	
}