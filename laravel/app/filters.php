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
	$have_user = Auth::check();
	if($have_user && Auth::user()->deleted_at) {
		Auth::logout();
		Session::flush();
		Session::regenerate();
		return Redirect::to('user/logout');
	}
	if ($have_user && !Session::has('user_id'))
	{
	   return Redirect::to('user/logout');
	}
	
	if ($have_user && Request::segment(1) != 'rest' ) {
		AnalyticsLogic::createSessionEngagement( 'navigate', Request::path() );
	}

	//This is meant for tracking sessions since the sessionId from laravel changes everytime.
	if (!Session::has('current_session')) {
		Session::put('current_session', str_random(40));
	}

	$contents = File::get(base_path().'/gitversion');
	$version =str_replace("\n", "", $contents);//gotta get rid of the returns.
	View::share('version', $version);

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

Route::filter('force_ssl',function() {
	//detect ec2 situation first.
	if(	( App::environment() == 'prod' || App::environment() == 'stage' ) &&
		!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
		$_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https'
		) {
		//do nothing.
		//return Redirect::to(Request::path());//
	} else {
		if( ! Request::secure() && Config::get('app.enable_ssl') )
	    {
	        return Redirect::secure(Request::path());
	    }	
	}
});

Route::filter('cors_allow',function() {
	header('Access-Control-Allow-Origin: '.Config::get('app.url') );
	header('Access-Control-Allow-Methods: GET');
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
 * View Composers
 *  IMPORTANT NOTE: Laravel is stupid at using slash or dot on the layout paths so try both if one or the other is not working.
 */
$category_views = array(
							'v2/category/category',
							'v2/featured/featured-trending',
							'v2.layouts.header',
							'v2/partials/category-listing',
							'v2/posts/post_form'
						);

View::composers(array(
	'AdminModComposer' => 'v2.layouts.admin-moderator',
	'HeaderComposer' => 'v2.layouts.header',
	'FooterComposer' => 'v2.layouts.footer',
	'CategoryComposer' => $category_views,
	'PostComposer' => 'v2/posts/post',
	'ProfileComposer' => 'v2/myprofile/profile'
));