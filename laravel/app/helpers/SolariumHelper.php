<?php

/**
 * This Helper is intended to make things easy with solarium 
 */

class SolariumHelper {
	
	static protected $sconfig = array(
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
	
	/**
	 * This is the general select from Solr
	 */
	public static function searchSolr($string, $ajax = false) {
		$client = new Solarium\Client(static::$sconfig);
		
		//lets split this fool via alunum
		$string_space = preg_replace('/[^A-Za-z0-9]/', ' ', $string);//gotta make this a safe search
		
		$multi = explode(" ", $string_space);
		
		//Does this have multiple words?
		if(count($multi) == 1) {
			$query = '*'.$string_space.'*';
		} else {
			$query = '(';
			foreach($multi as $k => $item) {
				if($k != 0) {
					$query .= 'AND *'.$item.'*';
				}else {
					$query .= '*'.$item.'*';
				}
			}
			$query .= ')';
		}
		
		
		if($ajax) {
			$fields = array('id','title','taglines','alias');
			$rows = 30;
		} else {
			$fields = array('id');
			$rows = 10;
		}
		
		$select = array(
			'query'         => $query,
		    'start'         => 0,
		    'rows'          => $rows,
		    'fields'        => $fields, 
		    'sort'          => array('id' => 'asc'),
		);
		
		$query = $client->createSelect($select);
		
		return $client->select($query);//result set returned!
	}
	
	/**
	 * In Solr, update is create and create is update
	 */
	public static function updatePost($post) {
		$client = new Solarium\Client(static::$sconfig);
		$update = $client->createUpdate();
		
		$new_post = $update->createDocument();
		$new_post->id = $post->id;
		$new_post->title = $post->title;
		$new_post->alias = $post->alias;
		$new_post->taglines = array($post->tagline_1,$post->tagline_2,$post->tagline_3);
		
		$new_post->body = $post->body;
		
		$update->addDocuments(array($new_post));
		$update->addCommit();
		$client->update($update);
	}
	
	
	/**
	 * Update/Create User details within Solr
	 */
	public static function updateUser($user) {
		$client = new Solarium\Client(static::$sconfig);
		$update = $client->createUpdate();
		
		$new_user = $update->createDocument();
		
		$new_user->id = $user->id;
		$new_user->username = $user->username;
		
		$update->addDocuments(array($new_user));
		$update->addCommit();
		$client->update($update);
	}
	
}
