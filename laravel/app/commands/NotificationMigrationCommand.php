<?php
 
use Illuminate\Console\Command;

//use Symfony\Component\Console\Input\InputOption;
//use Symfony\Component\Console\Input\InputArgument;
/**
 * This is used to reindex solr.
 */
class NotificationMigrationCommand extends Command {
 
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'not:migrate';

	protected $timeout = null; 
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Migrate notifications from mySQL to MongoDB";
 
    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire() {
    	$this->line('Welcome to notification migrator');
		
		$notifications = Notification::get();
		echo $notifications->count() . '<br>';
		$c = 1;
		foreach($notifications as $key => $notification) {
			$this->line('Notification: '. $key );
			
			if($notification->notification_type == 'follow') {
				$post_id = 0;
			} else {
				$post_id = $notification->post_id;
			}
			
			$motification = Motification::where('post_id', $post_id)
										->where('user_id', $notification->user_id)
										->where('notification_type', $notification->notification_type);
			
			$user = User::withTrashed()->where('id', $notification->action_id)->first();
			if(is_object($user)) {
				$user_name = $user->first()->username;
			} else {
				$user_name = 'nobody';
			}
			unset($user);//saving memory
			//If we can't find it, we need to make it.
			if($motification->count() == 0) {
				unset($motification);
				
				//Default values
				$post_id = 0;
				$post_alias = $post_title = '';
				
				if($notification->notification_type != 'follow') {
					$post_id = $notification->post_id;
					$post = Post::where('id', $notification->post_id)->first();
					if(isset($post->title)) {
						$post_title = $post->title;
						$post_alias = $post->alias;
					}
					unset($post);//gotta save memory
				}
				
				$motification = new Motification;
				$motification->post_id = $post_id;
				$motification->post_title = $post_title;
				$motification->post_alias = $post_alias;
				$motification->user_id = $notification->user_id;//person receiving the comment
				$motification->noticed = $notification->noticed;
				$motification->comment_id = $notification->comment_id;
				$motification->notification_type = $notification->notification_type;
				$motification->save();
				//This can add since its 
				$motification->push('users', $user_name, true);
			} else {
				//Otherwise, we just need to add to it.
				if($motification->count() == 1) {
					$this->line('Adding to Existing Motification');
				} elseif($motification->count() > 1) {
					$this->line($motification->count().' motifications exists.');
				}
				$motification->push('users', $user_name, true);
			}
			unset($motification);
		}
		$this->line($key.' notifications have been migrated');
    }
	
 
}