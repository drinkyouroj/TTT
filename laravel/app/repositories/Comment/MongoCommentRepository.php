<?php
namespace AppStorage\Comment;

use MongoComment, DateTime, Request, DB;

/**
 *	http://docs.mongodb.org/ecosystem/use-cases/storing-comments/
 */

class MongoCommentRepository implements CommentRepository {

	public function __construct(MongoComment $comment)
	{
		$this->comment = $comment;
	}

	//Instance
	public function instance() {
		return new MongoComment;
	}

	/**
	 *	Constructs a new comment
	 *	@param $user_id: id of the user who authored the post
	 */
	public function create ( $user_id, $username ) {
		// Before creating the comment, make sure the user has not commented within the last 10 seconds
		$last_comment = MongoComment::where( 'author.user_id', $user_id )
									->where( 'created_at', '>', new DateTime('-10 seconds') )
									->get();
		if ( count( $last_comment ) ) {
			// User has commented in the last 10 seconds!
			return null;
		}


		// Step 1: generate the unique portions of the slug and full slug.
		$slug_part = $this->generateRandomString(5);
		$created_at = new DateTime();
		$full_slug_part = $created_at->format('Y.m.d.H.i.s').':'.$slug_part;
		// Step 2: check if this comment is a reply to another
		if ( Request::get( 'reply_id' ) ) {
			$parent_comment = MongoComment::find( Request::get( 'reply_id' ) );
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
		$new_comment->post_id = intval( Request::get( 'post_id' ) );
		$new_comment->parent_comment = Request::get( 'reply_id' ) ? Request::get( 'reply_id' ) : null;
		$new_comment->slug = $slug;
		$new_comment->full_slug = $full_slug;
		$new_comment->created_at = $created_at;
		$new_comment->author = array(
							'user_id' => intval($user_id),
							'username' => $username
							);
		$new_comment->published = 1;
		$new_comment->depth = $depth;
		$new_comment->body = strip_tags( Request::get( 'body' ) );
		$new_comment->save();
		return $new_comment;

		// $this->comment->raw(function($colleciton) {
		// });
	}

	//Create??
	public function input ($input) {}

	/**
	 *	Find a comment by its id (the actual _id field given to it by mongo)
	 */
	public function findById( $id ) {
		return MongoComment::find( $id );
	}

	/**
	 *	Fetch comments for a given post. Pagination is available by the 'highest' level
	 *	comments (ie: comments with no parent, start of a comment thread, etc...).
	 */
	public function findByPostId ( $post_id, $paginate = 10, $page = 1 ) {
		return MongoComment::where( 'post_id', intval( $post_id ) )
						   ->orderBy( 'full_slug' )
						   ->skip( ($page - 1) * $paginate )
						   ->take( $paginate )
						   ->get();
	}
	
	//Read Multi
	public function all() {}
	
	/**
	 *	Fetch comments by a given author
	 */
	public function allByUserId ( $user_id, $paginate = 5, $page = 1, $rest = false ) {
		$comments = MongoComment::where( 'author.user_id', intval($user_id) )
						   ->orderBy( 'created_at', 'desc' )
						   ->skip( ($page - 1) * $paginate )
						   ->take( $paginate )
						   ->get();
		return $comments;

	}

	/**
	 *	Check whether a given user was the author of a given comment.
	 *	@return boolean: owns or not
	 */
	public function owns($comment_id, $user_id) {
		$result = MongoComment::where( '_id', $comment_id )
						      ->where( 'author.user_id', $user_id )
						      ->get();
		return count( $result ) ? true : false;
	}

	//Update
	public function update($input) {}
	
	/**
	 *	Publish a given comment
	 */
	public function publish ( $comment_id ) {
		$comment = MongoComment::where( '_id', $comment_id )->get()->first();
		$comment->published = 1;
		$comment->save();
	}

	public function unpublish ( $comment_id ) {
		$comment = MongoComment::where( '_id', $comment_id )->get()->first();
		$comment->published = 0;
		$comment->save();
	}
	
	//Delete
	public function delete ($id) {}

	/**
	 *	Simple random string generator for creating slugs.
	 */
	private function generateRandomString($length = 5) {
	    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
	
}