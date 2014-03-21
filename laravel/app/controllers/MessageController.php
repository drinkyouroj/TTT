<?php
/********************************************************************
 * Messages
*/
class MessageController extends BaseController {

	public function __construct() {
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
		//			->with('messages', $messages)
					->with('threads', $threads);
	}
	
	/**
	 * Message and Message Form.
	 */
	public function getMessageForm($user_id = false) {
		
		if(!$user_id) {
			$to = Message::where('from_uid', $user_id)->where('to_uid', Auth::user()->id);
			$from = Message::where('to_uid', $user_id)->where('from_uid', Auth::user()->id);
			
			if($to->count()) {
				$reply_id = $to->first()->reply_id;
				$user = User::where('id', '=', $reply_id);
			} elseif($from->count()) {
				$reply_id = $from->first()->reply_id;
				$user = User::where('id', '=', $reply_id);			
			} else {
				return self::getMessageReplyForm();
			}
			
		} else {
			$user = User::where('id', '=', $user_id);
		}
		
		if(isset($user) && $user->count()) {
			//user actually exists.
			return View::make('messages/form')
				->with('fullscreen', true)
				->with('message_user', $user->first());
		} else {
			//This is truly a new situation and the user id didnt' exist.
			$mutuals = FollowHelper::mutual_list();
			return View::make('messages/form')
				->with('fullscreen', true)
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
	
	
	public function postMessageForm() {
		//Let's first make sure the data is good.
		$message = self::message_object_input_filter();//Post object takes objects.
		$validator = $message->validate($message->toArray());//validation takes arrays
		
		if($validator->passes()) {//Successful Validation
			//Gotta check to see if they are mutually following.
			$is_following = FollowHelper::is_following($message->to_uid);
			$is_follower = FollowHelper::is_follower($message->to_uid);
			
			if($is_follower && $is_following) {
				$mutual = true;
			} else {
				return View::make('generic/error')
						->with('message', "Done messed up A-A-RON");
			}
			
			//Let's check to see if the users have a thread running already if there is no reply id.
			$to = Message::where('from_uid', $message->to_uid)->where('to_uid', Auth::user()->id)->where('reply_id', 0);
			$from = Message::where('to_uid', $message->to_uid)->where('from_uid', Auth::user()->id)->where('reply_id', 0);
			
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
				
				//Let's update the parent thread's last mod and last ids so that the thread knows to order it.
				Message::where('id', $message->reply_id)
						->update(array(
								'last_mod'=> date('Y-m-d H:i:s'),
								'last_id'=> $message->id)
								);
				
				
				
				return Redirect::to('profile/messages');
			} 
		} else {
			if($message->reply_id != 0 || !empty($message->reply_id)) {
				//this is a reply situation
				return Redirect::to('profile/replymessage/'.$message->reply_id)->withErrors($validator)->withInput();
			} else {
				//this is a new message situation
				return Redirect::to('profile/newmessage/')->withErrors($validator)->withInput();;
			}
		}
	}
	
	
		private function message_object_input_filter($new = false)
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