<?php

App::missing(function($exception) {
	return Response::view('v2.layouts.missing',array(), 404);
});

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/
Blade::extend(function($value) {
	//The best piece of code for Blade ever.  Lets us break all kinds of rules!
	return preg_replace('/\{\?(.+)\?\}/', '<?php ${1} ?>', $value);
});


App::before(function($request)
{	
	if (Auth::check() && !Session::has('user_id'))
	{
	   return Redirect::to('user/logout');
	}
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/
/*
Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::guest('login');
});
*/

Route::filter('auth', function()
{
    if ( Auth::guest() ) // If the user is not logged in
    {
        // Set the loginRedirect session variable
        Session::put( 'loginRedirect', Request::url() );

        // Redirect back to user login
        return Redirect::to( '/' );
    }
});


Route::filter('auth.basic', function()
{
	return Auth::basic('username');
});

Route::filter('admin', function()
{
    if (! Entrust::hasRole('Admin') ) // Checks the current user
    {
        return Redirect::to('/');
    }
});

Route::filter('mod', function()
{
    if (! Entrust::hasRole('Moderator') ) // Checks the current user
    {
        return Redirect::to('/');
    }
});


/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
	if (Auth::check()) return Redirect::to('/');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() != Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});



/** 
 * View Composer
 * 
 * This is called everytime any view is rendered.  Most of the views in our site require the below right now.
 * This will need to be figured out later as the view composer is unable to figure out how to reach the layouts.master.blade.php file 
 */

View::composer('*', function($view) {
	//Below is the filters for the Categories.  Its stored here since we needed to iterate through to see what is or is not active.
	//We can probably make this an admin function later.
	$filters = array(
					'popular'=> 'Most Popular',
					'recent' => 'Most Recent',
					'viewed' => 'Most Viewed',
					'discussed' => 'Most Discussed',
					/*
					'longest' => 'Longest',
					'shortest' => 'Shortest'
					 */ 
					);

	//Grab all the categories
	$category = App::make('AppStorage\Category\CategoryRepository');
	$categories = $category->all();

	if(!Auth::guest()) {
		$user = Auth::user();
		// ===================== ITEMS FOR THE SIDEBAR =====================
		$favRep = App::make('AppStorage\Favorite\FavoriteRepository');
		$saves = $favRep->allByUserId( $user->id, 6 );



		//The new Mongo notifications
		$compiled = NotificationLogic::top( $user->id );
		// Count of how many unread notifications the user has
		$notification_count = NotificationLogic::getUnreadCount( $user->id );

		//Unfortunately, we'll have to do this for now.
		$notification_ids = array();
		foreach($compiled as $k => $nots) {
			$notification_ids[$k] = $nots->_id;
		}
		
		$user_image = $user->image ? 'uploads/final_images/'.$user->image : 'images/profile/avatar-default.png';

		$view->with('categories',$categories)
			 ->with('filters', $filters)
			 ->with('notifications', $compiled)
			 ->with('notification_count', $notification_count)
			 ->with('saves', $saves)
			 ->with('notifications_ids', $notification_ids)
			 ->with('user_image', $user_image);
			 
	} else {
		//Guests
		$view->with('categories', $categories )
			 ->with('filters', $filters);
	}
	
	if(Request::segment(1) == 'profile') {
		$alias = Request::segment(2);
		
		//Unfortunately view composer currently sucks at things so this is a crappy work around.
		$not_segment = array(
					Session::get('username'),
					'newpost',
					'editpost',
					'newmessage',
					'replymessage',
					'submitpost',
					'comment',
					'commentform',
					'messages',
					'submitmessage',
					'notifications',
					'myposts',
					'settings'
					);
		
		if($alias && !in_array($alias, $not_segment) ) {//This is for other users. not yourself
			$user = User::where('username', '=', $alias)->first();
			// If you are mod or admin, you may view soft deleted user profiles as well
			if ( !is_object($user) && Auth::check() && Auth::user()->hasRole('Moderator') ) {
				$user = User::withTrashed()->where('username', '=', $alias)->first();
			}
			$user_id = $user->id;//set the profile user id for rest of the session.
		} else {
			//We're doing the user info loading this way to keep the view clean.
			$user_id = Session::get('user_id');
			
			//This isn't most ideal, but let's just place the banned detector here
			if(User::where('id', $user_id)->where('banned', true)->count()) {
				//this guy's session will terminate right as he/she clicks on any profile action.
				Auth::logout();
				return Redirect::to('/banned');
			}
		}
		

		$follow = App::make('AppStorage\Follow\FollowRepository');

		$followers = $follow->follower_count($user_id);
		$following = $follow->following_count($user_id);
		
		$view->with('followers', $followers)
			 ->with('following', $following);
	}

	// Admin/Moderator
	$is_mod = Session::get('mod');
	$is_admin = Session::get('admin');
	if ( $is_mod || $is_admin ) {

		$flagged = App::make('AppStorage\FlaggedContent\FlaggedContentRepository');
		$users_rep = App::make('AppStorage\User\UserRepository');
		$post_rep = App::make('AppStorage\Post\PostRepository');
		// Include the flagged content
		$flagged_post_content = $flagged->getFlaggedOfType( 'post' );
		$flagged_comment_content = $flagged->getFlaggedOfType( 'comment' );
		// Include some stats
		$num_users = $users_rep->getUserCount();
		$num_confirmed_users = $users_rep->getConfirmedUserCount();
		$num_users_created_today = $users_rep->getUserCreatedTodayCount();

		$num_published_posts = $post_rep->getPublishedCount();
		$num_published_posts_today = $post_rep->getPublishedTodayCount();
		$num_drafts_today = $post_rep->getDraftsTodayCount();

		// Check if we are on user and/or post page (additional functionalities will be given)
		$seg = Request::segment(1);
		if( $seg == 'profile') {
			$view->with( 'is_profile_page', true );
		} else if ( $seg == 'posts') {
			$view->with( 'is_post_page', true );
		}
		$view->with( 'flagged_post_content', $flagged_post_content )
			 ->with( 'flagged_comment_content', $flagged_comment_content )
			 ->with( 'num_users', $num_users )
			 ->with( 'num_confirmed_users', $num_confirmed_users )
			 ->with( 'num_users_created_today', $num_users_created_today )
			 ->with( 'num_published_posts', $num_published_posts )
			 ->with( 'num_published_posts_today', $num_published_posts_today )
			 ->with( 'num_drafts_today', $num_drafts_today );


	}
	
});



/**
 * Below are Entrust Filters for the admin system
 */
 


