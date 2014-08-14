<?php namespace AppStorage\Repost;

use DB, Request, Auth, Repost;

class EloquentRepostRepository implements RepostRepository {

	public function __construct(Repost $repost)
	{
		$this->repost = $repost;
	}

	public function instance() {
		return new Repost;
	}

	public function create($user_id, $post_id) {
		$repost = self::instance();
		$repost->post_id = $post_id;
		$repost->user_id = $user_id;//Gotta be from you.
		$repost->save();
	}

	public function exists($user_id, $post_id) {
		return $this->repost->where('post_id', $post_id)
			->where('user_id', $user_id)
			->count();
	}

	public function has_reposted($user_id, $post_id) {
		return $this->repost->where('user_id', $user_id)
					->where('post_id', $post_id)
					->count();
	}

	public function delete($user_id, $post_id) {
		$this->repost->where('post_id', $post_id)
			->where('user_id', $user_id)
			->delete();
	}

}