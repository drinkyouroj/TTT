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


//Rest API
Route::group(array('prefix' => 'rest', 'before' => 'auth'), function()
{
	//Meat of the Application
	Route::resource('posts', 'PostController');
    Route::resource('categories', 'CategoryController');
	Route::resource('profiles', 'ProfileController');
	Route::resource('comments', 'CommentController');
	Route::resource('messages', 'MessageController');
	
	//Binary action controllers aka minimal information is passed (follow, fav, repost)
	$binary_limits = array('only'=>array('index','create','show','destroy'));
	Route::resource('follows', 'FollowController',$binary_limits);//let's limit the useable controller functions
	Route::resource('favorites', 'FavoriteController',$binary_limits);
	Route::resource('reposts', 'RepostController',$binary_limits);
	
});

//Admin protection.
//Route::when('admin/*', 'admin');
//Route::controller('admin','AdminController');

//The Authentication Routes  (Confide RESTful route)
Route::get('user/confirm/{code}', 'UserController@getConfirm');
Route::get('user/reset/{token}', 'UserController@getReset');
Route::controller( 'user', 'UserController');

//Home and about pages
Route::controller( '/', 'HomeController');