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
 
Route::group(array('prefix' => 'rest', 'before' => 'auth'), function()
{
	
	//Binary action controllers aka minimal information is passed (follow, fav, repost)  Index is used to list followers, etc.
	$binary_limits = array('only'=>array('index','create','show','destroy'));
	
	//Followers, Followees  (FollowController's index shows the people you follow)
	//let's limit the useable controller functions
	
	
	/*Page Actions**********************************/
	//FollowingController shows the people that follow you.
	Route::resource('following', 'FollowingRestController',$binary_limits);//folks you're following.
	Route::resource('followers', 'FollowersRestController',$binary_limits);//folks following you: your followers
	
	//Page Actions
	Route::resource('likes', 'LikeRestController', array('only'=>array('index','show','store')));
	Route::resource('favorites', 'FavoriteRestController', array('only'=>array('index','show','store')));
	Route::resource('follows', 'FollowRestController', array('only'=>array('index','show','store')));
	Route::resource('reposts', 'RepostRestController',$binary_limits);
	Route::resource('comments', 'CommentRestController', $binary_limits);
	Route::resource('posts', 'PostRestController', $binary_limits);
	Route::resource('feature', 'FeatureRestController', $binary_limits);
	
	
	/**Post Input Systems***************************/
	//Title CHKR
	Route::resource('posttitle', 'PostTitleRestController', array('only'=>array('index')));//For Post input.
	
	//Flickr!!!!!
	Route::resource('flickr', 'FlickrRestController', array('only'=>array('index','show')));//API wrapper

	//Photo Processor
	Route::resource('photo', 'PhotoRestController', array('only'=>array('index','store','show')) );
	
	//Profile Image
	Route::resource('profileimage', 'ProfileImageRestController', $binary_limits);
	
});


//Admin protection.
Route::when('admin', 'admin');//Role based route filtering
Route::when('admin/*', 'admin');//Role based route filtering
Route::controller('/admin','AdminController');

//Mod protection
Route::when('mod', 'mod');//Role based route filtering
Route::when('mod/*', 'mod');//Role based route filtering
Route::get('mod/delpost/{id}','ModController@getDelPost');
Route::get('mod/delcomment/{id}','ModController@getDelComment');
Route::get('mod/ban/{id}','ModController@getBan');
Route::get('mod','ModController@getIndex');

/********************The Authentication Routes  (Confide RESTful route)************************/
Route::get('/user/confirm/{code}', 'UserController@getConfirm');
Route::get('/user/reset/{token}', 'UserController@getReset');
Route::controller( '/user', 'UserController');

/********************Normal non rest controllers********************************************/
//Category routes
Route::get( '/categories/{alais}/{sort}', 'CategoryController@getCategory');
Route::get( '/categories/{alais}', 'CategoryController@getCategory');

//Posts routes
Route::get( '/posts/{alias}', 'PostController@getPost');

//Search routes
Route::get('/search/{term}', 'SearchController@getResult');//Might turn into a rest system later
Route::post('/search', 'SearchController@postResult');

//Profile routes (handles 90% of text based inputs)
//Posts
Route::get( '/profile/editpost/{id}', 'ProfileController@getPostForm');
Route::get( '/profile/newpost', 'ProfileController@getPostForm');
Route::post( '/profile/submitpost', 'ProfileController@postPostForm');

//Notifications
Route::get( '/profile/notifications', 'ProfileController@getNotifications');

//My Posts
Route::get( '/profile/myposts', 'ProfileController@getMyPosts');


//Comments
Route::get( '/profile/commentform/{post_id}/{reply_id}', 'ProfileController@getCommentForm');//This is for getting the reply forms.
Route::post( '/profile/comment/{post_id}', 'ProfileController@postCommentForm');


//Messages
Route::get( '/profile/replymessage/{reply_id}', 'ProfileController@getMessageReplyForm');
Route::get( '/profile/newmessage/{user_id}', 'ProfileController@getMessageForm');

Route::post( '/profile/submitmessage', 'ProfileController@postMessageForm');
Route::get( '/profile/messages', 'ProfileController@getMessageInbox');

//General Posts
Route::get( '/profile/{alias}', 'ProfileController@getProfile');
Route::get( '/profile', 'ProfileController@getProfile');


Route::get('/banned', 'UserController@getBanned');


//
Route::controller( '/', 'HomeController');



