@extends('layouts.master')
	{? $user= Auth::user() ?}

@section('filters')
	@include('partials/generic-filter')
@stop

@section('js')
	<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/post.js"></script>
	
	@if( is_object($user))
		
		@if($user->hasRole('Admin'))
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/admin.js"></script>
		@endif
		
		@if($user->hasRole('Moderator'))
		<script type="text/javascript" src="{{Config::get('app.url')}}/js/views/mod.js"></script>
		@endif
		
	@endif
	
@stop

@section('title')
	{{$post->title}}
@stop

@section('content')	
<div class="col-md-10 col-md-offset-1 single-post">
	<hgroup>
		<h2>{{$post->title}}</h2>
		<h4>
			by {{link_to('profile/'.$post->user->username, $post->user->username)}}			
		</h4>
		
		@if(Auth::check())
			{? $my_id = Auth::user()->id ?}
		@else
			{? $my_id = 0 ?}
		@endif
		
		@if(!$is_following && $post->user->id != $my_id)
			<div class="follow-container">
				<a class="follow action" 
					href="{{Config::get('app.url')}}/follow/{{$post->user->id}}"
					data-user="{{$post->user->id}}">
					Follow {{$post->user->username}}
				</a>
			</div>
			@elseif($is_following && $is_follower)
				{{--Mutual--}}
			<div class="message-container">
				<a class="message action" 
					href="{{Config::get('app.url')}}/profile/newmessage/{{$post->user->id}}" 
					data-user="{{$post->user->id}}">
					Message {{$post->user->username}}
				</a>
			</div>
		@endif
	</hgroup>
	
	@if( is_object($user))
		<div class="admin">
			@if($user->hasRole('Admin'))
				{{--Featured--}}
				@if($post->featured)
					<a class="feature action" data-id="{{$post->id}}">
						Un-Feature this Article
					</a>
				@else
					<div class="feature-options">
						Width:
						<select name="width" id="width">
							<option value="1" selected="selected">25%</option>
							<option value="2">50%</option>
							<option value="3">75%</option>
						</select>
					
						With or Without Text:
						<select name="height" id="height">
							<option value="1" selected="selected">Without</option>
							<option value="2">With</option>
						</select>
					
						First or Last:
						<select name="order" id="order">
							<option value="first" selected="selected">First</option>
							<option value="last">Last</option>
						</select>
					
					</div>
					<a class="feature unfeatured action" data-id="{{$post->id}}">
						Set Article as a Featured
					</a>
				@endif
				<a class="hard-del" data-id="{{$post->id}}">
					Hard Delete Post
				</a>
			@endif
		
			@if($user->hasRole('Moderator'))
				@if($post->published)
					<a class="mod-delete" data-id="{{$post->id}}">
						Moderator Delete
					</a>
				@else
					<a class="mod-delete" data-id="{{$post->id}}">
						Moderator UnDelete
					</a>
				@endif
			
			
				{{--It'd be really stupid if you banned yourself.--}}
				@if($post->user->username != $user->username && (!$post->user->hasRole('Admin') || !$post->user->hasRole('Moderator') )) 
					@if(!$post->user->banned)
						<a class="mod-ban" data-id="{{$post->user->id}}">
							Ban {{$post->user->username}}
						</a>
					@else
						<a class="mod-ban" data-id="{{$post->user->id}}">
							UnBan {{$post->user->username}}
						</a>
					@endif
				@endif
			
			@endif
		</div>
	@endif
	
	<div class="the-share">
		@if(Auth::check() && $post->user->id != Auth::user()->id)
		
		@endif
			<div class="system-share">
				<span class="fav-container share-action {{ $favorited ? 'done': '' }}">
					<a class="fav"
						data-post="{{$post->id}}">
						Favorite
						{{$post->favorites->count() ? '<span class="brackets">(<span class="numbers">'.$post->favorites->count().'</span>)</span>' : ''}}
						<div class="description fav-description">Save this to your profile.</div>
					</a>
				</span>
				
				<span class="repost-container share-action {{ $reposted ? 'done': '' }}">
					<a class="repost"
						href="{{Config::get('app.url')}}/repost/{{$post->id}}" 
						data-post="{{$post->id}}">
						Repost
						{{$post->reposts->count() ? '<span class="brackets">(<span class="numbers">'.$post->reposts->count().'</span>)</span>' : ''}}
						<div class="description repost-description">Share this with your followers.</div>
					</a>
				</span>
				
				<span class="like-container share-action {{ $liked ? 'done': '' }}">
					<a class="like"
						href="{{Config::get('app.url')}}/like/{{$post->id}}" 
						data-post="{{$post->id}}">
						Like
						{{$post->likes->count() ? '<span class="brackets">(<span class="numbers">'.$post->likes->count().'</span>)</span>' : ''}}
						<div class="description like-description">Give this more visibility to the community.</div>
					</a>
				</span> 
			</div>
		
		<div class="external-share">
			
		</div>
	</div>
	
	@if($post->image)
	<div class="row">
		<div class="col-md-10 col-md-offset-1 the-image" style="background-image: url('{{Config::get('app.url')}}/uploads/final_images/{{$post->image}}');">
			
		</div>
		<div class="col-md-10 col-md-offset-1 the-tags @if(strlen($post->tagline_1.$post->tagline_2.$post->tagline_3) >= 35) long @endif ">
				{{$post->tagline_1}} | 
				{{$post->tagline_2}} | 
				{{$post->tagline_3}}
		</div>
	</div>
	@endif
	
	<div class="the-content">
		@if($post->published)
			{? $total = count($bodyarray)?}
			@foreach($bodyarray as $c => $body)
				<div class="row">
					<div class="col-md-10 col-md-offset-1 page-content">
						<div class="row-content" id="{? echo $c ? '':'one' ?}">
							{{{$body}}}
						</div>
					</div>
					<div class="col-md-10 col-md-offset-1 row-divider">
						<span class="page-count">{? echo $c+1 ?}/{{$total}}</span>
						<div class="clearfix"></div>
					</div>
				</div>
			@endforeach
		@else
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<h3 class="unpublished">This one's been unpublished.</h3>
				</div>
			</div>
		@endif
	</div>
	
	
	<div class="the-comment-container row">
		@if(count($post->comments))
			<div class="comments col-md-10 col-md-offset-1">
				@if(Auth::check())
					@include('generic.commentform', array('post' => $post) )
				@endif
				
				<ul class="comments-listing">
					{{--This below is a helper function--}}
					{? ThreadedComments::echo_comments($post->nochildcomments);?}
					{{--If you want to edit the formatting, go to: 'helpers/ThreadedComments.php'--}}
				</ul>
			</div>
		@else
			<div class="comments col-md-10 col-md-offset-1 no-comments">
				@if(Auth::check())
					@include('generic.commentform', array('post' => $post) )
				@endif
				
				<p>No Comments have been made yet...</p>
			</div>
		@endif
	</div>
	
</div>
@stop