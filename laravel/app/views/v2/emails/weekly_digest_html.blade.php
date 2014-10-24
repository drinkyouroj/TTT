@extends('v2.emails.email_layout')

@section('content')
	<h1 style="margin-top:50px; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:21px;">Two Thousand Times - Weekly Digest</h1>
	<div>
		<a href="{{Config::get('app.url')}}/posts/{{ $featured_post['alias'] }}">
			<img src="{{Config::get('app.imageurl')}}/{{ $featured_post['image'] }}">
		</a>
		<h2> <a href="{{Config::get('app.url')}}/posts/{{ $featured_post['alias'] }}">{{$featured_post['title']}}</a> </h2>
		<p> <a href="{{Config::get('app.url')}}/posts/{{ $featured_post['alias'] }}"><?php echo substr( strip_tags($featured_post['body']), 0, 60) ?>...</a>  </p>
		<div>
			<a href="{{Config::get('app.url')}}/profile/{{ $featured_post['user']['username'] }}">
				<img src="{{Config::get('app.imageurl')}}/{{ $featured_post['user']['image'] }}">
				<span>{{ $featured_post['user']['username'] }}</span>
			</a>
			<a href="{{Config::get('app.url')}}/posts/{{ $featured_post['alias'] }}">Read Now</a>
		</div>
	</div>
	<hr>
	<div>
		<h2> <a href="{{Config::get('app.url')}}/posts/{{ $post_2['alias'] }}"> {{$post_2['title']}} </a> </h2>
		<div>
			<a href="{{Config::get('app.url')}}/profile/{{ $post_2['user']['username'] }}">
				<img src="{{Config::get('app.imageurl')}}/{{ $post_2['user']['image'] }}">
				<span>{{ $post_2['user']['username'] }}</span>
			</a>
			<a href="{{Config::get('app.url')}}/posts/{{ $post_2['alias'] }}">Read Now</a>
		</div>
	</div>
	<hr>
	<div>
		<h2> <a href="{{Config::get('app.url')}}/posts/{{ $post_3['alias'] }}"> {{$post_3['title']}} </a> </h2>
		<div>
			<a href="{{Config::get('app.url')}}/profile/{{ $post_3['user']['username'] }}">
				<img src="{{Config::get('app.imageurl')}}/{{ $post_3['user']['image'] }}">
				<span>{{ $post_3['user']['username'] }}</span>
			</a>
			<a href="{{Config::get('app.url')}}/posts/{{ $post_3['alias'] }}">Read Now</a>
		</div>
	</div>
	<hr>
	<div>
		<h2> <a href="{{Config::get('app.url')}}/posts/{{ $post_4['alias'] }}"> {{$post_4['title']}} </a> </h2>
		<div>
			<a href="{{Config::get('app.url')}}/profile/{{ $post_4['user']['username'] }}">
				<img src="{{Config::get('app.imageurl')}}/{{ $post_4['user']['image'] }}">
				<span>{{ $post_4['user']['username'] }}</span>
			</a>
			<a href="{{Config::get('app.url')}}/posts/{{ $post_4['alias'] }}">Read Now</a>
		</div>
	</div>
	<hr>
	<div>
		<h2> <a href="{{Config::get('app.url')}}/posts/{{ $post_5['alias'] }}"> {{$post_5['title']}} </a> </h2>
		<div>
			<a href="{{Config::get('app.url')}}/profile/{{ $post_5['user']['username'] }}">
				<img src="{{Config::get('app.imageurl')}}/{{ $post_5['user']['image'] }}">
				<span>{{ $post_5['user']['username'] }}</span>
			</a>
			<a href="{{Config::get('app.url')}}/posts/{{ $post_5['alias'] }}">Read Now</a>
		</div>
	</div>
	<hr>
@stop