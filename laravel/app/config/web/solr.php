<?php
return array(
	'post' => array(
			'endpoint' => array(
		        'localhost' => array(
		            'host' => '192.168.3.1',
		            'port' => 8080,
		            'path' => '/solr/',
		            'core' => 'posts'
		        )
		    )
		),
	'user'=> array(
			'endpoint' => array(
		        'localhost' => array(
		            'host' => '192.168.3.1',
		            'port' => 8080,
		            'path' => '/solr/',
		            'core' => 'users'
		        )
		    )
		),
);