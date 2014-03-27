@extends('layouts.profile')

{{--Gotta check to see if this is you or other people.--}}
@if((Session::get('username') == Request::segment(2)) || (Request::segment(2) == '') )
	{? $me = true; ?}
@else 
	{? $me = false; ?}
@endif


@section('title')
{{$user->username }}'s Profile
@stop

@section('css')
	@parent
	
	@if($me && Session::get('first'))
	<link href="{{Config::get('app.url')}}/css/joyride-2.1.css" rel="stylesheet" media="screen">
	@endif
	
@stop

@section('left_sidebar')
	<div class="the-content">
		
		@if(Auth::check())
		<div class="notifications-listing">
			<h3>Notifications</h3>
			@if(count($notifications))
				{? $break = 10; $all = false; ?}
				{{--Below file has the foreach routine for both the top section and the full listing --}}
				@include('partials/notifications')
			@else
				No notificiations at this time.
			@endif
		</div>
		@endif
		
		{{--
		Below 3 are saved for now.
		
		
		@if($likes)
		<div class="likes recents">
			<h3>Recent likes:</h3>
			@if(count($likes))
				<ul>
				@foreach($likes as $like)
					<li><a href="{{ Config::get('app.url').'/posts/'.$like->post->alias}}"><strong>{{$like->post->title}}</strong> by {{$like->post->user->username}}</a></li>
				@endforeach
				</ul>
			@endif
		</div>
		@endif
		
		@if($follows && count($follows))
		<div class="follows recents">
			<h3>Recent Follows:</h3>
			<ul>
				@foreach($follows as $follow)
					<li><a href="{{ Config::get('app.url').'/profile/'.$follow->users->username}}">{{$follow->users->username}}</a></li>
				@endforeach
			</ul>
		</div>
		@endif
		
		@if($reposts && count($reposts))
		<div class="follows recents">
			<h3>Recent Reposts:</h3>
			<ul>
				@foreach($reposts as $repost)
					<li><a href="{{ Config::get('app.url').'/posts/'.$repost->posts->alias}}">{{$repost->posts->title}}</a></li>
				@endforeach
			</ul>
		</div>
		@endif
		--}}
		{{--Below is for the person visiting the profile--}}
		
		
	</div>
@stop

@section('main')		
		@if(!$me)	
			<div class="row activity-nav">
				<li class="left"><a class="all">Show All</a></li>
				<li><a class="myposts">{{Request::segment(2)}}'s Posts</a></li>
				<li><a class="myfavorites">{{Request::segment(2)}}'s Favorites</a></li>
			</div>
		@endif
		
		
		<div class="row activity-container generic-listing {{ $me ? '': 'myposts' }}">
			@if($me)
			{{--This is for the user's actual profile--}}
			<div class="col-md-4">
				<div class="generic-item add-new">
					<header>
						{{link_to('profile/newpost','')}}
					</header>
				</div>
			</div>
			@endif
			
			{{--
			@if(isset($featured) && $featured )
				{? $featured_item = 1 ?}
				{? $act = $featured ?}
				@include('partials/activity-item')
			@endif
			--}}
			
			{? $featured_item = 0 ?}
			
			@if(!empty($activity))
				@if($featured == false) 
					{? $featured_id = 0;?}
				@else
					{? $featured_id = $featured->post->id ?} 
				@endif
			
				@foreach($activity as $act)
					@if(isset($act->post->id) && $act->post->published && isset($act->user->username))
						@include('partials/activity-item')
					@endif
				@endforeach
			@endif
		</div>

	<ol id="WalkThrough" style="display:none;">
		<li class="walk-write walk-generic" data-class="new-post" data-button="Next">
			<br/>
			You Can Submit Content through this!<br/><br/>
		</li>
		<li class="walk-posts walk-generic" data-class="category-filter" data-button="Next">
			<br/>
			To check out other people's posts, you can click on any of the categories.<br/><br/>
		</li>
		<li class="walk-notifications walk-generic" data-class="notifications-parent" data-button="Next">
			<br/>
			All of your notifications are here!<br/><br/>
		</li>
		<li class="walk-search walk-generic" data-id="search-box" data-button="Next">
			<br/>
			You can run searches here if you're looking for something specific<br/><br/>
		</li>
		<li class="walk-follow walk-generic" data-class="follow-container" data-button="Done">
			<br/>
			You can check on your friends/foe here!<br/><br/>
		</li>
	</ol>

@stop


@section('js')
	@parent
	
	<script>
		window.cur_user = '{{$user->username}}';
	</script>

	{{-- Include all the JS required for the situation--}}
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/handlebars-v1.3.0.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
	
	@if(!$me)
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/myposts.js"></script>
	@endif
	
	
	@if(Auth::check() && $me && Session::get('first'))
		{{--If this is the user's first time logging into the system.--}}
			<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery.joyride-2.1.js"></script>
			<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile/first.js"></script>
			
		{{--This is where we disable the session "first".--}}
		{? Session::put('first', false); ?}
	@endif
	
	
	@include('partials/generic-handlebar-item')
	
@stop