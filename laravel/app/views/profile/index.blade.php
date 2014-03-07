@extends('layouts.profile')


@section('title')
{{$user->username }}'s Profile
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

	{{--Gotta check to see if this is you or other people.--}}
		@if((Session::get('username') == Request::segment(2)) || (Request::segment(2) == '') )
			{? $me = true; ?}
		@else 
			{? $me = false; ?}
			
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
			
			@if(isset($featured) && $featured )
				{? $featured_item = 1 ?}
				{? $act = $featured ?}
				@include('partials/activity-item')
			@endif
			
			{? $featured_item = 0 ?}
			
			@if(!empty($activity))
				@if($featured == false) 
					{? $featured_id = 0;?}
				@else
					{? $featured_id = $featured->post->id ?} 
				@endif
			
				@foreach($activity as $act)
					@if(isset($act->post->id))
						@if($act->post->id != $featured_id  )
							@include('partials/activity-item')
						@endif
					@endif
				@endforeach
			@endif
		</div>
	
@stop


@section('js')
	@parent
	{{-- Include all the JS required for the situation--}}
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/handlebars-v1.3.0.js"></script>
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
	
	@if(!$me)
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/myposts.js"></script>
	@endif
	
	@include('partials/generic-handlebar-item')
	
@stop