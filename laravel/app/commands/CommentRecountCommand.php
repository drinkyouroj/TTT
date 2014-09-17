<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CommentRecountCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'comment:recount';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Re-counts the comment counts on each post.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		$this->post = App::make('AppStorage\Post\PostRepository');
		$this->comment = App::make('AppStorage\Comment\CommentRepository');
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {
		// Fetch all posts
		$posts = $this->post->all( true );
		foreach ($posts as $post) {
			// count up the comments and modify comment_count field
			$count = $this->comment->getCommentCount( $post->id );
			$post->comment_count = $count;
			$post->save();
		}

	}

}
