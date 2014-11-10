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
	'enable_ssl' => false,//enables SSL force on the user and myprofile routes.

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

	'url' => 'http://localhost/tt',
	'secureurl' => 'https://localhost/tt',
	'imageurl' => 'http://localhost/tt/uploads/final_images',
	'staticurl' => '//localhost/tt',
	'cdn_upload' => false,//set to true if you want your images to go to S3.
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
	| Autoloaded Service Providers
	|--------------------------------------------------------------------------
	|
	| The service providers listed here will be automatically loaded on the
	| request to your application. Feel free to add your own services to
	| this array to grant expanded functionality to your applications.
	|
	*/

	'providers' => array(
		'Illuminate\Foundation\Providers\ArtisanServiceProvider',
		'Illuminate\Auth\AuthServiceProvider',
		'Illuminate\Cache\CacheServiceProvider',
		'Illuminate\Session\CommandsServiceProvider',
		'Illuminate\Foundation\Providers\ConsoleSupportServiceProvider',
		'Illuminate\Routing\ControllerServiceProvider',
		'Illuminate\Cookie\CookieServiceProvider',
		'Illuminate\Database\DatabaseServiceProvider',
		'Illuminate\Encryption\EncryptionServiceProvider',
		'Illuminate\Filesystem\FilesystemServiceProvider',
		'Illuminate\Hashing\HashServiceProvider',
		'Illuminate\Html\HtmlServiceProvider',
		'Illuminate\Log\LogServiceProvider',
		'Illuminate\Mail\MailServiceProvider',
		'Illuminate\Database\MigrationServiceProvider',
		'Illuminate\Pagination\PaginationServiceProvider',
		'Illuminate\Queue\QueueServiceProvider',
		'Illuminate\Redis\RedisServiceProvider',
		'Illuminate\Remote\RemoteServiceProvider',
		'Illuminate\Auth\Reminders\ReminderServiceProvider',
		'Illuminate\Database\SeedServiceProvider',
		'Illuminate\Session\SessionServiceProvider',
		'Illuminate\Translation\TranslationServiceProvider',
		'Illuminate\Validation\ValidationServiceProvider',
		'Illuminate\View\ViewServiceProvider',
		'Illuminate\Workbench\WorkbenchServiceProvider',
		
		'Zizaco\Entrust\EntrustServiceProvider',
		'Way\Console\GuardLaravelServiceProvider',
		'Way\Generators\GeneratorsServiceProvider',
		'Jenssegers\Mongodb\MongodbServiceProvider',
		'Jenssegers\Agent\AgentServiceProvider',
		'Mews\Captcha\CaptchaServiceProvider',
		
		//Below are service providers for the business logic that's been separated for ease of use.
		'AppLogic\CategoryLogic\CategoryLogicServiceProvider',
		'AppLogic\FollowLogic\FollowLogicServiceProvider',
		'AppLogic\NotificationLogic\NotificationLogicServiceProvider',
		'AppLogic\PostLogic\PostLogicServiceProvider',
		'AppLogic\CommentLogic\CommentLogicServiceProvider',
		'AppLogic\AnalyticsLogic\AnalyticsLogicServiceProvider',
		'AppLogic\EmailLogic\EmailLogicServiceProvider',
		
		//Helpers provide non-core services to the system (Solr, Instaham filters)
		'Helper\SolariumHelper\SolariumHelperServiceProvider',
		
		//Repository for model abstrations.
		'AppStorage\PostStorageServiceProvider',
		'AppStorage\CommentStorageServiceProvider',
		'AppStorage\NotificationStorageServiceProvider',
		'AppStorage\CategoryStorageServiceProvider',
		'AppStorage\FollowStorageServiceProvider',
		'AppStorage\RepostStorageServiceProvider',
		'AppStorage\LikeStorageServiceProvider',
		'AppStorage\FavoriteStorageServiceProvider',
		'AppStorage\ProfilePostStorageServiceProvider',
		'AppStorage\ActivityStorageServiceProvider',
		'AppStorage\FeaturedStorageServiceProvider',
		'AppStorage\MessageStorageServiceProvider',
		'AppStorage\PostViewStorageServiceProvider',
		'AppStorage\FeedStorageServiceProvider',
		'AppStorage\EmailStorageServiceProvider',
		'AppStorage\UserStorageServiceProvider',
		'AppStorage\FlaggedContentStorageServiceProvider',
		'AppStorage\PostFlaggedStorageServiceProvider',
		'AppStorage\PhotoStorageServiceProvider',
		'AppStorage\SearchStorageServiceProvider',
		'AppStorage\FeaturedUserStorageServiceProvider', 
		'AppStorage\EmailPrefStorageServiceProvider',
		'AppStorage\PromptStorageServiceProvider',
		'AppStorage\WeeklyDigestStorageServiceProvider', 

		//Image system for when we make our captcha.
		'Intervention\Image\ImageServiceProvider',

		//Compression of html
		'Fitztrev\LaravelHtmlMinify\LaravelHtmlMinifyServiceProvider'

	),

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

		'Captcha' => 'Mews\Captcha\Facades\Captcha'
	),
);