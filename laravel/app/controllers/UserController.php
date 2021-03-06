<?php
use \Carbon\Carbon;


class UserController extends BaseController {

	public function __construct(
                        PostRepository $post,
                        UserRepository $user,
                        EmailRepository $email
                        ) {
		$this->post = $post;
        $this->user = $user;
        $this->email = $email;

        //check 
        $this->beforeFilter('csrf', 
                                array('only' =>
                                    array(
                                    'postIndex',
                                    'postLogin'
                                    )
                                )
                            );
        $this->beforeFilter('force_ssl', array('except' => 'getUserCheck'));

        $this->beforeFilter('cors_allow', array('only' => 'getRandomUsername'));
	}

    public function getRandomUsername () {
        // Fetch random usernames until we find one that is valid/doesnt exists already
        $username = self::randomValidUsername();
        return Response::json( array(
                'username' => $username
                ), 200 );
    }
    /**
     *  Generates a random username that is not taken and fits length constraints
     */
    public function randomValidUsername () {
        $invalid = true;
        $username = '';
        while ( $invalid ) {
            $username = self::generateRandomUsername();
            $invalid = !self::validUsername( $username );
        }
        return $username;
    }
        private function generateRandomUsername () {
            $noun_file = file(storage_path().'/data/nouns.txt');
            $noun = $noun_file[array_rand($noun_file)];
            $adj_file = file(storage_path().'/data/adjectives.txt');
            $adjective = $adj_file[array_rand($adj_file)];
            $random = trim($adjective).trim($noun);
            return $random;
        }
        private function validUsername ( $username ) {
            $length = strlen( $username );
            if ( $length == 0 || $length > 15 ) {
                return false;
            }
            return !$this->user->usernameExists( $username );
        }

	/**
	 * Signup
	 */
	public function getSignup(){
		return View::make('v2/users/signup_only');
	}

    /**
     * Displays the form for account creation
     *
     */
    public function getCreate()
    {	
        return View::make('v2/users/signup_only');
    }

    /**
     * Stores new account and also validates the captcha
     */
    public function postIndex()
    {
        //Data input
        $data = array();
        $data['username']   = Request::get('username');
        $data['email']      = Request::get('email');
        $data['password']   = Request::get('password');
        $data['password_confirmation'] = Request::get('password_confirmation');
        $data['captcha']    = Request::get('captcha');

        $redirect = Request::has('redirect') ? Request::get('redirect') : false;
        $restore_comment = Request::has('restore-comment') ? Request::get('restore-comment') : false;

        $result = $this->user->create($data);

        //If save goes well on the other side and passed validation, it will give the user object an ID.
        if(!$result['user']) {
            return Redirect::secure('user/create')
                ->withInput(Input::except('password'))
                ->withErrors($result['validation']);//returns validator if $user is not created.
        } else {
            //Auto Login on Creation.
            $user = $this->user->login($data);
            Session::put('signup_success',true);
            //Gotta send out email
            if(!empty($data['email']) ) {
                $email_data = array(
                    'from' => 'Sondry <no_reply@sondry.com>',
                    'to' => array($data['email']),
                    'subject' => 'Thanks for Joining Sondry!',
                    'plaintext' => View::make('v2/emails/new_user_plain')->with('user', $user)->render(),
                    'html'  => View::make('v2/emails/new_user_html')->with('user', $user)->render()
                    );

                $this->email->create($email_data);
            }

            // Redirect accordingly. If user signed up from a 
            if ( $redirect ) {
                if ( $restore_comment ) {
                    return Redirect::to( $redirect )->with( 'restore_comment', $restore_comment );    
                } else {
                    return Redirect::to( $redirect );
                }
            } else {
                return Redirect::secure('myprofile');    
            }
        }
    }

    /**
     * Displays the login form
     *
     */
    public function getLogin()
    {
        if( !Auth::guest() )
        {
            // If user is logged, redirect            
            return Redirect::to('/');
        }
        else
        {
            return View::make('v2/users/login_only');
        }
    }
	
