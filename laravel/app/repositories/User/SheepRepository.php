<?php
namespace AppStorage\User;

use User,
	DB,
	Hash,
	Auth,
	Session,
	SolariumHelper
	;
 
class SheepRepository implements UserRepository {

	public function __construct(
			User $user
		) {
		$this->user = $user;
	}

	public function instance() {
		return new User;
	}

	public function create($data) {
		//Sanitize the data.
		$user = self::instance();
		$user->username = $data['username'];
		$user->email = $data['email'];
		$user->password = $data['password'];
		$user->password_confirmation = $data['password_confirmation'];
		$user->captcha = $data['captcha'];

		//Run Validation.
		$validation = $user->validate($user->toArray());

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
		$user->passwod = Hash::make($password);
		$user->save();
	}

	public function restore($id) {
		return $this->user->onlyTrashed()->where('id', $id)->restore();
	}

	public function delete($id) {
		$this->user->where('id', $id)->remove();
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

			if($user->first) {
				$user->first = false;
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
			$attempt = empty(Session::get('login-attempt')) ? 0 : Session::get('login-attempt');
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

	public function confirm($data) {

	}

	public function register($data) {

	}

}