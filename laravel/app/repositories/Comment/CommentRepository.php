<?php
namespace AppStorage\Comment;

interface CommentRepository {

	public function instance();

	public function create( $user_id, $username, $reply_id, $post_id, $comment_body );
	public function update($input);
	public function editBody($comment_id, $body);
	public function delete($id);

	public function findById ( $id );
	public function findByPostId ( $post_id, $paginate, $page );
	public function findByUserId($user_id, $paginate, $page, $rest);
	public function findAllByUserId ( $user_id );
	public function findByCommentAndPostId ( $comment_id, $post_id );

	public function owns($comment_id, $user_id);
	
	public function like( $user_id, $comment_id );
	public function unlike( $user_id, $comment_id );
	public function flag( $user_id, $comment_id );
	public function unflag( $user_id, $comment_id );
	
	public function publish($comment_id);
	public function publishAllByUser($user_id);
	public function unpublish($comment_id);
	public function unpublishAllByUser($user_id);
	
}