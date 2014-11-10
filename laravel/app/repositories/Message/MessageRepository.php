<?php
namespace AppStorage\Message;

interface MessageRepository {

	public function instance ();

	public function create ( $data );

	public function delete ( $id );

	public function getAllThreads ( $user_id );

	public function getThread ( $thread_id );
	public function getThread ( $user_a, $user_b );

}