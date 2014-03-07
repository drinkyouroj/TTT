<?php
/*
|--------------------------------------------------------------------------
| Confide Controller Template
|--------------------------------------------------------------------------
|
| This is the default Confide controller template for controlling user
| authentication. Feel free to change to your needs.
|
*/

class UserController extends BaseController {

	protected $layout = 'layouts.master';

	public function getSignup(){
		$this->getCreate();
	}

    /**
     * Displays the form for account creation
     *
     */
    public function getCreate()
    {
        $this->layout->content = View::make('user.signup');
    }

    /**
     * Stores new account
     *
     */
    public function postIndex()
    {
        $user = new User;

        $user->username = Input::get( 'username' );
		
		//If the signup form has no input for the email, then make it up.
		if(strlen(Input::get( 'email' )) == 0) {
			$user->email = Input::get( 'username' ).'@twothousandtimes.com';
		}else {
			$user->email = Input::get( 'email' );
		}
        
        $user->password = Input::get( 'password' );

        // The password confirmation will be removed from model
        // before saving. This field will be used in Ardent's
        // auto validation.
        $user->password_confirmation = Input::get( 'password_confirmation' );

        // Save if valid. Password field will be hashed before save
        $user->save();
		/*
		$userRole = Role::where('name', '=', 'Nobody')->first();
		
		$user->roles()->attach(1);//Attach the user role to a user.
		*/
        if ( $user->id )
        {
            // Redirect with success message, You may replace "Lang::get(..." for your custom message.
                        return Redirect::to('user/loginonly')
                            ->with( 'notice', Lang::get('confide::confide.alerts.account_created') );
        }
        else
        {
            // Get validation errors (see Ardent package)
            $error = $user->errors()->all(':message');

                        return Redirect::to('user/create')
                            ->withInput(Input::except('password'))
                ->with( 'error', $error );
        }
    }

    /**
     * Displays the login form
     *
     */
    public function getLogin()
    {
        if( Confide::user() )
        {
            // If user is logged, redirect to internal 
            // page, change it to '/admin', '/dashboard' or something
            return Redirect::to('/');
        }
        else
        {
            $this->layout->content = View::make('user.login');
        }
    }
	
	public function getLoginonly()
    {
        if( Confide::user() )
        {
            // If user is logged, redirect to internal 
            // page, change it to '/admin', '/dashboard' or something
            return Redirect::to('/');
        }
        else
        {
            $this->layout->content = View::make('user.loginonly');
        }
    }
	

    /**
     * Attempt to do login
     *
     */
    public function postLogin()
    {
        $input = array(
            'email'    => Input::get( 'email' ), // May be the username too
            'username' => Input::get( 'email' ), // so we have to pass both
            'password' => Input::get( 'password' ),
            'remember' => Input::get( 'remember' ),
        );

        // If you wish to only allow login from confirmed users, call logAttempt
        // with the second parameter as true.
        // logAttempt will check if the 'email' perhaps is the username.
        if ( Confide::logAttempt( $input ) ) 
        {
        	//YD change here: Let's store the UN and stuff.
        	$user = User::where('email', '=', $input['email'])
        			->orwhere('username', '=', $input['email'])
        			->first();
			
			//If this user is banned.....
			if($user->banned == 1) {
				Confide::logout();
				return Redirect::to('/banned');
			}
					
			Session::put('username', $user->username);
			Session::put('email', $user->email);
			Session::put('user_id', $user->id);
			Session::put('join_date', $user->created_at);
			Session::put('featured', $user->featured);
			
			if($user->featured != 0) {
				//Let's just grab the user's image.
				$user_featured = Post::where('id', $user->featured)->first();
				Session::put('image', $user_featured->image);
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
			
            // If the session 'loginRedirect' is set, then redirect
            // to that route. Otherwise redirect to '/'
            $r = Session::get('loginRedirect');
            if (!empty($r))
            {
                Session::forget('loginRedirect');
                return Redirect::to($r);
            }
            
            return Redirect::to('/profile'); // change it to '/admin', '/dashboard' or something
        }
        else
        {
            $user = new User;

            // Check if there was too many login attempts
            if( Confide::isThrottled( $input ) )
            {
                $err_msg = Lang::get('confide::confide.alerts.too_many_attempts');
            }
            elseif( $user->checkUserExists( $input ) and ! $user->isConfirmed( $input ) )
            {
                $err_msg = Lang::get('confide::confide.alerts.not_confirmed');
            }
            else
            {
                $err_msg = Lang::get('confide::confide.alerts.wrong_credentials');
            }

                        return Redirect::to('user/loginonly')
                            ->withInput(Input::except('password'))
                ->with( 'error', $err_msg );
        }
    }

    /**
     * Attempt to confirm account with code
     *
     * @param  string  $code
     */
    public function getConfirm( $code )
    {
        if ( Confide::confirm( $code ) )
        {
            $notice_msg = Lang::get('confide::confide.alerts.confirmation');
                        return Redirect::to('user/login')
                            ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = Lang::get('confide::confide.alerts.wrong_confirmation');
                        return Redirect::to('user/login')
                            ->with( 'error', $error_msg );
        }
    }

    /**
     * Displays the forgot password form
     *
     */
    public function getForgot()
    {
    	$this->layout->content = View::make('user.forgot');
        //return View::make(Config::get('confide::forgot_password_form'));
    }

    /**
     * Attempt to send change password link to the given email
     *
     */
    public function postForgot()
    {
        if( Confide::forgotPassword( Input::get( 'email' ) ) )
        {
            $notice_msg = Lang::get('confide::confide.alerts.password_forgot');
                        return Redirect::to('user/login')
                            ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_forgot');
                        return Redirect::to('user/forgot')
                            ->withInput()
                ->with( 'error', $error_msg );
        }
    }

    /**
     * Shows the change password form with the given token
     *
     */
    public function getReset( $token )
    {
        //return View::make(Config::get('confide::reset_password_form'))
        //        ->with('token', $token);
        $this->layout->content = View::make('user.reset')->with('token', $token);
    }

    /**
     * Attempt change password of the user
     *
     */
    public function postReset()
    {
        $input = array(
            'token'=>Input::get( 'token' ),
            'password'=>Input::get( 'password' ),
            'password_confirmation'=>Input::get( 'password_confirmation' ),
        );

        // By passing an array with the token, password and confirmation
        if( Confide::resetPassword( $input ) )
        {
            $notice_msg = Lang::get('confide::confide.alerts.password_reset');
                        return Redirect::to('user/login')
                            ->with( 'notice', $notice_msg );
        }
        else
        {
            $error_msg = Lang::get('confide::confide.alerts.wrong_password_reset');
                        return Redirect::to('user/reset/'.$input['token'])
                            ->withInput()
                ->with( 'error', $error_msg );
        }
    }

    /**
     * Log the user out of the application.
     *
     */
    public function getLogout()
    {
        Confide::logout();
		Session::flush();
		Session::regenerate();
        return Redirect::to('/');
    }


	public function getBanned()
	{
		return View::make('user.banned');
	}

}