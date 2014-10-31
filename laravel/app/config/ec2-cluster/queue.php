<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Default Queue Driver
	|--------------------------------------------------------------------------
	|
	| The Laravel queue API supports a variety of back-ends via an unified
	| API, giving you convenient access to each back-end using the same
	| syntax for each one. Here you may set the default queue driver.
	|
	| Supported: "sync", "beanstalkd", "sqs", "iron"
	|
	*/

	'default' => 'sqs',

	/*
	|--------------------------------------------------------------------------
	| Queue Connections
	|--------------------------------------------------------------------------
	|
	| Here you may configure the connection information for each server that
	| is used by your application. A default configuration has been added
	| for each back-end shipped with Laravel. You are free to add more.
	|
	*/

	'connections' => array(

		'sync' => array(
			'driver' => 'sync',
		),

		'beanstalkd' => array(
			'driver' => 'beanstalkd',
			'host'   => 'localhost',
			'queue'  => 'default',
		),

		'sqs' => array(
			'driver' => 'sqs',
			'key'    => 'AKIAIG5Q654GMX7YO4CQ',
			'secret' => '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',
			'queue'  => 'https://sqs.us-west-2.amazonaws.com/327127412329/laravel',
			'region' => 'us-west-2',
		),

	),
	'failed' => array(
	    'database' => 'mysql', 'table' => 'failed_jobs',
	),
);
