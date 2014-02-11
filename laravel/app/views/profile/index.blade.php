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
				<div class="col-md-4 post-id-{{$act->post->id}}">
					<div class="generic-item activity equal-height {{$act->post_type}} {{$act->type}}">
						<header>
							@if($act->post_type == 'post' || $act->type == 'post' )
								<h3 class="post">
									{{ link_to('posts/'.$act->post->alias, $act->post->title) }}
								</h3>
								<span class="author">by {{ link_to('profile/'.$act->post->user->username, $act->post->user->username) }}</span> 
							@elseif($act->post_type == 'repost' || $act->type == 'repost')
								<h3 class="repost">
									{{ link_to('posts/'.$act->post->alias, $act->post->title) }}
								</h3>
								<span class="author">by {{ link_to('profile/'.$act->post->user->username, $act->post->user->username) }}</span>
								<span class="repost">reposted by {{ link_to('profile/'.$act->user->username, $act->user->username) }}</span>
							@else
								<h3 class="favorite ">{{ link_to('posts/'.$act->post->alias, $act->post->title) }}</h3>
								<span class="author">by {{ link_to('profile/'.$act->post->user->username, $act->post->user->username) }}</span>
							@endif
							
							{{--If you are the owner of this post show menu options--}}
							@if(Auth::user()->id == $act->post->user->id && 
								strtotime(date('Y-m-d H:i:s', strtotime('-72 hours'))) <= strtotime($act->post->created_at) &&
								$act->type == 'post'
								)
								<ul class="user-menu">
									<li class="options">
										<a href="#post-edit">
											Options
										</a>
										<ul class="menu-listing">
											<li class="edit">
												<a href="{{Config::get('app.url')}}/profile/editpost/{{$act->post->id}}">Edit</a>
											</li>
											{{--Check out the fact that below has a hidden function --}}
											<li class="feature @if($act->post->id != Session::get('featured')) @else hidden @endif">
												<a href="#feature" data-id="{{$act->post->id}}">Feature</a>
											</li>
											
											<li class="delete">
												<a href="#delete" data-id="{{$act->post->id}}">Delete</a>
											</li>
										</ul>
									</li>
								</ul>
							@endif
						</header>
						<section>
							<div class="the-image">
								<a href="{{URL::to('posts/'.$act->post->alias)}}">
									<img src="{{Config::get('app.url')}}/uploads/final_images/{{$act->post->image}}">
								</a>
							</div>
							<div class="the-tags">
								{{$act->post->tagline_1}} |
								{{$act->post->tagline_2}} |
								{{$act->post->tagline_3}}
							</div>
						</section>
					</div>
				</div>
				@endforeach
			@endif
		</div>
	
@stop


@section('js')
	@parent
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
@stop