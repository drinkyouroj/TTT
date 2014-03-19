<?php


App::missing(function($exception) {
	return Response::view('missing',array(), 404);
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

App::before(function($request)
{
	//The best piece of code for Blade ever.  Lets us break all kinds of rules!
	Blade::extend(function($value) {
    	return preg_replace('/\{\?(.+)\?\}/', '<?php ${1} ?>', $value);
	});
	
	
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


Route::filter('profile', function() {
	View::composer('*', function($view) {
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
						'myposts'
						);
			
			if($alias && !in_array($alias, $not_segment) ) {//This is for other users. not yourself
				$user = User::where('username', '=', $alias)->first();
				$user_id = $user->id;//set the profile user id for rest of the session.
			} else {
				//We're doing the user info loading this way to keep the view clean.
				$user_id = Session::get('user_id');
				
				//This isn't most ideal, but let's just place the banned detector here
				if(User::where('id', $user_id)->where('banned', true)->count()) {
					//this guy's session will terminate right as he/she clicks on any profile action.
					Confide::logout();
					return Redirect::to('/banned');
				}
			}
			
			$followers = Follow::where('user_id', '=', $user_id)->count();
			$following = Follow::where('follower_id', '=', $user_id)->count();
			
			$view->with('followers', $followers)
					->with('following', $following);
		});
	
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

//This will need to be figured out later as the view composer is unable to figure out how to reach the layouts.master.blade.php file
View::composer('*', function($view) {
	if(!Auth::guest()) {
		$notifications = Notification::where('user_id', '=', Session::get('user_id'))
										->where('noticed', '=', 0)
										->take(30)//This is an artificial limit. I think the limit should be set by a 5 day limit instead.
										->orderBy('created_at', 'DESC')
										->get();
		
		$notification_ids = array();
		foreach($notifications as $k => $nots) {
			$notification_ids[$k] = $nots->id;
		}
		
		//Shared function for re-ordering the notifications per initial ID and per type.
		$compiled = NotificationParser::parse($notifications);
		
		$view->with('categories', Category::all())
			 //->with('notifications', $notifications)
			 ->with('notifications', $compiled)
			 ->with('notifications_ids', $notification_ids);
			 
	} else {
		$view->with('categories', Category::all());
		
	}
});



/**
 * Below are Entrust Filters for the admin system
 */
 


