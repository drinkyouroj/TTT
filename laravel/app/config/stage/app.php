<?php

#define('APP_HOST', 'http://janus00-483730696.us-west-2.elb.amazonaws.com'); #push all through load balancer
define('APP_HOST', 'http://stage.sondry.com'); #push all through load balancer
#define('APP_HOST', ''); #relative
#define('APP_HOST', 'http://54.68.96.139'); #my External IP -- means direct traffic to box, not through LB. also cross-origin problem, and specific config per box required.
$file_path = base_path().'/gitversion';
$version = str_replace("\n", "", fread(fopen($file_path, "r"), filesize($file_path)) );
define('GIT_VER', $version);

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
	
	'url' => APP_HOST, #constant defined at top
	'secureurl' => 'https://stage.sondry.com',
	'imageurl' => 'http://images.sondry.com',
	'staticurl' => '//static.sondry.com/'.GIT_VER,//not the prettiest, but it sure works.
	'cdn_upload' => true,//set to true if you want your images to go to S3.
	/*
	|--------------------------------------------------------------------------
	| Application Timezone
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default timezone for your application, which
	| will be used by the PHP date and date-time functions. We have gone
	| ahead and set this to a sensible default for you out of the box.
	|
	*/

	'timezone' => 'America/Los_Angeles',

	/*Encryption*/
	'cipher' => MCRYPT_RIJNDAEL_256,//Needed for upgrade to Laravel 4.2

	/*
	|--------------------------------------------------------------------------
	| Application Locale Configuration
	|--------------------------------------------------------------------------
	|
	| The application locale determines the default locale that will be used
	| by the translation service provider. You are free to set this value
	| to any of the locales which will be supported by the application.
	|
	*/

	'locale' => 'en',

	/*
	|--------------------------------------------------------------------------
	| Encryption Key
	|--------------------------------------------------------------------------
	|
	| This key is used by the Illuminate encrypter service and should be set
	| to a random, 32 character string, otherwise these encrypted strings
	| will not be safe. Please do this before deploying an application!
	|
	*/

	'key' => 'AGTDpG5dW4pDqucoV9NBEsJ9nLlZEDaH',

	/*
	|--------------------------------------------------------------------------
	| Service Provider Manifest
	|--------------------------------------------------------------------------
	|
	| The service provider manifest is used by Laravel to lazy load service
	| providers which are not needed for each request, as well to keep a
	| list of all of the services. Here, you may set its storage spot.
	|
	*/

	'manifest' => storage_path().'/meta',

	/*
	|--------------------------------------------------------------------------
	| Class Aliases
	|-------------------------------------------------------------------------- 
	|
	| This array of class aliases will be registered when this application
	| is started. However, feel free to register as many as you wish as
	| the aliases are "lazy" loaded so they don't hinder performance.
	|
	*/

	'aliases' => array(
		'Agent'            => 'Jenssegers\Agent\Facades\Agent',
		'App'             => 'Illuminate\Support\Facades\App',
		'Artisan'         => 'Illuminate\Support\Facades\Artisan',
		'Auth'            => 'Illuminate\Support\Facades\Auth',
		'Blade'           => 'Illuminate\Support\Facades\Blade',
		'Cache'           => 'Illuminate\Support\Facades\Cache',
		'ClassLoader'     => 'Illuminate\Support\ClassLoader',
		'Config'          => 'Illuminate\Support\Facades\Config',
		'Controller'      => 'Illuminate\Routing\Controller',
		'Cookie'          => 'Illuminate\Support\Facades\Cookie',
		'Crypt'           => 'Illuminate\Support\Facades\Crypt',
		'DB'              => 'Illuminate\Support\Facades\DB',
		'Eloquent'        => 'Illuminate\Database\Eloquent\Model',
		'Event'           => 'Illuminate\Support\Facades\Event',
		'File'            => 'Illuminate\Support\Facades\File',
		'Form'            => 'Illuminate\Support\Facades\Form',
		'Hash'            => 'Illuminate\Support\Facades\Hash',
		'HTML'            => 'Illuminate\Support\Facades\HTML',
		'Input'           => 'Illuminate\Support\Facades\Input',
		'Lang'            => 'Illuminate\Support\Facades\Lang',
		'Log'             => 'Illuminate\Support\Facades\Log',
		'Mail'            => 'Illuminate\Support\Facades\Mail',
		'Paginator'       => 'Illuminate\Support\Facades\Paginator',
		'Password'        => 'Illuminate\Support\Facades\Password',
		'Queue'           => 'Illuminate\Support\Facades\Queue',
		'Redirect'        => 'Illuminate\Support\Facades\Redirect',
		'Redis'           => 'Illuminate\Support\Facades\Redis',
		'Request'         => 'Illuminate\Support\Facades\Request',
		'Response'        => 'Illuminate\Support\Facades\Response',
		'Route'           => 'Illuminate\Support\Facades\Route',
		'Schema'          => 'Illuminate\Support\Facades\Schema',
		'Seeder'          => 'Illuminate\Database\Seeder',
		'Session'         => 'Illuminate\Support\Facades\Session',
		'SSH'             => 'Illuminate\Support\Facades\SSH',
		'Str'             => 'Illuminate\Support\Str',
		'URL'             => 'Illuminate\Support\Facades\URL',
		'Validator'       => 'Illuminate\Support\Facades\Validator',
		'View'            => 'Illuminate\Support\Facades\View',
		'Entrust'    => 'Zizaco\Entrust\EntrustFacade',
		'Image' => 'Intervention\Image\Facades\Image',
		
		//Note, below: Original Eloquent has been overridden by Jessenger's Eloquent.
		//This allows us to relate Mongo Data to MySQL data through the ORM.
		'Moloquent'       => 'Jenssegers\Mongodb\Model',
		'Eloquent' 		=> 'Jenssegers\Eloquent\Model',

		'Captcha' => 'Mews\Captcha\Facades\Captcha',
		'OpenCloud' => 'Thomaswelton\LaravelRackspaceOpencloud\Facades\OpenCloud',
	),

);
