@extends('layouts.profile')



@section('left_sidebar')
	<div class="the-content">
		{{$user->username}}
		<br/>
		We will actually add a bio section later.
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
	@if(Request::segment(2) != Session::get('username'))
		<div class="row activity">
			
			@if((Session::get('username') == Request::segment(2)) || (Request::segment(2) == '') )
			{{--This is for the user's actual profile--}}
			<div class="col-md-4 add-new">
				<header>
					{{link_to('profile/newpost','Add New Post')}}
				</header>
				<section>
					Add!
				</section>
			</div>
			@endif
			@if(!empty($activity))
				@foreach($activity as $act)
				<div class="col-md-4 activity {{$act->post_type}}">
					<header>
						@if($act->post_type == 'post')
							<h3 class="post">{{$act->post->title}}</h3>
							<span class="author">by {{$act->post->user->username}}</span> 
						@elseif($act->post_type == 'repost')
							<h3 class="repost">{{$act->post->title}}</h3>
							<span class="author">by {{$act->post->user->username}}</span>
							<span class="repost">reposted by {{$act->user->username}}</span>
						@else
							<h3 class="favorite">{{$act->post->title}}</h3>
							<span class="author">by {{$act->post->user->username}}</span>
						@endif
					</header>
					<section>
						<div class="the-image">
							<img src="">
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
				@endforeach
			@endif
		</div>
	@else
		{{--Other Folks--}}
		<div class="row posts">
		@foreach($posts as $post)
			<div class="col-md-4 post">
				{{$post->title}}
				{{$post->user->username}}
			</div>
		@endforeach
		</div>
	@endif
	
	
@stop


@section('js')
	@parent
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/profile.js"></script>
@stop