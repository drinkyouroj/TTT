{{$post->user->username}},

{{$user->username}} has liked “{{$post->title}}” To view {{$user->username}}’s profile, click {{ URL::to('profile/'.$user->username ) }}. 

You can edit your email notifications in account settings.