<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CommentMigrationCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'comment:migration';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Migrate the mysql comments over to mongodb.';

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
		$paginate = 25;
		$page = 0;
		$parent_comments = Comment::where( 'parent_id', 0 )->skip( $paginate * $page )->take( $paginate )->get();
		while ( count( $parent_comments ) > 0 ) {
			
			foreach ( $parent_comments as $key => $parent_comment ) {
				// This is the actual migration
				$this->migrateComment( $parent_comment, null );
			}

			// Increment the page and fetch next round of comments
			$page++;
			$parent_comments = Comment::where( 'parent_id', 0 )->skip( $paginate * $page )->take( $paginate )->get();
		}
	}

	private function migrateComment ( $comment, $mongo_parent_comment_id ) {
		// echo $comment->id."\n";
		if ( !is_object( $comment ) ) {
			// Safety catch / end of recursion
			return;
		} else {
			// Add this comment to mongo
			$new_mongo_comment_id = $this->createMongoComment( $comment, $mongo_parent_comment_id );
			
			// only continue if the mongocomment was created
			if ( $new_mongo_comment_id != false ) {
				// query for the children of this comment
				$direct_child_comments = Comment::where( 'parent_id', $comment->id )->get();

				foreach ( $direct_child_comments as $key => $child_comment ) {
					$this->migrateComment( $child_comment, $new_mongo_comment_id );
				}
			}
		}
	}

	private function createMongoComment ( $comment, $mongo_parent_comment_id ) {

		$post = $comment->post;
		$author = $comment->user;

		// Only process the comment if there is an existing post
		if ( is_object( $post ) ) {
			// Step 1: generate the unique portions of the slug and full slug.
			$slug_part = $this->generateRandomString(5);
			$created_at = $comment->created_at;
			$full_slug_part = $created_at->format('Y.m.d.H.i.s').':'.$slug_part;
			// Step 2: check if this comment is a reply to another
			if ( $mongo_parent_comment_id != null ) {
				$parent_comment = MongoComment::where( '_id', '=', $mongo_parent_comment_id )->first();
				// TODO: double check that we actually have the parent comment! (ie: we were given a valid reply_id)
				$slug = $parent_comment->slug.'/'.$slug_part;
				$full_slug = $parent_comment->full_slug.'/'.$full_slug_part;
				$depth = $parent_comment->depth + 1;
			} else {
				$slug = $slug_part;
				$full_slug = $full_slug_part;
				$depth = 0;
			}
			// Step 3: Do the insert!
			$new_comment = new MongoComment;
			$new_comment->post_id = $post->id;
			$new_comment->parent_comment = $mongo_parent_comment_id;
			$new_comment->slug = $slug;
			$new_comment->full_slug = $full_slug;
			$new_comment->created_at = $created_at;
			if ( is_object( $author ) ) {
				$new_comment->author = array(
					'user_id' => $author->id,
					'username' => $author->username
					);
			} else {
				$new_comment->author = array(
					'user_id' => 1,
					'username' => 'nobody'
					);
			}
			$new_comment->published = $comment->published;
			$new_comment->depth = $depth;
			$new_comment->body = $comment->body;
			$new_comment->likes = array();
			$new_comment->flags = array();
			$new_comment->save();
			// echo "Saved comment ".$comment->id."\n";
			return $new_comment->id;
		} else {
			return false;
		}
	}

	private function generateRandomString($length = 5) {
	    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}

}
