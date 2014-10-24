{{$user->username}},

{{$follower->username}} has followed you! To view {{$follower->username}}’s profile, click <a href="{{URL::to('profile/'.$follower->username)}}">here</a>. 

You can edit your email notifications in account settings.