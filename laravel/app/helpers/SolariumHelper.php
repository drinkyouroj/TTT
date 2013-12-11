<?php

/**
 * This Helper is intended to make things easy with solarium 
 */

class SolariumHelper {
	
	protected $sconfig = array(
							'endpoint' => array(
						        'localhost' => array(
						            'host' => '127.0.0.1',
						            'port' => 8080,
						            'path' => '/solr/',
						        )
						    )
						);
	
	
	function __constructor() {
		
	}
	
	//Creates a new Solarium Client instance
	private function new_solarium_client() {
		return new Solarium\Client($sconfig);
	}
	
	/**
	 * In Solr, update is create and create is update
	 */
	public function updatePost($post) {
		$client = self::new_solarium_client();
		$update = $client->createUpdate();
		
		$new_post = $update->createDocument();
		$new_post->id = $post->id;
		$new_post->title = $post->title;
		$new_post->tagline_1 = $post->tagline_1;
		$new_post->tagline_2 = $post->tagline_1;
		$new_post->tagline_3 = $post->tagline_1;
		$new_post->body = $post->body;
		
		$update->addDocuments(array($new_post));
		$update->addCommit();
		$client->update($update);
		
	}
	
	
	
	public function updateUser($user) {
		$client = self::new_solarium_client();
		$update = $client->createUpdate();
		
		$new_user = $update->createDocument();
		
		//Add the new user queries here.
		
		
		$update->addDocuments(array($new_user));
		$update->addCommit();
		$client->update($update);
	}
	
}
