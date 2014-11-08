<?php namespace AppStorage\Message;

use Message, DB;

class MongoMessageRepository implements MessageRepository {

	public function __construct(Message $messages)
	{
		$this->messages = $messages;
	}

	public function instance () {
		return new Message;
	}

	/**
	 * Creates a new message. 
	 *
	 *	@param data: array( to => 'user_id',
	 *						from => 'user_id',
	 *						body => 'text',
	 *						thread_id => 'id' (optional!)
	 *					  )
	 *	@return Message: the newly created message
	 *			false: invalid parameters
	 */
	public function create ( $data ) {
		if ( is_array($data) && isset($data['to']) && isset($data['from']) && isset($data['body']) ) {
			// We have all required params, proceed to create the message
			$new_message = self::instance();
			$new_message->to = $data['to'];
			$new_message->from = $data['from'];
			$new_message->body = $data['body'];
			$new_message->read = false;
			// Now we have to attach to an existing conversation (if applicable)
			if ( isset($data['thread_id']) ) {
				$new_message->thread_id = $data['thread_id'];
			} else {
				// Check to see if there already exists a conversation between to and from
				$result = $this->messages->where( function( $query ) use ( $new_message ) {
									$query->where('to', '=', $new_message->to)
										  ->where('from', '=', $new_message->from);
									})
							   ->orWhere( function( $query ) use ( $new_message ) {
									$query->where('to', '=', $new_message->from)
										  ->where('from', '=', $new_message->to);
									})
							   ->first();
				if ( $result instanceof Message ) {
					$new_message->thread_id = $result->thread_id;
				} else {
					// This is the first conversation id, we have to create one.
					// Might not be the best, but for now thread_id is just going to be the
					// md5 concatination of the two users involved
					$new_message->thread_id = md5($new_message->from.'\\'.$new_message->to);
				}
			}
			$new_message->save();
			return $new_message;
		} else {
			// Invalid params...
			return false;
		}
	}

	/**
	 *  Delete a message by id
	 */
	public function delete ( $id ) {
		$message = $this->messages->where('_id', '=', $id)->first();
		if ( $message instanceof Message ) {
			$message->delete();
		}
	}

	/**
	 *  Get all threads for a given user, grouped by thread_id and 
	 *	then ordered by date created.
	 *
	 *	@param user_id: target user
	 *	@return array: array of Messages
	 */
	public function getAllThreads ( $user_id ) {

		return $this->messages->where('to', '=', $user_id)
					   ->orWhere('from', '=', $user_id)
					   ->groupBy('thread_id')
					   ->orderBy('created_at')
					   ->get();			
	}

	/**
	 *	Get a single thread by the thread_id, ordered by date created
	 *	
	 *	@param thread_id: id of the thread
	 *	@return array: array of Messages
	 */
	public function getThread ( $thread_id ) {
		return $this->messages->where('thread_id', '=', $thread_id)
							  ->orderBy('created_at')
							  ->get();
	}

	/**
	 *	Get a single thread between two given users, ordered by date created
	 *	
	 *	@param user_a: id of one of the users
	 *	@param user_b: id of one of the users
	 *	@return array: array of Messages
	 */
	public function getThread ( $user_a, $user_b ) {
		return $this->messages->where( function( $query ) use ( $user_a, $user_b ) {
									$query->where('to', '=', $user_a)
										  ->where('from', '=', $user_b);
									})
						      ->orWhere( function( $query ) use ( $user_a, $user_b ) {
									$query->where('to', '=', $user_b)
										  ->where('from', '=', $user_a);
									})
						      ->orderBy('created_at')
						      ->get();
	}

}