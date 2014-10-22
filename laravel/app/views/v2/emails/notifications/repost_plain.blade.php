{{$post->user->username}},

{{$user->username}} has reposted “{{$post->title}}” To view {{$user->username}}’s profile, click <a href="{{URL::to('profile/'.$user->username) }}">here</a>. 

You can edit your email notifications in account settings.	