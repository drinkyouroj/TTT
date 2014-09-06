{{-- This is the partial template for a post and/or feed repost listing --}}
{{-- PLEASE keep updated with the equivalent handlebars template! --}}

<div class="post-container">
	
	<div class="post-image-overlay">
		<a href="{{ URL::to('profile/'.$post->user->username ) }}">
			<img class="post-author-avatar" src="">
		</a>
		story by <a href="{{ URL::to('profile/'.$post->user->username ) }}"> {{ $post->user->username }} </a>

		@if ( isset( $feed_type ) && $feed_type == 'repost' )
			<div class="post-repost-container">
				<img class="post-repost-image" src="{{ URL::to('img/icons/repost-single.png') }}">
				<div class="post-repost-count-container"> x 
					<span class="post-repost-count">{{ count( $users ) }} </span>
				</div>
				<ul class="post-repost-user-dropdown list-unstyled fade in out">
					@foreach ( $users as $user )
						<li> 
							<a href="{{ URL::to('profile/'.$user) }}"> {{ $user }} </a> 
						</li>
					@endforeach
				</ul>
			</div>
		@endif

	</div>

	<a class="image-link" href="{{ URL::to('posts/'.$post->alias) }}">
		<div class="top-fade"> </div>
			<div class="post-image" style="background-image:url('{{ URL::to('uploads/final_images/'.$post->image) }};')">

			</div>
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