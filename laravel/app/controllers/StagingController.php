<?php
/**
 *	This controller is only used for reserving usernames during the staging process.
 *	TODO: this will/should be removed post launch
 */

class StagingController extends BaseController {

	public function __construct( UserRepository $user ) {
		$this->user = $user;
		/**
		 *	Used to ensure a reservation cap on usernames! use in validation as follows
		 *	reservation_cap:{max usernames}
		 *	ex: reservation_cap:2
		 */
		Validator::extend('reservation_cap', function($attribute, $value, $parameters) {
		    $cap = $parameters[0];
		    $users = User::where( 'email', $value )->count();
		    return $users < $cap;
		});
	}

	/**
	 *	Handle a post request for reserving username
	 */
	public function postReserveUsername () {
		$username = Input::has('username') ? Input::get('username') : null;
		$email = Input::has('email') ? Input::get('email') : null;
		$top_secret = Input::has('top_secret') ? Input::get('top_secret') : null;

		// Make sure 
		if ( $top_secret == null || $top_secret != 'spider_pig_2014' ) {
			return Response::json( array( 'error' => array( 'general' => 'Go away.' ) ), 200 );
		}

		$validation = Validator::make(
				// Data to be validated
				array( 
					'username' => $username,
					'email' => $email
				),
				// Validation rules
				array(
					'username' => 'required|min:3|max:15|unique:users|alpha_dash', 
					'email' => 'required|email|reservation_cap:3'
				),
				// Error messages
				array(
					'username.required' => 'No username was provided',
					'username.min' => 'Username must be at least 3 characters long',
					'username.max' => 'Username must be less than 15 characters long',
					'username.unique' => 'Oops! That username has already been reserved',
					'username.alpha_dash' => 'The username contains invalid characters. Available characters: a-z,0-9,-, and _',
					'email.required' => 'No email was provided',
					'email.reservation_cap' => 'The given email has reached the maximum number of username reservations',
					'email.email' => 'Invalid email'
				)
		);

		if ( $validation->fails() ) {
			// Failed to validate, return erros
			return Response::json( 
				array( 
					'error' => $validation->messages()->toArray()
				), 200 );
		} else {
			// Succesfully validated, proceed to create user
			$data = array();
			$data['username'] = $username;
			$data['email'] = $email;
			$data['password'] = $this->generateRandomString(10);
			$data['reserved'] = 1;
			$data['password_confirmation'] = $data['password'];
			$results = $this->user->create( $data, true );
			if ( $results['user'] ) {
				return Response::json( array( 'success' => 'Thank you! You have succesfully reserved the username: '.$username ), 200 );
			} else {
				return Response::json( array( 'error' => $results['validation']->errors()->toArray() ), 200 );
			}
		}
	}

	private function generateRandomString($length = 5) {
	    return substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, $length);
	}
}