<div class="featured-item generic-item w-{{$w = rand(1,3)}} h-{{$h = rand(1,3)}}">
	<header>
		<h3>{{ link_to('posts/'.$post->alias, $post->title) }}</h3>
		<span class="story-type">{{$post->story_type}}</span>
		<span class="author"><span class="by">by</span> {{link_to('profile/'.$post->user->username, $post->user->username)}}</span>
	</header>
	<section>
		@if($post->image)
		<div class="the-image">
			<a href="{{ URL::to('posts/'.$post->alias) }}" 
				style="background: url('{{Config::get('app.url')}}/uploads/final_images/{{$post->image}}')">
			</a>
		</div>
		@endif
		<div class="the-content">
			@if($h == 1)
				{? $limit = 60 ?}
			@elseif($h == 2)
				{? $limit = 120 ?}
			@elseif($h == 3)
				{? $limit = 180 ?}
			@endif
			{{ substr($post->body, 0, $limit) }}...
			{{ link_to('posts/'.$post->alias, 'read on.') }}
			<div class="clearfix"></div>
		</div>
	</section>
	<div class="clearfix"></div>
</div>