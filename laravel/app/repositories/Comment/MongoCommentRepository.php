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
	 *	@param $username: username of the author
	 *	@param $reply_id: the id of the comment in which this is a reply to (if any)
	 *	@param $post_id: the post id to which this comment originated
	 *	@param $comment_body: the actual text of the comment
	 *
	 *	@return Returns the new comment, or the errors if the comment was not successfully saved.
	 */
	public function create ( $user_id, $username, $reply_id, $post_id, $comment_body ) {
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
		if ( $reply_id ) {
			$parent_comment = MongoComment::find( $reply_id );
			// TODO: double check that we actually have the parent comment! (ie: we were given a valid reply_id)
			$slug = $parent_comment->slug.'/'.$slug_part;
			$full_slug = $parent_comment->full_slug.'/'.$full_slug_part;
			$depth = $parent_comment->depth + 1;
		} else {
			$slug = $slug_part;
			$full_slug = $full_slug_part;
			$depth = 0;
		}
		// Step 3: Build up the new comment
		$new_comment = new MongoComment;
		$new_comment->post_id = intval( $post_id );
		$new_comment->parent_comment = $reply_id ? $reply_id : null;
		$new_comment->slug = $slug;
		$new_comment->full_slug = $full_slug;
		$new_comment->created_at = $created_at;
		$new_comment->author = array(
							'user_id' => intval($user_id),
							'username' => $username
							);
		$new_comment->published = 1;
		$new_comment->depth = $depth;
		$new_comment->body = trim( strip_tags( $comment_body ) );
		$new_comment->likes = array();
		$new_comment->flags = array();

		// Step 4: Run the validate to make sure all fields are actually valid
		$validation = $new_comment->validate( $new_comment->toArray() );

		if ( $validation->fails() ) {
			return null;
		} else {
			// Step 5: Save the beast
			$new_comment->save();	
			if ( $new_comment->id ) {
				return $new_comment;
			} else {
				return null;
			}
		}
	}

	public function update($input) {

	}

	public function editBody($comment_id, $body) {
		$comment = $this->comment->find( $comment_id );
		if ($comment instanceof MongoComment) {
			if ( $comment->edited > 1 ) {
				// Imposed a limit of 2 edits 
				return array( 'error' => 'Each comment may only be edited twice.' );
			}
			$comment->body = trim( strip_tags( $body ) );
			$comment->edited = $comment->edited ? $comment->edited + 1 : 1;
			$comment->save();
			return array( 'comment' => $comment );
		}
		return array( 'error' => 'The comment in question was not found!' );
	}

	public function delete ( $id ) {
		$comment = MongoComment::find( $id );
		if ( $comment instanceof MongoComment ) {
			$comment->delete();
		}
	}

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
						   ->orderBy( 'full_slug', 'asc' )
						   ->skip( ($page - 1) * $paginate )
						   ->take( $paginate )
						   ->get();
	}
	
	/**
	 *	Fetch comments by a given author
	 */
	public function findByUserId ( $user_id, $paginate = 5, $page = 1, $rest = false ) {
		$query = $this->comment->where( 'author.user_id', intval($user_id) )
								->where('published', 1)
							   ->orderBy( 'created_at', 'asc' )							   
							   ->skip( ($page - 1) * $paginate )
							   ->take( $paginate )
						   		;
		
		if($rest) {
			return $query->with('post.user')->get();
		} else {
			return $query->get();
		}
	}

	/**
	 *	Find all comments by a given user_id
	 */
	public function findAllByUserId ( $user_id ) {
		return $this->comment->where( 'author.user_id', intval( $user_id ) )->get();
	}

	/**
	 *	Find all comments related to given post up until $comment_id is found.
	 *	This is used for deep linking to comments so that we only load the correct
	 *	number of comments
	 */
	public function findByCommentAndPostId ( $comment_id, $post_id, $paginate = 10 ) {
		$post_id = intval( $post_id );
		// Step 1. First query for the comment by id.
		$target_comment = MongoComment::find( $comment_id );
		if ( $target_comment instanceof MongoComment ) {
			// Step 2. Then query the number of comments up until that comment
			$number_of_comments = MongoComment::where( 'post_id', $post_id )
											  ->where( 'full_slug', '<=', $target_comment->full_slug )
											  ->count();
			// Step 3. calculate the correct pagination/page so that we return proper
			// pagination to the front end.
			$pages_to_pull = ceil( $number_of_comments / $paginate );
			// Step 4. Make the final query
			$comments = MongoComment::where( 'post_id', intval( $post_id ) )
						   ->orderBy( 'full_slug', 'asc' )
						   ->take( $paginate * $pages_to_pull )
						   ->get();
			return array(
				'comments' => $comments->toArray(),
				'page' => $pages_to_pull + 1,
				'paginate' => $paginate
				);
		} else {
			// Oops! no comment was found by given id.
			return false;
		}
	}

	/**
	 *	Check whether a given user was the author of a given comment.
	 *	@return boolean: owns or not
	 */
	public function owns($comment_id, $user_id) {
		$result = MongoComment::where( '_id', $comment_id )
						      ->where( 'author.user_id', $user_id )
						      ->first();
		return ( $result instanceof MongoComment );
	}


	public function like( $user_id, $comment_id ) {
		$comment = $this->findById( $comment_id );
		if ( $comment instanceof MongoComment ) {
			$comment->push('likes', $user_id, true);
			return true;
		} else {
			return false;
		}
	}
	public function unlike( $user_id, $comment_id ) {
		$comment = $this->findById( $comment_id );
		if ( $comment instanceof MongoComment ) {
			$comment->pull('likes', $user_id);
			return true;
		} else {
			return false;
		}
	}
	public function flag( $user_id, $comment_id ) {
		$comment = $this->findById( $comment_id );
		if ( $comment instanceof MongoComment ) {
			$comment->push('flags', $user_id, true);

			if ( count( $comment->flags ) > 4 ) {
				// TODO: so many flags!!! somebody should probably intervene.
			}
			return true;
		} else {
			return false;
		}
	}
	public function unflag( $user_id, $comment_id ) {
		$comment = $this->findById( $comment_id );
		if ( $comment instanceof MongoComment ) {
			$comment->pull('flags', $user_id);
			return true;
		} else {
			return false;
		}
	}
	
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
	

		/**
		 *	Simple random string generator for creating slugs.
		 */
		private function generateRandomString($length = 5) {
		    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}
		
}