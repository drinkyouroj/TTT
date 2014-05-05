<?php
/**
 * Moderator Controller
 * This Class holds functions for moderators.  It currently has only the Async functions.
 * Note that this function is protected via a router based filter.
 */
class ModController extends Controller {
	
	public function __construct(
								PostRepository $post,
								CommentRepository $comment
								) {
		$this->post = $post;
		$this->comment = $comment;
	}
	
	
	public function getDelPost() {
		$id = Request::segment(3);
		if($id != 0) {
			$post = $this->post->findById($id, 'any');
			if($post->published == true) {
				//Unpublish
				$this->post->unpublish($id);
				
				SolariumHelper::deletePost($id);//Gotta get rid of it from the search system.
				
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			} else {
				//RePublish
				$this->post->publish($id);
				
				SolariumHelper::updatePost($post);//Gotta add back the post.
				
				return Response::json(
					array('result'=>$post->body),//return the body so that we can display it.
					200//response is OK!
				);
			}
		} else {
			return Response::json(
				array('result'=>'fail'),
				200//response is OK!
			);
		}
	}
	
	public function getDelComment() {
		$id = Request::segment(3);
		if($id != 0) {
			$comment = $this->comment->findById($id);
			if($comment->published == true) {
				//Unpublish
				$this->comment->unpublish($id, $comment->user_id);
				
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			} else {
				//Publish
				$this->comment->publish($id, $comment->user_id);
				//return the body so that we can display it.
				return Response::json(
					array('result'=>$comment->body),
					200//response is OK!
				);
			}
		} else {
			return Response::json(
				array('result'=>'fail'),
				200//response is OK!
			);
		}
	}
	
	public function getBan() {
		$id = Request::segment(3);
		if($id != 0) {
			
			$user = User::where('id', $id)->first();
			if($user->banned == true) {
				//Unban
				User::where('id', $id)->update(array('banned'=>false));
				return Response::json(
					array('result'=>'unbanned'),
					200//response is OK!
				);
			} else {
				//Ban
				User::where('id', $id)->update(array('banned'=>true));
				return Response::json(
					array('result'=>'banned'),
					200//response is OK!
				);
			}
		} else {
			return Response::json(
				array('result'=>'fail'),
				200//response is OK!
			);
		}
	}
	
}