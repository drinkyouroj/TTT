{{--Let's prevent some user errors here--}}

<!--{{$featured->height}}-->

	{? $h = $featured->height ?}
	@if($h >= 2 && strlen($post->body) < 700 )
		{? $h = 1 ?}
	@endif


<div class="animated fadeIn featured-item generic-item w-{{$w = $featured->width}} h-{{$h}}">
	
	<header>
		<h3>{{ link_to('posts/'.$post->alias, $post->title) }}</h3>
		<span class="story-type">{{$post->story_type}}</span>
		<span class="author"><span class="by">by</span> 
		@if(isset($post->user->username))
			{{link_to('profile/'.$post->user->username, $post->user->username)}}
		@else
			{{link_to('profile/nobody', 'Nobody')}}
		@endif
		</span>
	</header>
	<section>
		@if($post->image)
		<div class="the-image">
			<a href="{{ URL::to('posts/'.$post->alias) }}" 
				style="background-image: url('{{Config::get('app.url')}}/uploads/final_images/{{$post->image}}')">
			</a>
			@if($h == 1)
			<div class="the-featured-content">
				
				{{ substr($post->body, 0, 120) }}...
				<div class="clearfix"></div>
			</div>
			@endif
		</div>
		@endif
		@if($h == 2)

			{{--The below function --}}
			@if($w == 1)
				{? $limit = 510 ?}
			@elseif($w == 2)
				{? $limit = 1150 ?}
			@elseif($w == 3)
				{? $limit = 2200 ?}
			@endif
			
			<div class="the-content">
				
				{{ substr($post->body, 0, $limit) }}...
				{{link_to('posts/'.$post->alias,'Read on')}}
				<div class="clearfix"></div>
			</div>
		@endif
	</section>
	<div class="clearfix"></div>
</div>