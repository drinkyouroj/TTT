<div class="animated fadeIn @if(isset($featured_item) && $featured_item) col-md-8 profile-featured @else col-md-4 @endif post-id-{{$post->id}}">
	<div class="generic-item">
		<header>
			<h3>{{ link_to('posts/'.$post->alias, $post->title) }}</h3> 
			<span class="story-type">{{$post->story_type}}</span>
			<span class="author"><span>by</span> {{link_to('profile/'.$post->user->username, $post->user->username)}}</span>
		</header>
		<section>
			@if($post->image)
			<div class="the-image">
				<a href="{{ URL::to('posts/'.$post->alias) }}" style="background-image: url('{{Config::get('app.url')}}/uploads/final_images/{{$post->image}}');">
				
				</a>
			</div>
			@endif
			<div class="the-tags">
				{{$post->tagline_1}} | 
				{{$post->tagline_2}} | 
				{{$post->tagline_3}}
			</div>
		</section>
	</div>
</div>