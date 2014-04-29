<?php namespace Helper\SolariumHelper;
/**
 * This Helper is intended to make things easy with solarium 
 * Keep in mind that this is the posts solarium helper, not the users one.
 */
use Solarium, Config;
 
class SolariumHelper {
		
	/**
	 * This is the general select from Solr
	 */
	public function searchSolr($string, $ajax = false, $page = 1) {
		//First, let's process the query.
		$query = self::queryBuilder($string);
		
		//Posts Search.
		$post_client = new Solarium\Client(Config::get('solr.post'));
		
		if($ajax) {
			$post_fields = array('id','title','taglines','alias');
			$rows = 5;
		} else {
			$post_fields = array('id');
			$rows = 30;
		}
		
		$select = array(
			'query'         => $query,
		    'start'         => 0,
		    'rows'          => $rows,
		    'fields'        => $post_fields, 
		    'sort'          => array('id' => 'asc'),
		);
		
		$post_query = $post_client->createSelect($select);
		$posts = $post_client->select($post_query);//result set returned!
		
		
		//User Search
		$user_client = new Solarium\Client(Config::get('solr.user'));
		
		if($ajax) {
			$user_fields = array('id','username','bio');
			$rows = 5;
		} else {
			$user_fields = array('id');
			$rows = 30;
		}
		
		$select = array(
			'query'         => $query,//query is still the same
		    'start'         => 0,
		    'rows'          => $rows,
		    'fields'        => $user_fields, 
		    'sort'          => array('id' => 'asc'),
		);
		
		$user_query = $user_client->createSelect($select);
		$users = $user_client->select($user_query);//result set returned!
		
		return array('posts' => $posts,
					 'users' => $users);
	}
	
		private function queryBuilder($string) {
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
						$query .= ' AND *'.$item.'*';//The space before the AND is very important.
					}else {
						$query .= '*'.$item.'*';
					}
				}
				$query .= ')';
			}
			return $query;
		}
	
	
	/**
	 * In Solr, update is create and create is update
	 */
	public function updatePost($post) {
		$client = new Solarium\Client(Config::get('solr.post'));
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
	 * Delete the post.
	 */
	public function deletePost($id) {
		$client = new Solarium\Client(Config::get('solr.post'));
		$update = $client->createUpdate();
		
		$update->addDeleteById($id);
		$update->addCommit();
		
		$result = $client->update($update);
		return $result->getStatus();
	}
	
	/**
	 * Update/Create User details within Solr
	 */
	public function updateUser($user) {
		$client = new Solarium\Client(Config::get('solr.user'));
		$update = $client->createUpdate();
		
		$new_user = $update->createDocument();
		
		$new_user->id = $user->id;
		$new_user->username = $user->username;
		$new_user->bio = $user->bio;
		
		$update->addDocuments(array($new_user));
		$update->addCommit();
		$client->update($update);
	}
	
}
