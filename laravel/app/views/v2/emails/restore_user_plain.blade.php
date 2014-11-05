Hi {{$user->username}},

Welcome back to Sondry!

You can restore your account here:
{{Config::get('app.secureurl')}}/user/restore/{{$user->restore_confirm}}

Team Sondry