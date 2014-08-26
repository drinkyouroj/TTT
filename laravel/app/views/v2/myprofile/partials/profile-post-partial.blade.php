{{-- This is the partial template for a profile post --}}
<div class="post-container">
	
	<div class="post-image-overlay">
		<img class="post-author-avatar" src="">
		story by <a href=""> {{ $item->post->user->username }} </a>

		@if ( $item->feed_type == 'repost' )
			<div class="post-repost-container">
				<img class="post-repost-image" src="">
				<div> x 
					<span class="post-repost-count">{{ count( $item->users ) }} </span>
				</div>
				<ul class="post-repost-user-dropdown list-unstyled">
					@foreach ( $item->users as $user )
						<li> 
							<a href=""> {{ $user }} </a> 
						</li>
					@endforeach
				</ul>
			</div>
		@endif

	</div>

	<img class="post-image" src="">
	<p class="post-title"> 
		{{ $item->post->title }}
	</p>
	<ul class="post-taglines list-inline">
		<li> {{ $item->post->tagline_1 }} </li>
		<li> {{ $item->post->tagline_2 }} </li>
		<li> {{ $item->post->tagline_3 }} </li>
	</ul>
	
</div>