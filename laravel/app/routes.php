<?php
/**
 * Routing for the TTT!
 */

/**
 *	JSON API 
 * 	This thing is kind of a bastard of a REST API.
 */

//Protected rest controllers
Route::group(array('prefix' => 'rest', 'before' => 'auth'), function()
{	
	// Route for MyProfile
	Route::group(array('prefix' => 'profile'), function() {
		Route::get( 'feed/{feed_type}/{page}', 'MyProfileController@getRestFeed' );
		Route::get( 'saves/{page}', 'MyProfileController@getRestSaves' );
		Route::get( 'drafts/{page}', 'MyProfileController@getRestDrafts' );
		Route::post( 'image/upload', 'MyProfileController@postAvatar');
		Route::post( 'password', 'MyProfileController@postChangePassword');
	});

	//Profile Image for the Follower/Following buttons.
	Route::resource('profileimage', 'ProfileImageRestController', array('only'=>array('show')));
	
	/**Post Input Systems***************************/	
	//Flickr!!!!!
	Route::resource('flickr', 'FlickrRestController', array('only'=>array('index','show')));//API wrapper

	//Photo Processor
	Route::resource('photo', 'PhotoRestController', array('only'=>array('index','store','show')) );

	//Post Save Route.
	Route::post('savepost','PostController@postSavePost');

	// Comment post route
	Route::post('comment', 'CommentController@postRestComment');

});

//Unprotected rest controllers
Route::group(array('prefix'=>'rest'), function() {
	//Route for Public Profile functions.
	Route::group(array('prefix' => 'profile'), function() {
		Route::get( 'collection/{type}/{user_id}/{page}','MyProfileController@getRestCollection');
		Route::get( 'followers/{user_id}/{page}', 'MyProfileController@getRestFollowers');
		Route::get( 'following/{user_id}/{page}', 'MyProfileController@getRestFollowing');
		Route::get( 'comments/{user_id}/{page}', 'MyProfileController@getRestComments');
	});
	
	//Category autload
	Route::get( 'categories/{alais}/{sort}/{page}', 'CategoryController@getRestCategory');
	Route::get( 'categories/{alais}/{sort}', 'CategoryController@getRestCategory');
	Route::get( 'categories/{alais}', 'CategoryController@getRestCategory');

	//Profile autload
	Route::get( 'profile/{alias}', 'ProfileController@getRestProfile');
	Route::get( 'profile', 'ProfileController@getRestProfile');

	//Auto Load of for featured.
	Route::get( 'featured', 'HomeController@getRestFeatured');

	Route::get( 'post/{post_id}/comments/{paginate}/{page}', 'CommentController@getRestComments');
});

//Note, below JSONController route has to be below the abouve REST route Group.
Route::group(array('before' => 'auth'), function() {
	Route::controller('rest','JSONController');
});

// Routes for users profile
Route::group( array('prefix'=>'myprofile', 'before' => 'auth' ), function() {
	Route::get( 'editpost/{id}', 'PostController@getPostForm');
	Route::get( 'newpost', 'PostController@getPostForm');
	Route::get('/{alias}', 'MyProfileController@getPublicProfile');

	Route::get('/', 'MyProfileController@getMyProfile');
	//Route::controller( 'myprofile', 'MyProfileController' );
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

/********************The Authentication Routes  (Confide routes)************************/
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
	/*
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
	*/
});

//Not protected profile routes
Route::group(array('before'=>'profile'), function() {
	//General Posts
	Route::get( 'profile/{alias}', 'MyProfileController@getPublicProfile');
	Route::get( 'profile', 'ProfileController@getProfile');
	
});

//For banned users to see when try try to log in.
Route::get('banned', 'UserController@getBanned');

//The featured page (since it wasn't part of the categories)
Route::get( 'featured', 'HomeController@getIndex');

//The front page.
Route::controller( '/', 'HomeController');
