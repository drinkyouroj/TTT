Hi {{$user->username}},

	Your email has been updated.
	Updated Email: {{$user->updated_email}}

	To verify your account or update your email address please visit the link below:

	{{Config::get('app.secureurl')}}/user/emailupdate/{{$user->update_confirm}}

	sondry.com

	Thanks,
	TTT