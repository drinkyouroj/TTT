{{-- This is the partial template for a post and/or feed repost listing --}}
{{-- PLEASE keep updated with the equivalent handlebars template! --}}

<div class="post-container">
	
	<div class="post-image-overlay">
		
	</div>

	<a class="image-link" href="{{ URL::to('posts/'.$post->alias) }}">
		<div class="top-fade"> 
		</div>
		<div class="post-image" style="background-image:url('{{ Config::get('app.imageurl').'/'.$post->image }}')">

		</div>
	</a>

	<p class="post-title"> 
		<a href="{{ URL::to('posts/'.$post->alias) }}">
			{{ $post->title }}
		</a>
	</p>
	<div class="line"></div>
	<ul class="post-taglines list-inline">
		<li> {{ $post->tagline_1 }} </li>
		<li> {{ $post->tagline_2 }} </li>
		<li> {{ $post->tagline_3 }} </li>
	</ul>
	<div class="author">
		{{$post->story_type}} by <a href="{{ URL::to('profile/'.$post->user->username ) }}"> {{ $post->user->username }} </a>
	</div>
</div>