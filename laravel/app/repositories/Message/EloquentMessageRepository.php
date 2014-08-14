<?php namespace AppStorage\Message;

use Message, DB, Request, Auth;

class EloquentMessageRepository implements MessageRepository {

	public function __construct(Message $message)
	{
		$this->message = $message;
	}

	public function instance() {
		return new Message;
	}

	public function create() {

	}

	//grabs the message threads that you own.
	public function findThreads($user_id) {
		return $this->message
				->where('reply_id', 0)
				->where(function($query) {
					$query->where('from_uid', $user_id )
							->orWhere('to_uid', $user_id );
				  })
				->orderBy('last_mod', 'DESC')//last modified date.
				->get();
	}

	//Note, 
	public function findPrevious($my_id, $other_id) {
		$to = 	$this->message
				->where('from_uid', $other_id)
				->where('to_uid', $my_id);

		$from = $this->message
				->where('to_uid', $other_id)
				->where('from_uid', $my_id);

		$reply_id = false;
			
		if($to->count()) {
			//we've either talked to them
			$reply_id = $to->first()->reply_id;
		} elseif($from->count()) {
			//or they've talked to us
			$reply_id = $from->first()->reply_id;
		}

		return $reply_id;
	}

	public function findParent($reply_id) {
		
	}

	public function getPreviousFrom($user_id) {
		
	}

	public function exists() {

	}

	public function delete() {

	}

}