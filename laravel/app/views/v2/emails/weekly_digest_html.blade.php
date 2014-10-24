@extends('v2.emails.email_layout')

@section('content')
	<h1 style="margin-top:50px; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:21px;">Two Thousand Times - Weekly Digest</h1>
	<div>
		<img src="{{Config::get('app.imageurl')}}/{{ $featured_post['image'] }}">
		<h2>{{$featured_post['title']}}</h2>
		<p><?php echo substr( strip_tags($featured_post['body']), 0, 60) ?>...</p>
		<img src="{{Config::get('app.imageurl')}}/{{ $featured_post['user']['image'] }}">
		<span>{{ $featured_post['user']['username'] }}</span>
		<a href="{{Config::get('app.url')}}/posts/{{ $featured_post['alias'] }}">Read Now</a>
	</div>
	<hr>
	<div>
		<h2>{{$post_2['title']}}</h2>
		<p><?php echo substr( strip_tags($post_2['body']), 0, 60) ?>...</p>
		<img src="{{Config::get('app.imageurl')}}/{{ $post_2['user']['image'] }}">
		<span>{{ $post_2['user']['username'] }}</span>
		<a href="{{Config::get('app.url')}}/posts/{{ $post_2['alias'] }}">Read Now</a>
	</div>
	<hr>
	<div>
		<h2>{{$post_3['title']}}</h2>
		<p><?php echo substr( strip_tags($post_3['body']), 0, 60) ?>...</p>
		<img src="{{Config::get('app.imageurl')}}/{{ $post_3['user']['image'] }}">
		<span>{{ $post_3['user']['username'] }}</span>
		<a href="{{Config::get('app.url')}}/posts/{{ $post_3['alias'] }}">Read Now</a>
	</div>
	<hr>
	<div>
		<h2>{{$post_4['title']}}</h2>
		<p><?php echo substr( strip_tags($post_4['body']), 0, 60) ?>...</p>
		<img src="{{Config::get('app.imageurl')}}/{{ $post_4['user']['image'] }}">
		<span>{{ $post_4['user']['username'] }}</span>
		<a href="{{Config::get('app.url')}}/posts/{{ $post_4['alias'] }}">Read Now</a>
	</div>
	<hr>
	<div>
		<h2>{{$post_5['title']}}</h2>
		<p><?php echo substr( strip_tags($post_5['body']), 0, 60) ?>...</p>
		<img src="{{Config::get('app.imageurl')}}/{{ $post_5['user']['image'] }}">
		<span>{{ $post_5['user']['username'] }}</span>
		<a href="{{Config::get('app.url')}}/posts/{{ $post_5['alias'] }}">Read Now</a>
	</div>
	<hr>
@stop