<?php
return array(

	/*
	|--------------------------------------------------------------------------
	| Application Debug Mode
	|--------------------------------------------------------------------------
	|
	| When your application is in debug mode, detailed error messages with
	| stack traces will be shown on every error that occurs within your
	| application. If disabled, a simple generic error page is shown.
	|
	*/

	
	'debug' => true,
	'stack' => true,//errors automatically redirects to a specified location.
	'email_send' => true,
	'enable_ssl' => true,//enables SSL force on the user and myprofile routes.

	/*
	|--------------------------------------------------------------------------
	| Application URL
	|--------------------------------------------------------------------------
	|
	| This URL is used by the console to properly generate URLs when using
	| the Artisan command line tool. You should set this to the root of
	| your application so that it is used when running Artisan tasks.
	|
	*/

	'url' => 'http://192.168.9.149',
	'staticurl' => '//192.168.9.149',
	'imageurl' => 'http://192.168.9.149/uploads/final_images',
	'cdn_upload' => false,//set to true if you want your images to go to S3.
);
