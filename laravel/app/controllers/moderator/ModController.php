<?php
class ModController extends Controller {
	
	public function __construct() {
		
	}
	
	public function getIndex() {
		
	}
	
	public function getDelPost() {
		$id = Request::segment(3);
		if($id != 0) {
			$post = Post::where('id', $id)->first();
			if($post->published == true) {
				//Unpublish
				Post::where('id', $id)->update(array('published'=>false));
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			} else {
				//Publish
				Post::where('id', $id)->update(array('published'=>true));
				//return the body so that we can display it.
				return Response::json(
					array('result'=>$post->body),
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
			$comment = Comment::where('id', $id)->first();
			if($comment->published == true) {
				//Unpublish
				Comment::where('id', $id)->update(array('published'=>false));
				return Response::json(
					array('result'=>'deleted'),
					200//response is OK!
				);
			} else {
				//Publish
				Comment::where('id', $id)->update(array('published'=>true));
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