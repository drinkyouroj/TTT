<?php
namespace AppStorage\Comment;

interface CommentRepository {

	public function instance();

	public function create( $user_id, $username, $reply_id, $post_id, $comment_body );
	public function update($input);
	public function delete($id);

	public function findById ( $id );
	public function findByPostId ( $post_id, $paginate, $page );
	public function allByUserId($user_id, $paginate, $page, $rest);

	public function owns($comment_id, $user_id);
	
	public function like( $user_id, $comment_id );
	public function unlike( $user_id, $comment_id );
	public function flag( $user_id, $comment_id );
	public function unflag( $user_id, $comment_id );
	
	public function publish($comment_id);
	public function unpublish($comment_id);
	
}