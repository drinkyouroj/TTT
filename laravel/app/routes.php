<?php

/**
 * Routing for the TTT!
 */


/**
 *	Rest API 
 * 	This thing is kind of a bastard of a REST API.
 */

//Protected rest controllers
Route::group(array('prefix' => 'rest', 'before' => 'auth'), function()
{
	
	//Binary action controllers aka minimal information is passed (follow, fav, repost)  Index is used to list followers, etc.
	$binary_limits = array('only'=>array('index','create','show','destroy'));
		
	/*Page Actions**********************************/
	//FollowingController shows the people that follow you.
	Route::resource('following', 'FollowingRestController',$binary_limits);//folks you're following.
	Route::resource('followers', 'FollowersRestController',$binary_limits);//folks following you: your followers
	
	//Page Actions
	Route::resource('likes', 'LikeRestController', array('only'=>array('index','show','store')) );
	Route::resource('favorites', 'FavoriteRestController', array('only'=>array('index','show','store')));
	Route::resource('follows', 'FollowRestController', array('only'=>array('index','show','store')));
	Route::resource('reposts', 'RepostRestController',$binary_limits);
	Route::resource('comments', 'CommentRestController', $binary_limits);
	Route::resource('posts', 'PostRestController', $binary_limits);
	Route::resource('feature', 'FeatureRestController', $binary_limits);
	
	//Notification Action
	Route::resource('notification', 'NotificationsRestController', array('only'=>array('index','show','store')) );
		
	//Profile Image for the Follower/Following buttons. TODO This will go away soon after we create a more robust system
	Route::resource('profileimage', 'ProfileImageRestController', $binary_limits);
	
	/**Post Input Systems***************************/
	//Title Checker
	Route::resource('posttitle', 'PostTitleRestController', array('only'=>array('index')));//For Post input.
	
	//Flickr!!!!!
	Route::resource('flickr', 'FlickrRestController', array('only'=>array('index','show')));//API wrapper

	//Photo Processor
	Route::resource('photo', 'PhotoRestController', array('only'=>array('index','store','show')) );
});

//Unprotected rest controllers
Route::group(array('prefix'=>'rest'), function() {
	//Autoload routes
	//Category autload
	Route::get( 'categories/{alais}/{sort}', 'CategoryController@getRestCategory');
	Route::get( 'categories/{alais}', 'CategoryController@getRestCategory');
	//Profile autload
	Route::get( 'profile/{alias}', 'ProfileController@getRestProfile');
	Route::get( 'profile', 'ProfileController@getRestProfile');
	//Auto Load of for featured.
	Route::get( 'featured', 'HomeController@getRestFeatured');
});


//Admin area
Route::group(array('prefix'=> 'admin', 'before'=> 'admin'), function() {
	Route::get('solr', 'AdminController@getResetSolr');//this updates the users on solr
	Route::get('resetnot/{batch}', 'AdminController@getResetNotifications');//this updates the users on solr
	Route::controller('/','AdminController');	
});

//Mod area
Route::group(array('prefix'=> 'mod', 'before'=> 'mod'), function() {
	Route::get('delpost/{id}','ModController@getDelPost');
	Route::get('delcomment/{id}','ModController@getDelComment');
	Route::get('ban/{id}','ModController@getBan');
	Route::get('/','ModController@getIndex');
});

/********************The Authentication Routes  (Confide RESTful route)************************/
Route::group(array('prefix'=> 'user'), function() {
	Route::get('confirm/{code}', 'UserController@getConfirm');
	Route::get('reset/{token}', 'UserController@getReset');
	Route::get('restore/{id}', 'UserController@getRestore');
	Route::get('check', 'UserController@getUserCheck');
	Route::get('forgot', 'UserController@getForgot');
	Route::controller( '/', 'UserController');
});


/********************Normal non rest controllers********************************************/
//Category routes
Route::group(array('prefix'=>'categories'),function() {
	Route::get( '{alais}/{sort}', 'CategoryController@getCategory');
	Route::get( '{alais}', 'CategoryController@getCategory');	
});


//Posts routes
Route::get( 'posts/{alias}', 'PostController@getPost');
Route::get( 'posts', 'PostController@getIndex');//grabs a random post

//Search routes
Route::get('search/{term}', 'SearchController@getResult');//Might turn into a rest system later
Route::post('search', 'SearchController@postResult');


//Protected Profile routes
Route::group(array('prefix'=> 'profile', 'before'=> 'profile|auth'), function() 
{
	Route::get( 'editpost/{id}', 'PostController@getPostForm');
	Route::get( 'newpost', 'PostController@getPostForm');
	
	Route::post( 'submitpost', 'PostController@postPostForm');
	
	//All Notifications
	Route::get( 'notifications', 'ProfileController@getNotifications');
	
	//My Posts
	Route::get( 'myposts', 'ProfileController@getMyPosts');
	
	//My Settings
	Route::get( 'settings', 'ProfileController@getSettings');
	
	//Comments
	Route::get( 'commentform/{post_id}/{reply_id}', 'CommentController@getCommentForm');//This is for getting the reply forms.
	Route::post( 'comment/{post_id}', 'CommentController@postCommentForm');
	
	//Messages
	Route::get( 'replymessage/{reply_id}', 'MessageController@getMessageReplyForm');
	Route::get( 'newmessage/{user_id}', 'MessageController@getMessageForm');
	Route::get( 'newmessage', 'MessageController@getMessageForm');
	
	Route::post( 'submitmessage', 'MessageController@postMessageForm');
	Route::get( 'messages', 'MessageController@getMessageInbox');
		
});

//Not protected profile routes
Route::group(array('before'=>'profile'), function() {
	//General Posts
	Route::get( 'profile/{alias}', 'ProfileController@getProfile');
	Route::get( 'profile', 'ProfileController@getProfile');
	
});

//For banned users to see when try try to log in.
Route::get('banned', 'UserController@getBanned');



//The featured page (since it wasn't part of the categories)
Route::get( 'featured', 'HomeController@getIndex');

//The front page.
Route::controller( '/', 'HomeController');



