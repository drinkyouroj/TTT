{{$parent_user->username}},

{{$user->username}} has replied to your comment “{{ Str::limit(strip_tags($parent->body),30) }}” on “{{$parent->post->title}}” To view {{$user->username}}’s comment, click {{URL::to('posts/'.$comment->post->alias . '#comment-'. $comment->_id )}}.

You can edit your email notifications in account settings.