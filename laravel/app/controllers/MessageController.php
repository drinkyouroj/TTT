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
								FollowRepository $follow,
								MessageRepository $message,
								NotificationRepository $not
								) {
		$this->follow = $follow;
		$this->message = $message;
		$this->not = $not;
	}

	/**
	 * Messages inbox
	 */
	public function getMessageInbox() {
		
		//Let's first grab the threads you own.
		$threads = $this->message->findThreads(Auth::user()->id);
						
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
			$reply_id = $this->message->findPrevious(Auth::user()->id, $user_id);
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
		$message = $this->message->findParent($reply_id);
		
		if($message->count()) {
			//Grab the rest of the thread
			$thread =  $this->message
							->findThread(Auth::user()->id, $message->first()->id);
				
			$message = $message->first();
			
			$user = User::where('id', $message->from_uid)
						->first();
			
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
		$user_id = Auth::user()->id;

		//first, make sure that there is a mutual follow status.
		if($this->follow->mutual($user_id, Request::get('to_uid'))) {
			//Let's first make sure the data is good.
			$data = array();
			$data['from_uid'] = $user_id;
			$data['to_uid'] = Request::get('to_uid');
			$data['reply_id'] = Request::get('reply_id');//which message are we replying to?			
			$data['body'] = Request::get('body');

			$message = $this->message->create($data);//creates a 
			
			//If the message is saved, it'll return an object, if not, it'll return false.
			if($message) {
				//generate a notification everytime a new message is sent out from one user to another.
				$not_data = array(
					'post_id' => 0,
					'post_title' => 0,
					'post_alias' => 0,
					'user_id' => $message->to_uid,
					'user' => User::find($message->from_uid)->username,
					'users' => array(User::find($message->from_uid)->username),
					'noticed' => 0,
					'notification_type' => 'message'
					);

				$not = $this->not->create($not_data);
				
				//Let's update the parent thread's last mod and last ids so that the thread knows to order it.
				$this->message->updateLast($message->reply_id, $message->id);
				
				return Redirect::to('profile/messages');

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
		} else {
			return View::make('generic/error')
					->with('message', "Not a Mutually Followed/Following user.");
		}
	}
}
?>