@extends('layouts.master')



@section('content')
	
	@if(Auth::check())
		{{--
			This is a logged in scenario.  This will display if the user is LOGGED IN.
				Below is escaping the Ember.js handlebars layout by using an include.
			--}}
		
		@include('handlebars/main')
		
		@include('handlebars/posts')
		
		@include('handlebars/profile')
		
		@include('handlebars/messages')
		
	@else
		{{--
			This is a not logged in scenario.  This will display if the user is not logged in.
			--}}
		@foreach($posts as $post)
			<p>Test {{$post->id}}</p>
		@endforeach
	@endif	

@stop


@section('js')
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery-1.9.1.js"></script>
		
	@if(Auth::check())
		<!--Ember/Bootstrap-->
		<script type="text/javascript">window.user_id = {{Auth::user()->id}};</script><!--//user id is passed as a global variable-->
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/handlebars-1.0.0.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/ember-1.0.0.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/ember-data-1.0.0.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/ember-easyForm-1.0.0.beta1.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/ember_bs/bs-core.min.js"></script>
		
		<!--App Router-->
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/app.js"></script>
		
		<!--Route extentions aka the real controllers-->
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/route.js"></script>

		<!--Models-->
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/models/posts_model.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/models/profiles_model.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/models/messages_model.js"></script>
		
		<!--Controllers or really View-Models-->
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/controllers/posts.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/controllers/profiles.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/controllers/messages.js"></script>
				
		<!--Views-->
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/views/posts.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/views/profiles.js"></script>
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/app/views/messages.js"></script>
		
	@else
		
	@endif
@stop