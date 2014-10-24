{{$comment->post->user->username}},

{{$user->username}} has commented on “{{$comment->post->title}}” To view {{$user->username}}’s comment, click <a href="{{URL::to('posts/'.$comment->post->alias. '#comment-'. $comment->_id)}}">here</a>. 

You can edit your email notifications in account settings.