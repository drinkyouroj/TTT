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
	//
	Blade::extend(function($value) {
    	return preg_replace('/\{\?(.+)\?\}/', '<?php ${1} ?>', $value);
	});
	
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
        return Redirect::to( 'user/login' );
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

//This will need to be figured out later as the view composer is unable to figure out how to reach the layouts.master.blade.php file
View::composer('*', function($view) {
	if(!Auth::guest()) {
		$notifications = Notification::where('user_id', '=', Session::get('user_id'))
										->where('noticed', '=', 0)
										->take(30)//This is an artificial limit. I think the limit should be set by a 5 day limit instead.
										->orderBy('created_at', 'DESC')
										->get();
		
		//Shared function for re-ordering the notifications per initial ID and per type.
		$compiled = NotificationParser::parse($notifications);
		
		$view->with('categories', Category::all())
			 //->with('notifications', $notifications)
			 ->with('notifications', $compiled);
			 
	} else {
		$view->with('categories', Category::all());
		
	}
});



/**
 * Below are Entrust Filters for the admin system
 */
 


