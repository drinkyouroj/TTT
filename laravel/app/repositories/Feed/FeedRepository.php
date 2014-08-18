<?php
namespace AppStorage\Feed;

interface FeedRepository {

		//Instance
	public function instance();

	public function create($data);

	public function exists($user_id, $post_id, $feed_type, $count);

	public function find($user_id, $paginate, $page, $rest);

	public function delete($data);

}