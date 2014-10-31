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
		Route::get( 'saves/delete/{post_id}', 'MyProfileController@getRestSaveDelete' );
		Route::get( 'saves/{page}', 'MyProfileController@getRestSaves' );
		Route::get( 'notifications/{page}', 'MyProfileController@getRestNotifications' );

		Route::get( 'drafts/{page}', 'MyProfileController@getRestDrafts' );
		//below 2 are delete functions
		Route::get( 'repost/{id}', 'MyProfileController@getRestRepostDelete' );
		Route::get( 'post/{id}', 'MyProfileController@getRestPostDelete' );

		Route::post( 'featured/{post_id}', 'MyProfileController@postRestFeatured');
		Route::post( 'image/upload', 'MyProfileController@postAvatar');
		Route::post( 'password', 'UserController@postNewpass'); //I know its not in myprofile!
		Route::post( 'email/update', 'MyProfileController@postUpdateEmail');
		Route::post( 'email/pref', 'MyProfileController@postRestEmailPref');
		Route::get( 'settings', 'MyProfileController@getRestSettings');
	});

	//Profile Image for the Follower/Following buttons.
	Route::resource('profileimage', 'ProfileImageRestController', array('only'=>array('show')));
	
	/**Post Input Systems***************************/	
	//Flickr!!!!!
	Route::get('flickr/{photo_id}', 'PhotoController@getPhoto');//For grabbing the Photo License info.
	Route::get('flickr', 'PhotoController@getPhotoSearch');
	Route::get('photo', 'PhotoController@getProcessPhoto');//Photo processor

	//Post Save Route.
	Route::post('savepost','PostController@postSavePost');
	Route::post('parse-post-body', 'PostController@parsePostBody');

	// Comment routes!
	Route::post('comment', 'CommentController@postRestComment');
	Route::post('comment/edit', 'CommentController@editComment');
	Route::get('comment/like/{comment_id}', 'CommentController@likeComment');
	Route::get('comment/unlike/{comment_id}', 'CommentController@unlikeComment');
	Route::get('comment/flag/{comment_id}', 'CommentController@flagComment');
	Route::get('comment/unflag/{comment_id}', 'CommentController@unflagComment');

});

//Unprotected rest controllers
Route::group(array('prefix'=>'rest'), function() {
	//Route for Public Profile functions.
	Route::group(array('prefix' => 'profile'), function() {
		Route::get( 'collection/{type}/{user_id}/{page}','MyProfileController@getRestCollection');
		Route::get( 'followers/{user_id}/{page}', 'MyProfileController@getRestFollowers');
		Route::get( 'following/{user_id}/{page}', 'MyProfileController@getRestFollowing');
		Route::get( 'comments/{user_id}/{page}', 'MyProfileController@getRestComments');
		Route::get( 'featured/{post_id}', 'MyProfileController@getRestFeatured');
	});
	
	//Grab Comments
	Route::get( 'post/{post_id}/comments/deeplink/{comment_id}', 'CommentController@getDeepLinkedComments');
	Route::get( 'post/{post_id}/comments/{paginate}/{page}', 'CommentController@getRestComments');


	//Category autload
	Route::get( 'categories/{alais}/{sort}/{page}', 'CategoryController@getRestCategory');
	Route::get( 'categories/{alais}/{sort}', 'CategoryController@getRestCategory');
	Route::get( 'categories/{alais}', 'CategoryController@getRestCategory');

	// Route for staging username reservations. 
	// TODO: this will/should be removed post launch
	Route::post( 'username/reserve', 'StagingController@postReserveUsername' );

	//Profile autload
	Route::get( 'profile/{alias}', 'ProfileController@getRestProfile');
	Route::get( 'profile', 'ProfileController@getRestProfile');

	//Auto Load of for featured.
	Route::get( 'featured/{page}', 'HomeController@getRestFeatured');
	Route::get( 'featured', 'HomeController@getRestFeatured');

	Route::get( 'random', 'UserController@getRandomUsername');
	
});

//Note, below JSONController route has to be below the abouve REST route Group.
Route::group(array('before' => 'auth'), function() {
	Route::get('rest/flag/post/{post_id}', 'JSONController@flagPost');
	Route::controller('rest','JSONController');
});

