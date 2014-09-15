<?php
define('DATASERVER_HOST', 'internal-lb-data-1193680030.us-west-2.elb.amazonaws.com');
return array(
	'post' => array(
			'connectionParams' => array(
		            'host' => DATASERVER_HOST,
		            'port' => 9200
		        )
		    ),
	'user'=> array(
			'connectionParams' => array(
		            'host' => DATASERVER_HOST,
		            'port' => 9200,
		        )
		),
);