	public function getLoginonly()
    {
        if( !Auth::guest() )
        {
            // If user is logged, redirect to internal 
            // page, change it to '/admin', '/dashboard' or something
            return Redirect::to('/');
        }
        else
        {
            return View::make('v2/users/login_only');
        }
    }
		
	/**
	 * Async Username checker
	 * 	This is used forthe signup process. 
	 */
	public function getUserCheck()
    {
		if( $this->user->usernameExists( Request::get('username') ) ) 
        {
			$val = false;
		} else {
			$val = true;
		}
		return Response::json(
			$val,
			200//response is OK!
		);
	}

    /**
     * Attempt to do login
     *
     */
    public function postLogin()
    {
        $input = array(
            'username' => Request::get( 'username' ), // so we have to pass both
            'password' => Request::get( 'password' )
        );
        
        $user = $this->user->login($input);

        if ( is_object($user) ) 
        {
            if($user->banned) {
                $this->user->logout();
                return Redirect::secure('user/banned');
            }

            // USER ANALYTICS - Start measuring user activity here
            AnalyticsLogic::createSession( Session::getId(), $user );

			//Gotta redirect to an acknowledge page if the user happens to have softDeleted their account
			if(Session::get('restored')) {
                /*
                //Below is the code for restore by e-mail incase we go back to that at some point.
				$rando_string = str_random(40);
                $user->restore_confirm = $rando_string;
                $user->save();

                $email_data = array(
                    'from' => 'no_reply@sondry.com',
                    'to' => array($user->email),
                    'subject' => 'Welcome back to Sondry!',
                    'plaintext' => View::make('v2/emails/restore_user_plain')->with('user', $user)->render(),
                    'html'  => View::make('v2/emails/restore_user_html')->with('user', $user)->render()
                    );

                $this->email->create($email_data);
                */
                $this->user->restore($user->id);

				return View::make('v2/users/undelete')
					->with('user',$user);
			}
            $user->last_logged_in = Carbon::now();
            $user->save();

            // If the session 'loginRedirect' is set, then redirect
            // to that route. Otherwise redirect to '/'
            $r = Session::get('loginRedirect');
            if (!empty($r) && strpos($r, '/rest/') === false)
            {
                Session::forget('loginRedirect');
                return Redirect::to($r);
            }
            
            return Redirect::to('myprofile'); // change it to '/admin', '/dashboard' or something
        }
        else
        {
            $user = $this->user->instance();

            // Check if there was too many login attempts
            if( Session::get('login-attempt') >= 5 )
            {
                $err_msg = 'Too many attempts';
            }
            else
            {
                $err_msg = 'Wrong Credentials';
            }

            return Redirect::secure('user/loginonly')
                ->withInput(Input::except('password'))
                ->with( 'error', $err_msg );
        }
    }

        public function getCaptcha() {
            $num1 = rand(1,9);
            $num2 = rand(1,9);
            Session::put('signup_num1', $num1 );
            Session::put('signup_num2', $num2 );
            $img =  Image::canvas(100,40, '#fffff');
            $img->text($num1.' + '.$num2. ' = ??', 40,20, function($font) {
                $font->file(3);
                $font->size(50);
                $font->color('#000000');
                $font->align('center');
            });

            return $img->response('jpg');
        }


		/**
		 * This has to do with undeleting the user.
		 * User has to acknowledge that the person will be 
		 */
		public function getRestore() {
			if($this->user->restoreByConfirmation(Request::segment(3))) {
				return Redirect::secure('user/loginonly')
                                ->with('notice', 'Your account has been restored!');
			} else {
				return Redirect::to('featured');
			}
		}


    /**
     * Attempt to confirm account with code
     *
     * @param  string  $code
     */
    public function getConfirm( $code )
    {
        if ( $this->user->confirm( $code ) )
        {
            $notice_msg = 'Your user is confirmed.';
                        return Redirect::secure('user/login')
                            ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = 'Wrong Code Bro.';
                        return Redirect::secure('user/login')
                            ->with( 'error', $error_msg );
        }
    }


