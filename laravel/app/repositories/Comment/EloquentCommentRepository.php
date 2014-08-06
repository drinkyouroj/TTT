<?php namespace AppStorage\Comment;

use Comment,DB, Request;

class EloquentCommentRepository implements CommentRepository {

	public function __construct(Comment $comment)
	{
		$this->comment = $comment;
	}

	
	//Instance
	public function instance() {
		return new Comment;
	}

	public function input($user_id) {
		$comment = self::instance();
		$comment->user_id = $user_id;
		$comment->post_id = Request::segment(3);
		$comment->published = 1;//This sets the comment to be "deleted"  We did this so we don't lose the tree structure.
		if(Request::get('reply_id')) {
			$comment->parent_id = Request::get('reply_id');
		}
		$comment->body = strip_tags(Request::get('body'));
		return $comment;
	}

	//Create
	public function create($input) {}

	//Read
	public function findById($id) {
		return $this->comment->where('id', $id)->first();
	}
	
	//Read Multi
	public function all() {}
	
	//Check
	public function owns($comment_id, $user_id) {
		return $this->comment
					->where('id', '=', $comment_id)
					->where('user_id', '=', $user_id)
					->count();
	}

	//Update
	public function update($input) {}
	
	public function publish($comment_id, $user_id) {
		$this->comment
					->where('id', '=', $comment_id)
					->where('user_id', '=', $user_id)
					->update(array('published' => 1));
	}
	public function unpublish($comment_id, $user_id) {
		$this->comment
					->where('id', '=', $comment_id)
					->where('user_id', '=', $user_id)
					->update(array('published' => 0));
	}
	
	//Delete
	public function delete($id) {}
	
}