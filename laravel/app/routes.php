<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

//Normal Pages
Route::get('/', 'HomeController@index');
Route::get('about', 'HomeController@about');

//Logged in Views
Route::get('profile', 'HomeController@profile');


//Rest API
Route::group(array('prefix' => 'rest', 'before' => 'auth.basic'), function()
{
	//Meat of the Application
	Route::resource('post', 'PostController');
    Route::resource('category', 'CategoryController');
	Route::resource('profile', 'ProfileController');
	Route::resource('comment', 'CommentController');
	
	//Binary action controllers aka minimal information is passed (follow, fav, repost)
	$binary_limits = array('only'=>array('index','create','show','destroy'));
	Route::resource('follow', 'FollowController',$binary_limits);//let's limit the useable controller functions
	Route::resource('favorite', 'FavoriteController',$binary_limits);
	Route::resource('repost', 'RepostController',$binary_limits);
	
});

//The Authentication Routes  (Confide RESTful route)
Route::get('user/confirm/{code}', 'UserController@getConfirm');
Route::get('user/reset/{token}', 'UserController@getReset');
Route::controller( 'user', 'UserController');

