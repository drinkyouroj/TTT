{{$comment->post->user->username}},

{{$user->username}} has commented on “{{$comment->post->title}}” To view {{$user->username}}’s comment, click {{URL::to('posts/'.$comment->post->alias. '#comment-'. $comment->_id)}}. 

You can edit your email notifications in account settings.