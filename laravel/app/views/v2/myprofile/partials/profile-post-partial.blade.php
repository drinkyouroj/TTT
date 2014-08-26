{{-- This is the partial template for a profile post. --}}
{{-- PLEASE keep updated with the equivalenthandlebars template! --}}

<div class="post-container">
	
	<div class="post-image-overlay">
		<a href="{{ URL::to('profile/'.$item->post->user->username ) }}">
			<img class="post-author-avatar" src="">
		</a>
		story by <a href="{{ URL::to('profile/'.$item->post->user->username ) }}"> {{ $item->post->user->username }} </a>

		@if ( $item->feed_type == 'repost' )
			<div class="post-repost-container">
				<img class="post-repost-image" src="">
				<div> x 
					<span class="post-repost-count">{{ count( $item->users ) }} </span>
				</div>
				<ul class="post-repost-user-dropdown list-unstyled fade in out">
					@foreach ( $item->users as $user )
						<li> 
							<a href="{{ URL::to('profile/'.$user) }}"> {{ $user }} </a> 
						</li>
					@endforeach
				</ul>
			</div>
		@endif

	</div>

	<a href="{{ URL::to('posts/'.$item->post->alias) }}">
		<img class="post-image" src="{{ URL::to('uploads/final_images/'.$item->post->image) }}">
	</a>

	<p class="post-title"> 
		<a href="{{ URL::to('posts/'.$item->post->alias) }}">
			{{ $item->post->title }}
		</a>
	</p>
	<ul class="post-taglines list-inline">
		<li> {{ $item->post->tagline_1 }} </li>
		<li> {{ $item->post->tagline_2 }} </li>
		<li> {{ $item->post->tagline_3 }} </li>
	</ul>
	
</div>