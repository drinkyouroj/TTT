<?php namespace AppStorage\Comment;

use Comment, DB, Request;

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
	public function create($user_id, $username) {
		//TODO: if we use this repository again we need to implement this.
	}

	//Read
	public function findById($id) {
		return $this->comment->where('id', $id)->first();
	}
	
	public function findByPostId ( $post_id, $paginate, $page ) {}
	
	//Read Multi
	public function all() {}

	public function allByUserId($user_id, $paginate = 5, $page = 1, $rest = false) {
		$query = $this->comment
						->where('user_id', $user_id)
						->orderBy('updated_at', 'DESC')
						->skip(($page-1)*$paginate)
						->take($paginate);
						;
		if($rest) {
			return $query->with('post.user')->get();
		} else {
			return $query->get();
		}

	}
	
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