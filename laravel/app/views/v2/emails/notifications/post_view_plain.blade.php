{{$post->user->username}},

Your post, “{{$post->title}}” has reached over {{$views}} views. To view your post click {{URL::to('posts/'.$post->alias)}}.

You can edit your email notifications in account settings.