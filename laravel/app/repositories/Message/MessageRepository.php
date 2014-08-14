<?php
namespace AppStorage\Message;

interface MessageRepository {

	//Instance
	public function instance();

	public function create($data);

	public function exists($user_id, $post_id);

	public function delete($user_id, $post_id);

}