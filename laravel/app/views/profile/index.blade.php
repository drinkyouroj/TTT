@extends('layouts.profile')


@section('title')
{{$user->username }}'s Profile

@stop

@section('left_sidebar')
	<div class="the-content">
		
		
		<div class="notifications-listing">
		@if(count($notifications))
			{{--Below file has the foreach routine for both the top section and the full listing --}}
			@include('partials/notifications')
		@else
			No noficiations at thsi time.
		@endif
			<a class="all-notifications" href="{{Config::get('app.url')}}/profile/notifications">
				<span>All Notifications</span>
			</a>
		</div>
		
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
	
	@if(Auth::check())
		{{--
			This is a logged in scenario.  This will display if the user is LOGGED IN.
				Below is escaping the Ember.js handlebars layout by using an include.
			--}}
		
	@else
		{{--
			This is a not logged in scenario.  This will display if the user is not logged in.
			--}}
		
	@endif

	{{--Gotta check to see if this is you or other people.--}}
		
		<div class="row activity-container generic-listing">
			
				@if((Session::get('username') == Request::segment(2)) || (Request::segment(2) == '') )
				{{--This is for the user's actual profile--}}
				<div class="col-md-4">
					<div class="generic-item equal-height add-new">
						<header>
							{{link_to('profile/newpost','Add New Post')}}
						</header>
						<section>
							Add!
						</section>
					</div>
				</div>
				@endif
				
			@if(!empty($activity))
				@foreach($activity as $act)
					@include('partials/activity-item')
				@endforeach
			@endif
		</div>
	
@stop


@section('js')
	@parent
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
@stop