<?php
 
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
/**
 * This is used to reindex solr.
 */
class SolrReindexCommand extends Command {
 
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'solr:reindex';

	protected $timeout = null; 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Blow out everything from solr and rebuild the index";
 
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $this->line('Welcome to Solr reindexer');
		$data_type = $this->argument('data');//user or posts
		
		//left this as a switch incase we do more Solr.
		switch($data_type){
			case 'post':
				self::rebuildPost();
			break;
			case 'user':
				self::rebuildUser();
			break;
		}
    }
 
	 	private function rebuildPost() {
	 		
	 		$client = new Solarium\Client(Config::get('solr.post'));
			$update = $client->createUpdate();
			
			// add the delete query and a commit command to the update query
			$update->addDeleteQuery('*:*');
			$update->addCommit();
			$result = $client->update($update);
			
			$update = $client->createUpdate();
			$posts = Post::where('published', 1)->get();
			$i = 0;
			$updateArray = array(); 
			foreach($posts as $post) {
				$new_post = $update->createDocument();
				$new_post->id = $post->id;
				$new_post->title = $post->title;
				$new_post->alias = $post->alias;
				$new_post->taglines = array($post->tagline_1,$post->tagline_2,$post->tagline_3);
				
				$new_post->body = $post->body;
				array_push($updateArray, $new_post);
				$i++;
				$this->line($i .' posts have been reindexed');
			}
			
			$update->addDocuments($updateArray);
			$update->addCommit();
			$client->update($update);
			
			$update->addOptimize(true, false, 5);
			$client->update($update);
			
			
	 	}
 
 		private function rebuildUser() {
 			
 			$client = new Solarium\Client(Config::get('solr.user'));
			$update = $client->createUpdate();
			// add the delete query and a commit command to the update query
			$update->addDeleteQuery('*:*');
			$update->addCommit();
			$result = $client->update($update);
			
			$update = $client->createUpdate();//recreate update.
			$users = User::where('banned', 0)->get();
			$i = 0;
			
			foreach($users as $user) {
				$new_user = $update->createDocument();
				
				$new_user->id = $user->id;
				$new_user->username = $user->username;
				$new_user->bio = $user->bio;
				
				$update->addDocuments(array($new_user));
				$update->addCommit();
				$client->update($update);
				$i++;
				
			}
			$update->addOptimize(true, false, 5);
			$client->update($update);
			
			$this->line($i .' users have been reindexed');
 		}
 
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
    protected function getArguments()
    {
        return array(
            array('data', InputArgument::REQUIRED, 'Name of the data type'),
        );
    }
 
}