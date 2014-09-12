<?php
namespace AppStorage\User;

use User,
	DB,
	Hash,
	Auth,
	Session,
	SolariumHelper,
	CommentRepository,
	PostRepository
	;
 
class SheepRepository implements UserRepository {

	public function __construct(
					User $user,
					CommentRepository $comment,
					PostRepository $post) {
		$this->user = $user;
		$this->comment = $comment;
		$this->post = $post;
	}

	public function instance() {
		return new User;
	}

	public function create($data, $json = false) {
		//Sanitize the data.
		$user = self::instance();
		$user->username = isset( $data['username'] ) ? $data['username'] : null;
		$user->email = isset( $data['email'] ) ? $data['email'] : null;
		$user->password = isset( $data['password'] ) ? $data['password'] : null;
		$user->password_confirmation = isset( $data['password_confirmation'] ) ? $data['password_confirmation'] : null;
		$user->captcha = isset( $data['captcha'] ) ? $data['captcha'] : null;

		//Run Validation.
		if($json) {
			unset($user->captcha);
			$user->reserved = 1;//if JSON call then its a reserve.
			$validation = $user->validateJSON($user->toArray());
		}else {
			$validation = $user->validate($user->toArray());
		}

		if($validation->fails()) {
			$result = array();
			$result['user'] = false;
			$result['validation'] = $validation;
			return $result;
		} else {
			unset($user->password_confirmation);
			unset($user->captcha);
			//validation passes
			$user->first = true;
			$user->confirmed = false;
			$user->confirmation_code = md5(date('Y-M-D H:i:s') . $user->username);
			$user->image = 0;
			$user->banned = 0;
			$user->password = Hash::make($user->password);
			$user->save();

			//Gotta add the new user to SOLR
        	//SolariumHelper::updateUser($user);
			$result = array();
			$result['user'] = $user;
			$result['validation'] = false;

			return $result;
		}
	}
	 
	public function exists($id) {
		$exists = $this->user->where('id',$id)->count();
		if($exists) {
			return true;
		} else {
			return false;
		}
	}

	public function usernameExists($username) {
		return $this->user->where('username', $username)->count();
	}
	 
	public function find($id) {
		return $this->user->find($id);
	}

	
	public function all() {
		//probably don't need it at all.
	}

	public function update($data) {
		
	}

	public function updatePassword($user, $password) {
		$user->password = Hash::make($password);
		$user->save();
	}

	public function delete($id) {
		$this->user->where('id', $id)->delete();
		// Soft delete users posts
		$this->post->deleteAllByUserId( $id );
		// Unpublish comments by this user
		$comments = $this->comment->findAllByUserId( $id );
		foreach ($comments as $comment) {
			$this->comment->unpublish( $comment->_id );
		}
	}

	public function restore($id) {
		$user =	$this->user->onlyTrashed()->where('id', $id)->first(); 
		if ( $user instanceof User ) {
			$user->restore();
			// Restore all their posts
			$this->post->restoreAllByUserId( $id );
			// Restore all their comments
			$comments = $this->comment->findAllByUserId( $id );
			foreach ($comments as $comment) {
				$this->comment->publish( $comment->_id );
			}
			return true;
		}
		return false;
	}

	public function login($data) {
		$user = $this->user->where('username', $data['username'])
							->first();

		$check = Auth::attempt(array('username' => $data['username'], 'password' => $data['password']));
		
		if( $check ) {
			//Logged In.

			//check to see if this user has been banned.
			if($user->banned) {
				return false;
			}

			//We're not really using these anymore, but just in case for now.
			Session::put('username', $user->username);
			Session::put('email', $user->email);
			Session::put('user_id', $user->id);
			//Session::put('join_date', $user->created_at);
			Session::put('featured', $user->featured);
			Session::put('first', $user->first);

			
			//update set (bunched so we don't have to )
			//first login?
			if($user->first || $user->forgot_pass) {
				if($user->first) {
					$user->first = false;
				}
				if($user->forgot_pass) {
					$user->forgot_pass = false;
					$user->verified = true;//when the user forgets their pass and resets it, we know that its verified for sure.
				}
				$user->update();
			}

			//Is this user an admin?
			if($user->hasRole('Admin')) {
				//this was more convienent in some places as pulling the user is a pain in the ass.
				Session::put('admin', 1);
			} else {
				Session::put('admin', 0);
			}
			
			//Is this user a moderator?
			if($user->hasRole('Moderator')) {
				Session::put('mod', 1);
			} else {
				Session::put('mod', 0);
			}
			
			return $user;
		} else {
			//Bad News bears.
			//Maybe add to a session variable and enable throttling for later.
			if( intval( Session::get('login-attempt') ) ) {
				$attempt = 0;
			} else {
				$attempt = Session::get('login-attempt');
			}
			
			$attempt++;

			Session::put('login-attempt', $attempt);
			return false;
		}

	}
	
	public function logout() {
		//Laravel logout.
		Auth::logout();

		//clear the session and regenerate it.
		Session::flush();
		Session::regenerate();
	}

	public function check($id) {

	}

	public function confirm($code) {
		$user = $this->user->where('confirmation_code',$code)->first();
		if($user instanceof User) {
			$user->verified = 1;
			$user->save();
			return true;
		} else {
			return false;
		}
	}

	public function register($data) {

	}

	public function ban($id) {
		$user = $this->user->where('id', $id)->first();
		if ( $user instanceof User ) {
			$user->banned = 1;
			$user->save();
			return true;
		}
		return false;
	}

	public function unban($id) {
		$user = $this->user->where('id', $id)->first();
		if ( $user instanceof User ) {
			$user->banned = 0;
			$user->save();
			return true;
		}
		return false;
	}

	public function resetPassword ( $id ) {
		$user = $this->user->where('id', $id)->first();
		if ( $user instanceof User ) {
			$new_password = $this->generateRandomString(8);	
			$user->password = Hash::make($new_password);
			$user->save();
			return array(
				'new_password' => $new_password,
				'user' => $user
				);
		}
		return false;
	}

	public function forgotPassword($email, $username) {
		$user = $this->user->where('email', $email)
							->where('username', $username)
							->first();

		if($user instanceof User) {
			//have to mark as true.
			$user->forgot_pass = true;
			$user->update();
			return $this->resetPassword($user->id);//I know this isn't the most efficient, but it works.
		}

		return false;
	}

	public function getUserCount() {
		return $this->user->where( 'banned', 0 )->count();
	}
	public function getConfirmedUserCount() {
		return $this->user->where( 'banned', 0 )->where( 'confirmed', 1 )->count();
	}
	public function getUserCreatedTodayCount() {
		return $this->user->where( 'created_at', '>=', new \DateTime('today') )->count();
	}

		/**
		 *	Simple random string generator.
		 */
		private function generateRandomString($length = 5) {
		    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
		}

}