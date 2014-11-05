Welcome {{$user->username}},
	Your TTT account has been activated. Please click the link below to verify your account. Enjoy!
	
	{{Config::get('app.secureurl')}}/user/confirm/{{$user->confirmation_code}}

	Team Sondry

	sondry.com