<div class="featured-item generic-item @if($k<2) w2 @endif">
	<h3>{{ link_to('posts/'.$post->alias, $post->title) }}</h3>
	<h4>by {{link_to('profile/'.$post->user->username, $post->user->username)}}</h4>
	@if($post->image)
	<div class="the-image">
		<a href="{{ URL::to('posts/'.$post->alias) }}">
			<img src="{{Config::get('app.url')}}/uploads/final_images/{{$post->image}}">
		</a>
	</div>
	@endif
	<div class="the-content">
		{{ substr($post->body, 0, 50) }}...
		{{ link_to('posts/'.$post->alias, 'read on.') }}
		<div class="clearfix"></div>
	</div>
	<div class="clearfix"></div>
</div>