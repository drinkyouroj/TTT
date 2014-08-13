<?php
/**
 * Messages Controller
 * 
 * Handles things to do with messages
 * 
 */
class MessageController extends BaseController {

	//Not a lot in the constructor for now!
	public function __construct(
								FollowRepository $follow
								) {
		$this->follow = $follow;
	}

	/**
	 * Messages inbox
	 */
	public function getMessageInbox() {
		
		//Let's first grab the threads you own.
		$threads = Message::where('reply_id', 0)
							->where(function($query) {
								$query->where('from_uid', Auth::user()->id)
										->orWhere('to_uid', Auth::user()->id);
							})->orderBy('last_mod', 'DESC')//last modified date.
							->get();
						
		return View::make('messages/inbox')
					->with('fullscreen', true)
					->with('threads', $threads);
	}
	
	/**
	 * Message and Message Form.
	 * @param user_id $user_id User id of the person that you're writing a message to.
	 */
	public function getMessageForm($user_id = false) {
		//If there is a user id that's defined, we check to make sure that it 
		if($user_id) {
			//See if the user has previous messages with this user.
			$to = Message::where('from_uid', $user_id)->where('to_uid', Auth::user()->id);
			$from = Message::where('to_uid', $user_id)->where('from_uid', Auth::user()->id);
			
			$reply_id = false;
			
			if($to->count()) {
				//we've either talked to them
				$reply_id = $to->first()->reply_id;
			} elseif($from->count()) {
				//or they've talked to us
				$reply_id = $from->first()->reply_id;
			}
			
			if($reply_id) {
				//Redirect to the replymessage if the user already has a thread going.
				return Redirect::to('profile/replymessage/'.$reply_id);
			} else {
				//No Thread, so must be a new message
				$user = User::where('id', $user_id)->first(); 
				return View::make('messages/form')
				->with('fullscreen', true)
				->with('message_user', $user)
				->with('newmessage', true);
			}
			
		} else {
			$mutuals = $this->follow->mutual_list(Auth::user()->id);//gets the mutual users.
			return View::make('messages/form')
				->with('fullscreen', true)
				->with('message_user', false)
				->with('newmessage',true)
				->with('mutuals', $mutuals);
		}
	}
	
	/**
	 * Message reply situation.
	 */
	public function getMessageReplyForm($reply_id=false) {
		
		//Grab the parent message.
		$message = Message::where('id',$reply_id)
						->orWhere('to_uid', 0);
		
		if($message->count()) {
			//Grab the rest of the thread
			$thread =  Message::where('reply_id',$message->first()->id)
								->where(function($query) {
									$query->where('to_uid', Auth::user()->id)
										  ->orWhere('from_uid', Auth::user()->id);
								})//grab all the related messages.
								->orderBy('created_at','ASC')
								->get();
								
			$message = $message->first();
			
			$user = User::where('id', '=', $message->from_uid)->first();
			
		} else {
			$user = false;
			$thread = false;
		}
		
		return View::make('messages/form')
				->with('fullscreen', true)
				->with('message', $message)
				->with('thread', $thread)
				->with('message_user', $user);
	}
	
	/**
	 * Saves and stores the messages that are received in the correct way.
	 * 
	 */
	public function postMessageForm() {
		//Let's first make sure the data is good.
		$message = self::message_object_input_filter();//Post object takes objects.
		$validator = $message->validate($message->toArray());//validation takes arrays
		
		
		if($validator->passes()) {
			//Successful Validation
			$user_id = Auth::user()->id;
			$is_following = $this->follow->is_following($user_id, $message->to_uid);
			$is_follower = $this->follow->is_follower($user_id, $message->to_uid);
			
			if($this->follow->mutual($user_id, $message->to_uid)) {
				$mutual = true;
			} else {
				return View::make('generic/error')
						->with('message', "Not a Mutually Followed/Following user.");
			}
			
			//Let's check to see if the users have a thread running already if there is no reply id.
			$to = Message::where('from_uid', $message->to_uid)
						->where('to_uid', Auth::user()->id)
						->where('reply_id', 0);
						
			$from = Message::where('to_uid', $message->to_uid)
						->where('from_uid', Auth::user()->id)
						->where('reply_id', 0);
			
			//Let's set the thread reply id so that we can get that over with.
			if($to->count()) {
				$message->reply_id = $to->first()->id;
			} elseif($from->count()) {
				$message->reply_id = $from->first()->id;
			} else {
				//Actually a new message!
				$message->reply_id = 0;
			}
			
			if($mutual) {
				$message->save();
				
				$mot = new Motification;
				$mot->notification_type = 'message';
				$mot->post_id = 0;
				$mot->noticed = 0;
				$mot->user_id = $message->to_uid;
				$mot->user = User::find($message->from_uid)->username;
				$mot->users = array(User::find($message->from_uid)->username);
				$mot->save();
				
				//Let's update the parent thread's last mod and last ids so that the thread knows to order it.
				Message::where('id', $message->reply_id)
						->update(array(
								'last_mod'=> date('Y-m-d H:i:s'),
								'last_id'=> $message->id)
								);
				
				
				return Redirect::to('profile/messages');
			} 
		} else {
			//not successful validation
			if($message->reply_id != 0 || !empty($message->reply_id)) 
			{
				//this is a reply situation
				return Redirect::to('profile/replymessage/'.$message->reply_id)->withErrors($validator)->withInput();
			} else {
				//this is a new message situation
				return Redirect::to('profile/newmessage/')->withErrors($validator)->withInput();;
			}
		}
	}
		/**
		 * Gets Message Input from the form. 
		 *  
		 * @param new $new is this a new message or is this an update?
		 * @return message $message A new message Object with all the inputs.
		 */
		private function message_object_input_filter()
		{
			//Creates a new post
			$message = new Message;
			$message->from_uid = Auth::user()->id;
			$message->to_uid = Request::get('to_uid');
			$message->reply_id = Request::get('reply_id');//which message are we replying to?			
			$message->body = Request::get('body');
			
			return $message;
		}
}
?>