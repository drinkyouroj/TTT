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



/************
 *	Rest API 
 * 	This thing is kind of a bastard of a REST API.
 *  I'm just trying to make it work with Ember.js Which is kind of finicky when it comes to the JSON that it wants.
 * */
 /*
Route::group(array('prefix' => 'rest', 'before' => 'auth'), function()
{
	//Meat of the Application
	Route::resource('posts', 'PostRestController');
    Route::resource('categories', 'CategoryRestController');
	Route::resource('profiles', 'ProfileRestController');//This controller happens to be called "ProfileController" since the "UserController" is used by Confide
	Route::resource('comments', 'CommentRestController');
	Route::resource('messages', 'MessageRestController', array('except'=>array('delete')));
	Route::resource('sent', 'SentRestController', array('only'=>array('index','show')));//a controller for getting sent messages
	
	//Binary action controllers aka minimal information is passed (follow, fav, repost)  Index is used to list followers, etc.
	$binary_limits = array('only'=>array('index','create','show','destroy'));
	
	//Followers, Followees  (FollowController's index shows the people you follow)
	Route::resource('follows', 'FollowRestController',$binary_limits);//let's limit the useable controller functions
	//FollowingController shows the people that follow you.
	Route::resource('following', 'FollowingRestController',array('only'=>array('index')));
	
	//Post Favorites
	Route::resource('favorites', 'FavoriteRestController',$binary_limits);
	
	//Repost them posts.
	Route::resource('reposts', 'RepostRestController',$binary_limits);
	
	//Flickr!!!!!
	Route::resource('flickr', 'FlickrRestController', array('only'=>array('index','show')));
});
*/

//Admin protection.
//Route::when('admin/*', 'admin');
//Route::controller('admin','AdminController');



/********************The Authentication Routes  (Confide RESTful route)************************/
Route::get('/user/confirm/{code}', 'UserController@getConfirm');
Route::get('/user/reset/{token}', 'UserController@getReset');
Route::controller( '/user', 'UserController');

/********************Normal non rest controllers********************************************/
//Category routes
Route::get( '/categories/{alais}', 'CategoryController@getCategory');

//Posts routes
Route::get( '/posts/{alias}', 'PostController@getPost');

//Profile routes
Route::get( '/profile/editpost/{id}', 'ProfileController@getPostForm');
Route::get( '/profile/newpost', 'ProfileController@getPostForm');
Route::post( '/profile/submitpost', 'ProfileController@postPostForm');
Route::get( '/profile/newmessage', 'ProfileController@getMessageForm');
Route::get( '/profile/{alias}', 'ProfileController@getProfile');
Route::get( '/profile', 'ProfileController@getProfile');

//
Route::controller( '/', 'HomeController');



