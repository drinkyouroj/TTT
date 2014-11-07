@extends('v2.emails.email_layout')

@section('content')
	<h1 style="margin-top:50px; margin-bottom:50px; text-align:center; color:#000000; font-family:Helvetica Neue,Helvetica,Arial,sans-serif; font-weight:bold; font-size:21px;">Recommended by Sondry</h1>
	<div>
		<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $featured_post['alias'] }}">
			<img src="{{Config::get('app.imageurl')}}/{{ $featured_post['image'] }}" alt="Cover Photo" style="width: 100%;height: auto;background-color: lightgrey;">
		</a>
		<h2> <a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $featured_post['alias'] }}" style="margin-top:50px;color:#000000;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:20px;text-decoration:none;"><?php echo strtoupper($featured_post['title']); ?></a> </h2>
		<p> <a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $featured_post['alias'] }}" style="margin-top:50px;color:#000000;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:14px;text-decoration:none;"><?php echo substr( strip_tags($featured_post['body']), 0, 100) ?>...</a>  </p>
		<div>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/user/{{ $featured_post['user']['username'] }}" style="text-decoration: none;">
				<img src="{{Config::get('app.imageurl')}}/{{ $featured_post['user']['image'] }}" alt="avatar" height="30px" width="30px" style="height: 30px;width: 30px;background-color: black;vertical-align: middle;border-radius: 50%;margin-left: 5px;margin-right: 10px;">
				<span style="color: #000000;font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-right: 5px;">{{ $featured_post['user']['username'] }}</span>
			</a>
			<span>-</span>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $featured_post['alias'] }}" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-left: 5px;font-weight: bold;text-decoration: none;color: #32b1c6;">Read Now</a>
		</div>
	</div>
	<hr style="margin-bottom: 30px; margin-top: 30px;">
	<div>
		<h2> <a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $post_2['alias'] }}" style="margin-top:50px;color:#000000;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:20px;text-decoration:none;"> <?php echo strtoupper($post_2['title']); ?> </a> </h2>
		<div>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/user/{{ $post_2['user']['username'] }}" style="text-decoration: none;">
				@if ( isset($post_2['user']['image']) && ($post_2['user']['image'] != 0) )
					<img src="{{Config::get('app.imageurl')}}/{{ $post_2['user']['image'] }}" alt="avatar" height="30px" width="30px" style="height: 30px;width: 30px;background-color: black;vertical-align: middle;border-radius: 50%;margin-left: 5px;margin-right: 10px;">
				@else
					<img src="{{Config::get('app.url')}}/images/profile/avatar-default.png" alt="avatar" height="30px" width="30px" style="height: 30px;width: 30px;background-color: black;vertical-align: middle;border-radius: 50%;margin-left: 5px;margin-right: 10px;">
				@endif
				<span style="color: #000000;font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-right: 5px;">{{ $post_2['user']['username'] }}</span>
			</a>
			<span>-</span>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $post_2['alias'] }}" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-left: 5px;font-weight: bold;text-decoration: none;color: #32b1c6;">Read Now</a>
		</div>
	</div>
	<hr style="margin-bottom: 30px; margin-top: 30px;">
	<div>
		<h2> <a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $post_3['alias'] }}" style="margin-top:50px;color:#000000;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:20px;text-decoration:none;"> <?php echo strtoupper($post_3['title']); ?> </a> </h2>
		<div>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/user/{{ $post_3['user']['username'] }}" style="text-decoration: none;">
				@if ( isset($post_3['user']['image']) && ($post_3['user']['image'] != 0) )
					<img src="{{Config::get('app.imageurl')}}/{{ $post_3['user']['image'] }}" alt="avatar" height="30px" width="30px" style="height: 30px;width: 30px;background-color: black;vertical-align: middle;border-radius: 50%;margin-left: 5px;margin-right: 10px;">
				@else
					<img src="{{Config::get('app.url')}}/images/profile/avatar-default.png" alt="avatar" height="30px" width="30px" style="height: 30px;width: 30px;background-color: black;vertical-align: middle;border-radius: 50%;margin-left: 5px;margin-right: 10px;">
				@endif
				<span style="color: #000000;font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-right: 5px;">{{ $post_3['user']['username'] }}</span>
			</a>
			<span>-</span>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $post_3['alias'] }}" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-left: 5px;font-weight: bold;text-decoration: none;color: #32b1c6;">Read Now</a>
		</div>
	</div>
	<hr style="margin-bottom: 30px; margin-top: 30px;">
	<div>
		<h2> <a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $post_4['alias'] }}" style="margin-top:50px;color:#000000;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:20px;text-decoration:none;"> <?php echo strtoupper($post_4['title']); ?> </a> </h2>
		<div>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/user/{{ $post_4['user']['username'] }}" style="text-decoration: none;">
				@if ( isset($post_4['user']['image']) && ($post_4['user']['image'] != 0) )
					<img src="{{Config::get('app.imageurl')}}/{{ $post_4['user']['image'] }}" alt="avatar" height="30px" width="30px" style="height: 30px;width: 30px;background-color: black;vertical-align: middle;border-radius: 50%;margin-left: 5px;margin-right: 10px;">
				@else
					<img src="{{Config::get('app.url')}}/images/profile/avatar-default.png" alt="avatar" height="30px" width="30px" style="height: 30px;width: 30px;background-color: black;vertical-align: middle;border-radius: 50%;margin-left: 5px;margin-right: 10px;">
				@endif
				<span style="color: #000000;font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-right: 5px;">{{ $post_4['user']['username'] }}</span>
			</a>
			<span>-</span>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $post_4['alias'] }}" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-left: 5px;font-weight: bold;text-decoration: none;color: #32b1c6;">Read Now</a>
		</div>
	</div>
	<hr style="margin-bottom: 30px; margin-top: 30px;">
	<div style="margin-bottom: 50px;">
		<h2> <a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $post_5['alias'] }}" style="margin-top:50px;color:#000000;font-family:Helvetica Neue,Helvetica,Arial,sans-serif;font-size:20px;text-decoration:none;"> <?php echo strtoupper($post_5['title']); ?> </a> </h2>
		<div>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/user/{{ $post_5['user']['username'] }}" style="text-decoration: none;">
				@if ( isset($post_5['user']['image']) && ($post_5['user']['image'] != 0) )
					<img src="{{Config::get('app.imageurl')}}/{{ $post_5['user']['image'] }}" alt="avatar" height="30px" width="30px" style="height: 30px;width: 30px;background-color: black;vertical-align: middle;border-radius: 50%;margin-left: 5px;margin-right: 10px;">
				@else
					<img src="{{Config::get('app.url')}}/images/profile/avatar-default.png" alt="avatar" height="30px" width="30px" style="height: 30px;width: 30px;background-color: black;vertical-align: middle;border-radius: 50%;margin-left: 5px;margin-right: 10px;">
				@endif
				<span style="color: #000000;font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-right: 5px;">{{ $post_5['user']['username'] }}</span>
			</a>
			<span>-</span>
			<a href="{{Config::get('app.url')}}/email/{{$digest_id}}/post/{{ $post_5['alias'] }}" style="font-family: Helvetica Neue,Helvetica,Arial,sans-serif;font-size: 14px;margin-left: 5px;font-weight: bold;text-decoration: none;color: #32b1c6;">Read Now</a>
		</div>
	</div>
@stop