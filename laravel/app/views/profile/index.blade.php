@extends('layouts.profile')



@section('left_sidebar')
	<div class="the-content">
		{{$user->username}}
		<br/>
		
		<div class="likes">
			<h3>Your recent likes:</h3>
			@if(count($likes))
				<ul>
				@foreach($likes as $like)
					<li><strong>{{$like->post->title}}</strong> by {{$like->post->user->username}}</li>
				@endforeach
				</ul>
			@else
				<div class="no-likes">
					<p>
						No likes! Yikes! <br/>
						See if you can find a post you like!
					</p>
				</div>
			@endif
		</div>
		
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
				<div class="col-md-4">
					<div class="generic-item activity equal-height {{$act->post_type}}">
						<header>
							@if($act->post_type == 'post')
								<h3 class="post">
									{{ link_to('posts/'.$act->post->alias, $act->post->title) }}
								</h3>
								<span class="author">by {{ link_to('profile/'.$act->post->user->username, $act->post->user->username) }}</span> 
							@elseif($act->post_type == 'repost')
								<h3 class="repost">
									{{ link_to('posts/'.$act->post->alias, $act->post->title) }}
								</h3>
								<span class="author">by {{ link_to('profile/'.$act->post->user->username, $act->post->user->username) }}</span>
								<span class="repost">reposted by {{ link_to('profile/'.$act->user->username, $act->user->username) }}</span>
							@else
								<h3 class="favorite">{{ link_to('posts/'.$act->post->alias, $act->post->title) }}</h3>
								<span class="author">by {{ link_to('profile/'.$act->post->user->username, $act->post->user->username) }}</span>
							@endif
						</header>
						<section>
							<div class="the-image">
								<img src="{{$act->post->image}}">
							</div>
							<div class="the-taglines">
								<span class="taglines">
								{{$act->post->tagline_1}} |
								{{$act->post->tagline_2}} |
								{{$act->post->tagline_3}}
								</span>
							</div>
						</section>
					</div>
				</div>
				@endforeach
			@else
				@if(!empty($posts))
					@foreach($posts as $post)
					<div class="col-md-4">
						<div class="generic-item equal-height activity">
							<header>
								<h3 class="post">
									{{ link_to('posts/'.$post->alias, $post->title) }}
								</h3>
								<span class="author">by {{ link_to('profile/'.$post->user->username, $post->user->username) }}</span>
							</header>
							<section>
								<div class="the-image">
									<img src="{{$post->image}}">
								</div>
								<div class="the-taglines">
									<span class="taglines">
									{{$post->tagline_1}} |
									{{$post->tagline_2}} |
									{{$post->tagline_3}}
									</span>
								</div>
							</section>
						</div>
					</div>
					@endforeach
				@endif			
			@endif
		</div>
	
		
	
	
	
@stop


@section('js')
	@parent
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
@stop