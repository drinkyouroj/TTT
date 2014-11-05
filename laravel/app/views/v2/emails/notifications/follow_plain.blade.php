{{$user->username}},

{{$follower->username}} has followed you! To view {{$follower->username}}’s profile, click {{URL::to('profile/'.$follower->username)}}. 

You can edit your email notifications in account settings.