@extends('layouts.profile')



@section('left_sidebar')
	<div class="the-content">
		{{$user->username}}
		Will actually add a bio section later.
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
			<div class="col-md-4 add-new">
				<header>
					Add!
				</header>
				<section>
					Add!
				</section>
			</div>
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
						{{$act->post->tagline1}} |
						{{$act->post->tagline2}} |
						{{$act->post->tagline3}}
						</span>
					</div>
				</section>
			</div>
			@endforeach
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
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery-1.9.1.js"></script>
@stop