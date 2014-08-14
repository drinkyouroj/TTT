<?php
namespace AppStorage\Message;

interface MessageRepository {

	//Instance
	public function instance();

	public function create($data);

	public function findThreads($user_id);

	public function findThread($user_id, $reply_id);

	public function findPrevious($my_id, $other_id);

	public function findParent($reply_id);

	public function getPreviousFrom($user_id);

	public function exists();

	public function updateLast($reply_id, $message_id);

	public function delete();

}