// Routes for users profile
Route::group( array('prefix'=>'myprofile', 'before' => array('auth', 'force_ssl') ), function() {
	Route::get( 'editpost/{id}', 'PostController@getPostForm');
	Route::get( 'newpost', 'PostController@getPostForm');
	//Route::get('/{alias}', 'MyProfileController@getPublicProfile');

	Route::get('/', 'MyProfileController@getMyProfile' );
});

//Admin area
Route::group(array('prefix'=> 'admin', 'before'=> 'admin'), function() {
	Route::get('featured/post/{post_id}/remove', 'AdminController@removeFromFeatured');
	Route::get('featured/post/{post_id}/position/{position}', 'AdminController@setFeaturedPosition');
	Route::post('feature/user', 'AdminController@setFeaturedUser');
	Route::get('assign/moderator/user/{user_id}', 'AdminController@assignModerator');
	Route::get('unassign/moderator/user/{user_id}', 'AdminController@unassignModerator');
	Route::get('delete/user/{user_id}', 'AdminController@deleteUser');
	Route::get('restore/user/{user_id}', 'AdminController@restoreUser');
	Route::get('reset/user/{user_id}', 'AdminController@resetUser');
	Route::post('post/edit', 'AdminController@editPost');
	Route::post('post/update-view-count', 'AdminController@updatePostViewCount');
	Route::post('category/description', 'AdminController@editCategoryDescription');
	Route::post('category/create', 'AdminController@createCategory');
	// Weekly Digest Routes
	Route::post('digest/setpost', 'AdminController@setDigestPost');
	Route::post('digest/submit', 'AdminController@sendWeeklyDigest');
	//NSFW
	Route::get('nsfw/post/{post_id}', 'AdminController@setNSFW');
	// Prompts
	Route::get('prompts', 'AdminController@getPrompts');
	Route::post('prompts', 'AdminController@createPrompt');
	Route::post('prompt/toggle-active', 'AdminController@togglePromptActive');
	Route::post('prompt/delete', 'AdminController@deletePrompt');
	Route::controller('/','AdminController');
});

//Mod area
Route::group(array('prefix'=> 'mod', 'before'=> 'mod'), function() {
	Route::get('delete/post/{post_id}','ModController@deletePost');
	Route::get('undelete/post/{post_id}','ModController@undeletePost');
	Route::get('delete/post/{post_id}/category/{category_id}', 'ModController@deletePostCategory');
	Route::get('ban/user/{user_id}', 'ModController@banUser');
	Route::get('unban/user/{user_id}', 'ModController@unbanUser');
	Route::get('remove/flagged/comment/{flagged_id}', 'ModController@removeFlaggedComment');
	Route::get('remove/flagged/post/{flagged_id}', 'ModController@removeFlaggedPost');

});

/********************The Authentication Routes  ************************/
Route::group(array('prefix'=> 'user', 'https' => Config::get('app.enable_ssl') ), function() {
	Route::get('confirm/{code}', 'UserController@getConfirm');
	Route::get('reset/{token}', 'UserController@getReset');
	Route::get('restore/{id}', 'UserController@getRestore');
	Route::get('emailupdate/{token}', 'UserController@getEmailUpdate');
	
	Route::get('check', 'UserController@getUserCheck');
	
	Route::get('forgot', 'UserController@getForgot');
	Route::post('forgot', 'UserController@postForgot');

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
Route::get('search', 'SearchController@getSearchPage');
// Rest routes for searching users/posts (currently not used)
Route::get('search/users/{term}/{page}', 'SearchController@searchUsers');
Route::get('search/posts/{term}/{page}', 'SearchController@searchPosts');

//Not protected profile routes
Route::group(array('before'=>'profile'), function() {
	//General Profile
	Route::get( 'profile/{alias}', 'MyProfileController@getPublicProfile');
	Route::get( 'profile', 'MyProfileController@getPublicProfile');
});

//For Error Logging if the user wishes to contribute.
Route::get('error/form', 'HomeController@getErrorForm');

//For banned users to see when try try to log in.
Route::get('banned', 'UserController@getBanned');

//The featured page (since it wasn't part of the categories)
Route::get( 'featured', 'HomeController@getIndex');

//The front page.
Route::controller( '/', 'HomeController');
