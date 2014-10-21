{{$post->user->username}},

Your post, “{{$post->title}}” has reached over {{$post->views}} views. To view your post click <a href="{{URL::to('posts/'.$post->alias)}}">here</a>.

You can edit your email notifications in account settings.