<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CommentSortingCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'comment:sorting';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

		$comments = MongoComment::all();
		foreach ($comments as $key => $comment) {
			$slug = $comment->full_slug ? $comment->full_slug : $comment->full_slug_asc;
			$comment->full_slug_desc = $slug."\\";
			$comment->full_slug_asc = $slug;
			$comment->unset('full_slug');
			$comment->save();
		}

		/* NOTE: i removed paginatation because it was not hitting all existing comments for some reason
		$paginate = 25;
		$page = 0;
		$comments = MongoComment::skip( $paginate * $page )->take( $paginate )->get();
		while ( count ( $comments ) > 0 ) {
			foreach ($comments as $key => $comment) {
				// Update the comment accordingly
				$comment->full_slug_desc = $comment->full_slug."\\";
				$comment->full_slug_asc = $comment->full_slug;
				$comment->unset('full_slug');
				$comment->save();
			}
			$page++;
			$comments = MongoComment::skip( $paginate * $page )->take( $paginate )->get();
		}
		*/
	}
}
