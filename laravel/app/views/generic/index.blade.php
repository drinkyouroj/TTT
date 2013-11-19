@extends('layouts.master')

@section('filters')
	@include('partials/generic-filter')
@stop

@section('content')
	
	@if(Auth::check())
			{{--Add anything that's required by a logged in user.--}}
	
	@endif
	
	<div class="col-md-10 col-md-offset-1">
		<div class="row generic-listing">
			@if(!is_string($posts))
				@foreach($posts as $post)
						<div class="col-md-4">
							<h3>{{ link_to('posts/'.$post->alias, $post->title) }}</h3>
							<h4>by {{$post->user->username}}</h4>
							<div class="the-content">
								{{ substr($post->body, 0, 50) }}...
								{{ link_to('posts/'.$post->alias, 'read on.') }}
							</div>
						</div>
				@endforeach
			@else
				<div class="col-md-12">
					{{$posts}}
				</div>
			@endif
		</div>
	</div>

@stop
