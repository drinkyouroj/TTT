<?php
namespace AppStorage\Search;

use Elasticsearch, Config, Log;

class ElasticSearchRepository implements SearchRepository {

	public function __construct() {
		$this->client = new Elasticsearch\Client(Config::get('elastic'));
	}

	public function searchPosts( $search_string, $page = 1, $paginate = 12 ) {
		$params['index'] = 'posts';
		$params['type']  = 'post';
		// Only get the ids
		$params['body']['fields'] = array( 'id' );
		// Fuzzy search
		$fuzzy = array();
		$fuzzy['fields'] = array('title', 'taglines', 'body');
		$fuzzy['like_text'] = $search_string;
		$fuzzy['fuzziness'] = 1;

		$params['body']['query']['fuzzy_like_this'] = $fuzzy;
		// Pagination
		$params['body']['from'] = ($page - 1) * $paginate;
		$params['body']['size'] = $paginate;
		
		$results = $this->client->search($params);
		return $results['hits']['hits'];
	}

	public function searchUsers( $search_string, $page = 1, $paginate = 12 ) {
		$params['index'] = 'users';
		$params['type']  = 'user';
		// Only get ids
		$params['body']['fields'] = array( 'id' );
		// Fuzzy Search
		$fuzzy = array();
		$fuzzy['fields'] = array('username');
		$fuzzy['like_text'] = $search_string;
		$fuzzy['fuzziness'] = 0.3; // Lower number = more results


		$params['body']['query']['fuzzy_like_this'] = $fuzzy;
		// Pagination
		$params['body']['from'] = ($page - 1) * $paginate;
		$params['body']['size'] = $paginate;

		$results = $this->client->search($params);
		return $results['hits']['hits'];
	}

	public function updatePost( $post ) {
		$params = array();
		$params['index'] = 'posts';
		$params['type'] = 'post';
		$params['id'] = $post->id;
		$params['body'] = array(
			'id' => $post->id,
			'title' => $post->title,
			'alias' => $post->alias,
			'taglines' => array( $post->tagline_1, $post->tagline_2, $post->tagline_3 ),
			'body' => $post->body
		);
		$params['refresh'] = true;  // Refresh the index after performing operation.
		try {
			return $this->client->index( $params );  // This will update if post with id already exists.
		} catch(Elasticsearch\Common\Exceptions\Curl\CouldNotConnectToHost $e) {
			Log::error($e);
			return false;
		}
	}

	public function updateUser( $user ) {
		$params = array();
		$params['index'] = 'users';
		$params['type'] = 'user';
		$params['id'] = $user->id;
		$params['body'] = array(
			'id' => $user->id,
			'username' => $user->username
		);
		$params['refresh'] = true;  // Refresh the index after performing operation.
		try {
			return $this->client->index( $params );  // This will update if post with id already exists.
		} catch(Elasticsearch\Common\Exceptions\Curl\CouldNotConnectToHost $e) {
			Log::error($e);
			return false;
		}
	}

	public function deletePost( $post_id ) {
		$params = array();
		$params['index'] = 'posts';
		$params['type'] = 'post';
		$params['id'] = $post_id;
		$params['ignore'] = 404;
		return $this->client->delete( $params );
	}

	public function deleteUser( $user_id ) {
		$params = array();
		$params['index'] = 'users';
		$params['type'] = 'user';
		$params['id'] = $user_id;
		$params['ignore'] = 404;
		return $this->client->delete( $params );
	}

}
