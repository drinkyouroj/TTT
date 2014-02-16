<?php
/********************************************************************
 * Messages
*/
class MessageController extends BaseController {

	/**
	 * Messages inbox
	 */
	
	public function getMessageInbox() {
		$messages = Message::where('to_uid', Session::get('user_id'))
						->where('reply_id', 0)
						->orWhere('to_uid', 0)
						->orderBy('id', 'DESC')
						->get();
						
						
		return View::make('messages/inbox')
					->with('fullscreen', true)
					->with('messages', $messages);
	}
	
	/**
	 * Message form 
	 */
	public function getMessageForm($user_id=false, $reply_id = false) {
		if($reply_id) {
			//message you're replying to.
			$message = Message::where('id', '=', $reply_id)
							->where('to_uid', Auth::user()->id)
							->orWhere('to_uid', 0)
							->first();
			if($message->to_uid != 0) {
				//let's grab the thread parent's id.
				$thread =  Message::where('id', '=', $reply_id)
								->where('reply_id',0)
								->where('to_uid', Auth::user()->id)
								->first();
			}
							
			$user = User::where('id', '=', $message->from_uid)->first();
			return View::make('messages/form')
					->with('message', $message)
					->with('message_user', $user);
		} else {
			$user = User::where('id', '=', $user_id)->first();
			//Can't find that user?
			if(is_null($user)) {
				//Gotta find all the mutual follows!
				$mutuals = FollowHelper::mutual_list();
				return View::make('messages/form')
					->with('mutuals', $mutuals);
			} else {
				return View::make('messages/form')
					->with('message_user', $user);
			}
			
		}
	}
	
	public function getMessageReplyForm($reply_id=false) {
		if($reply_id) {
			return self::getMessageForm(false, $reply_id);
		} else {
			return Redirect::to('profile');
		}
	}
	
	
	public function postMessageForm() {
		
		$message = self::message_object_input_filter();//Post object takes objects.
		$validator = $message->validate($message->toArray());//validation takes arrays
		
		if($validator->passes()) {//Successful Validation
			//Gotta check to make sure that the user isn't messing with the reply id threading.
			$parent_check = Message::where('id', $message->reply_id)
									->where('reply_id',0)//Gotta be a parent
									->count();
			//Should return a zero.
			
			//Gotta check to see if they are mutually following.
			$is_following = FollowHelper::is_following($message->to_uid);
			$is_follower = FollowHelper::is_follower($message->to_uid);
			
			if($is_follower && $is_following) {
				$mutual = true;
			}
			
			if($mutual && !$parent_check) {
				$message->save();
				return Redirect::to('profile');
			} else {
				return View::make('generic/error')
						->with('message', "Done messed up A-A-RON");
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
		
		private function mutual_follow_check($uid1, $uid2) {
			
		}
}
?>