<?php namespace AppStorage\Message;

use Message, DB;

class EloquentMessageRepository implements MessageRepository {

	public function __construct(Message $message)
	{
		$this->message = $message;
	}

	public function instance() {
		return new Message;
	}

	public function create($data) {
		$message = self::instance();
		$message->from_uid = $data['from_uid'];
		$message->to_uid = $data['to_uid'];
		$message->reply_id = $data['reply_id'];//which message are we replying to?			
		$message->body = $data['body'];

		//below looks weird and tricky, but trust me, it works.
		$validator = $message->validate($message->toArray());
		if($validator->passes()) {
			//Successful Validation			
			//Let's check to see if the users have a thread running already if there is no reply id.
			$message->reply_id = self::findPrevious($message->from_uid, $message->to_uid);
			
			//save!
			$message->save();

			return $message;
		} else {
			return false;
		}
	}

	//grabs the message threads that you own.  This grabs all the threads.
	public function findThreads($user_id) {
		//below is an excellent example of how to pass a variable to a closure from the outside.
		return $this->message
				->where('reply_id', 0)
				->where(function($query) use ($user_id) {
					$query->where('from_uid', $user_id )
							->orWhere('to_uid', $user_id );
				  })
				->orderBy('last_mod', 'DESC')//last modified date.
				->get();
	}

	public function findThread($user_id, $reply_id) {
		return $this->message
					->where('reply_id',$reply_id)
					->where(function($query) use ($user_id) {
						$query->where('to_uid', $user_id)
							  ->orWhere('from_uid', $user_id);
					})//grab all the related messages.
					->orderBy('created_at','ASC')
					->get();
	}

	//Note, 
	public function findPrevious($my_id, $other_id) {
		$to = 	$this->message
				->where('from_uid', $other_id)
				->where('to_uid', $my_id)
				->where('reply_id', 0);

		$from = $this->message
				->where('to_uid', $other_id)
				->where('from_uid', $my_id)
				->where('reply_id', 0);

		$reply_id = 0;
			
		if($to->count()) {
			//we've either talked to them
			$reply_id = $to->first()->id;
		} elseif($from->count()) {
			//or they've talked to us
			$reply_id = $from->first()->id;
		}

		return $reply_id;
	}

	public function findParent($reply_id) {
		return $this->message
				->where('id',$reply_id)
				->orWhere('to_uid', 0);
	}

	public function getPreviousFrom($user_id) {
		
	}

	public function exists() {

	}

	public function updateLast($reply_id, $message_id) {
		$this->message->where('id', $reply_id)
						->update(array(
								'last_mod'=> date('Y-m-d H:i:s'),
								'last_id'=> $message_id)
								);
	}

	public function delete() {

	}

}