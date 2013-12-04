@extends('layouts.master')

@section('filters')
	@include('partials/generic-filter')
@stop

@section('content')	
<div class="col-md-10 col-md-offset-1 single-post">
	
	<hgroup>
		<h2>{{$post->title}}</h2>
		<h4>
			by {{link_to('profile/'.$post->user->username, $post->user->username)}}
			@if(!$is_following && $post->user->id != Session::get('user_id'))
			<span class="follow-container">
				<a class="follow" 
					href="{{Config::get('app.url')}}/follow/{{$post->user->id}}"
					data-user="{{$post->user->id}}">
					Follow {{$post->user->username}}
				</a>
			</span>
			@elseif($is_following && $is_follower)
				{{--Mutual--}}
			<span class="message-container">
				<a class="message" 
					href="{{Config::get('app.url')}}/profile/newmessage/{{$post->user->id}}" 
					data-user="{{$post->user->id}}">
					Message {{$post->user->username}}
				</a>
			</span>	
			@endif
		</h4>
	</hgroup>
	
	<div class="the-share">
		@if(Auth::check() && $post->user->id != Session::get('user_id'))
			<div class="system-share">
				<span class="fav-container">
					<a class="fav"
						href="{{Config::get('app.url')}}/favorite/{{$post->id}}" 
						data-post="{{$post->id}}">
						Favorite
					</a>
				</span>
				<span> | </span>
				<span class="repost-container">
					<a class="repost"
						href="{{Config::get('app.url')}}/repost/{{$post->id}}" 
						data-post="{{$post->id}}">
						Repost
					</a>
				</span>
				<span> | </span>
				<span class="like-container">
					<a class="like"
						href="{{Config::get('app.url')}}/like/{{$post->id}}" 
						data-post="{{$post->id}}">
						Like
					</a>
				</span> 
			</div>
		@endif
		<div class="external-share">
			//Addthis will go here.
		</div>
	</div>
	
	@if($post->image)
	<div class="row">
		<div class="col-md-10 col-md-offset-1 the-image">
			<img src="{{$post->image}}">
			<div class="clearfix"></div>
		</div>
	</div>
	@endif
	
	<div class="the-content">
		{? $total = count($bodyarray)?}
		@foreach($bodyarray as $c => $body)
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="row-content" id="{? echo $c ? '':'one' ?}">
						{{$body}}
					</div>
				</div>
				<div class="col-md-10 col-md-offset-1 row-divider">
					<span class="page-count">{? echo $c+1 ?}/{{$total}}</span>
				</div>
			</div>
		@endforeach
	</div>
	
</div>
@stop