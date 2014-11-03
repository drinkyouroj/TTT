{{-- This is the partial template for a post and/or feed repost listing --}}
{{-- PLEASE keep updated with the equivalent handlebars template! --}}
@if(isset($post->user))
<div class="post-container">
	
	<div class="post-image-overlay">
		<a href="{{ URL::to('profile/'.$post->user->username ) }}">
			<?php $user_image = $post->user->image ? Config::get('app.imageurl').'/'.$post->user->image : Config::get('app.url').'/images/profile/avatar-default.png' ;?>
			<span class="post-author-avatar" style="background-image:url('{{$user_image}}');">
		</a>
		<span class="author-text">
		{{$post->story_type}} by <a href="{{ URL::to('profile/'.$post->user->username ) }}"> {{ $post->user->username }} </a>
		</span>

		@if ( isset( $feed_type ) && $feed_type == 'repost' )
			<div class="post-repost-container">
				<img class="post-repost-image" src="{{ URL::to('images/global/repost-single.png') }}" width="30px" height="30px">
				<div class="post-repost-count-container"> x 
					<span class="post-repost-count">{{ count( $users ) }} </span>
				</div>
				<ul class="post-repost-user-dropdown list-unstyled fade in out">
					@foreach ( $users as $user )
						<li>
							<a href="{{ URL::to('profile/'.$user) }}"> {{ $user }} </a>
						</li>
					@endforeach
						<li class="reposted-label">reposted this post.</li>
				</ul>
			</div>
		@endif

	</div>

	<a class="image-link" href="{{ URL::to('posts/'.$post->alias) }}">
		<div class="top-fade"> </div>
		<div class="post-image" style="background-image:url('{{ Config::get('app.imageurl').'/'.$post->image }}')"> </div>
		@if ( $post->nsfw )
			<div class="nsfw"></div>
		@endif
	</a>

	<p class="post-title"> 
		<a href="{{ URL::to('posts/'.$post->alias) }}">
			{{ $post->title }}
		</a>
	</p>
	<ul class="post-taglines list-inline">
		<li> {{ $post->tagline_1 }} </li>
		<li> {{ $post->tagline_2 }} </li>
		<li> {{ $post->tagline_3 }} </li>
	</ul>
	
</div>
@endif