    public function getEmailUpdate($confirm = false) {
        if($this->user->updateEmail($confirm) ) {
            return Redirect::secure('user/loginonly')
                            ->with('notice', 'Your email has been updated.');
        } else {
            // We should make a better distinction later
            return Redirect::secure('user/loginonly')
                            ->with('notice', 'Your email has already been updated.');;
        }
    }

    /**
     * Displays the forgot password form
     *
     */
    public function getForgot()
    {
    	return View::make('v2/users/forgot');
    }

    /**
     * Attempt to send change password link to the given email
     *
     */
    public function postForgot()
    {
        $email = Request::get('email');
        $username = Request::get('username');
        //Gotta run an e-mail validation first.
        $validator = Validator::make(
                        array(
                            'email' => $email,
                            'username' => $username
                            ),
                        array(
                            'email' => 'required|email',
                            'username' => 'required'
                            )
                        );

        if($validator->passes() ){
            $pass = $this->user->forgotPassword($email, $username);
            if( $pass )
            {
                $user = $pass['user'];
                $new_pass = $pass['new_password'];
                $plain = View::make('v2/emails/forgot_plain')
                            ->with('user', $user)
                            ->with('new_pass', $new_pass)
                            ->render();

                $html = View::make('v2/emails/forgot_html')
                            ->with('user', $user)
                            ->with('new_pass', $new_pass)
                            ->render();

                //send them the email
                $email_data = array(
                    'from' => 'Sondry <no_reply@sondry.com>',
                    'to' => array($user->email),
                    'subject' => "Here's your new credentials for Sondry.",
                    'plaintext' => $plain,
                    'html'  => $html
                    );

                $this->email->create($email_data);

                $notice_msg = 'Please check your e-mail.';
                            return Redirect::secure('user/login')
                                ->with( 'notice', $notice_msg );
            } else {
                $error_msg = 'Sorry, but your username/email combo does not have a match';
                            return Redirect::secure('user/forgot')
                                ->withInput()
                    			->with( 'error', $error_msg );
            }
        } else {
            return Redirect::secure('user/forgot')
                ->withInput()
                ->with( 'error', $validator->messages() );
        }
    }

    /**
     * Shows the change password form with the given token
     *
     */
    public function getReset( $token )
    {
        return View::make('user.reset')->with('token', $token);
    }


	/**
	 * Password reset from the backend while you're logged in.
	 */
	public function postNewpass() {

        $input = array(
            'username' => Auth::user()->username, // so we have to pass both
            'password' => Request::get( 'current_password' )
        );
        
        $user = $this->user->login($input);

		$failed = false;
		
        if ( $user ) 
        {
        	$new_input = array(
        			'password' => Input::get( 'password' ),
        			'password_confirmation' => Input::get( 'password_confirmation' )
				);
			
        	//Check to make sure that the new passwords match.
        	$rules = array(
        			'password' => 'required',
        			'password_confirmation' => 'required|same:password'
				);
				
			//make the validator	
        	$validator = Validator::make($new_input, $rules);
			
			if($validator->fails()) {
				//No good.  the passwords do not match
				$failed = true;
				$message = "The two passwords don't match! ";
				
			} else {
				//Actually update the damn passwords
                $user = Auth::user();
				$this->user->updatePassword($user, $new_input['password']);
			}

        } else {
        	//need to return that auth failed.
        	$failed = true;
			$message = 'Wrong current password. ';
        }
		
		if($failed) {
                return Response::json(
                    array(
                        'success' => false,
                        'message' => $message
                        ),
                    200
                );
		} else {
            return Response::json(
                    array(
                        'success' => true,
                        'message' => 'Nice!'
                        ),
                    200
                );
		}
		
	}

    /**
     * Log the user out of the application.
     *
     */
    public function getLogout()
    {
        $this->user->logout();

        return Redirect::to('/');
    }

	/**
	 * This is the route for banned users trying to login.  They're given an email address where they can explain themselves.
	 */
	public function getBanned()
	{
		return View::make('v2.users.banned');
	}

}