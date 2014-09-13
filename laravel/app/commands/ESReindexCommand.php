<?php
 
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
/**
 * This is used to reindex elasticsearch.
 */
class ESReindexCommand extends Command {
 
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'elasticsearch:reindex';

	protected $timeout = null; 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Fuck shit up. Oh, and reindex es ;)";
 
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->line('Welcome to ES reindexer');
		$data_type = $this->argument('data');//user or posts
		
		switch($data_type){
			case 'posts':
				self::rebuildPost();
			break;
			case 'users':
				self::rebuildUser();
			break;
			case 'delete-posts':
				self::deletePosts();
			break;
			case 'delete-users':
				self::deleteUsers();
			break;
		}
    }
 
 	/**
 	 *	Rebuild the post command
 	 */
 	private function rebuildPost() {
		$client = new Elasticsearch\Client();
		// Create the index
		$indexParams = array();
		$indexParams['index'] = 'posts';
		$indexParams['body']['mappings']['post'] = 
			array(
				'properties' => array(
					'id' => array( 'type' => 'integer' ),
					'title' => array( 'type' => 'string' ),
					'alias' => array( 'type' => 'string' ),
					'taglines' => array( 'type' => 'string' ),
					'body' => array( 'type' => 'string' )
				)
			);
		$client->indices()->create( $indexParams );
		// Fetch the existing posts data
		$posts = Post::all();
		$params = array();
		$params['index'] = 'posts';
		$params['type'] = 'post';
		// Add data to es
		for($i = 0; $i < count($posts); $i++) {
		    $post = $posts[$i];
		    $params['body'][] = array(
		        'update' => array(
		            '_id' => $i
		        )
		    );

		    $params['body'][] = array(
		        'doc_as_upsert' => 'true',
		        'doc' => array(
		            'id' => $post->id,
		            'title' => $post->title,
		            'alias' => $post->alias,
		            'taglines' => array( $post->tagline_1, $post->tagline_2, $post->tagline_3 ),
		            'body' => $post->body
		        )
		    );
		}

		$responses = $client->bulk($params);
		$this->line('Added '.count($posts).' posts to the posts index.');
 	}
 
	private function rebuildUser() {
		$client = new Elasticsearch\Client();
		// Create the index
		$indexParams = array();
		$indexParams['index'] = 'users';
		$indexParams['body']['mappings']['user'] = 
			array(
				'properties' => array(
					'id' => array( 'type' => 'integer' ),
					'username' => array( 'type' => 'string' ),
					'bio' => array( 'type' => 'string' )
				)
			);
		$client->indices()->create( $indexParams );
		// Fetch the existing posts data
		$users = User::all();
		$params = array();
		$params['index'] = 'users';
		$params['type'] = 'user';
		// Add data to es
		for($i = 0; $i < count($users); $i++) {
		    $user = $users[$i];
		    $params['body'][] = array(
		        'update' => array(
		            '_id' => $i
		        )
		    );

		    $params['body'][] = array(
		        'doc_as_upsert' => 'true',
		        'doc' => array(
		            'id' => $user->id,
		            'username' => $user->username,
		            'bio' => $user->bio
		        )
		    );
		}

		$responses = $client->bulk($params);
		$this->line('Added '.count($users).' users to the users index.');
	}

	private function deletePosts() {
		$client = new Elasticsearch\Client();
		$deleteParams = array();
		$deleteParams['index'] = 'posts';
		$client->indices()->delete( $deleteParams );
	}
	private function deleteUsers() {
		$client = new Elasticsearch\Client();
		$deleteParams = array();
		$deleteParams['index'] = 'users';
		$client->indices()->delete( $deleteParams );
	}
 
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
    protected function getArguments()
    {
        return array(
            array('data', InputArgument::REQUIRED, 'Name of the data type: posts or users'),
        );
    }
 
}