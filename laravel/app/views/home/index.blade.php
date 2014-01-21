@extends('layouts/master')

@section('filters')
	@include('partials/generic-filter')
@stop

@section('content')
	
	{{--We'll implement the layout selector in the backend and we should use partials at that point to separate the layouts.--}}
	
	<div class="col-md-10 col-md-offset-1">
		<div class="row top-featured">
			
			{{--This is temporary to get things working.--}}
			
			<div class="col-md-4 left">
			@foreach($featured as $k=>$post)
				<div class="left-post">
					<h3>{{ link_to('posts/'.$post->alias, $post->title) }}</h3>
					<h4>by {{link_to('profile/'.$post->user->username, $post->user->username)}}</h4>
					<div class="the-content">
						{{ substr($post->body, 0, 50) }}...
						{{ link_to('posts/'.$post->alias, 'read on.') }}
					</div>
				</div>
				{? if($k == 3 ) {break;}?}
			@endforeach
			</div>
			
			<div class="col-md-8 right">
			@foreach($featured as $k=>$post)
				@if($k >=3)
				<div class="right-post">
					<h3>{{ link_to('posts/'.$post->alias, $post->title) }}</h3>
					<h4>by {{link_to('profile/'.$post->user->username, $post->user->username)}}</h4>
					<div class="the-content">
						{{ substr($post->body, 0, 50) }}...
						{{ link_to('posts/'.$post->alias, 'read on.') }}
					</div>
				</div>
				@endif
			@endforeach
			</div>
		</div>
		
		@if(count($past_featured))
		<div class="row bottom-featured">
			<div class="past_featured_box">
				@foreach($past_featured as $k => $post)
					<div class="col-md-2 {{$k ? '': 'col-md-offset1'}}">{{--This will have offset of 1 if $k = 0 --}}
						
						<div class="the-image">
							{{ link_to('posts/'.$post->alias, 'read on.') }}
						</div>
						<h3>{{ link_to('posts/'.$post->alias, $post->title) }}</h3>
						<h4>by {{$post->user->username}}</h4>
					</div>
				@endforeach
			</div>
		</div>
		@endif
	</div>
	
@stop


@section('js')
	{{-- Include all the JS required for the situation--}}
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/libs/jquery-1.9.1.js"></script>
		
	@if(Auth::check())
		<!--//user id is passed as a global variable-->
		<script type="text/javascript">window.user_id = {{Auth::user()->id}};</script>
	@else
		
	@endif
@stop


@section('css')
	<link href="{{Config::get('app.url')}}/css/views/style.css" rel="stylesheet" media="screen">
@stop