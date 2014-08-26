{{-- This is the partial template for a profile post --}}
<div class="profile-post">
	
	<div class="image-overlay">
		<img class="post-author-avatar" src="">
		story by <a href=""> {{ $item->post->user->username }} </a>
	</div>
	<img class="post-image" src="">
	<p class="post-title"> 
		{{ $item->post->title }}
	</p>
	<ul class="list-inline">
		<li> {{ $item->post->tagline_1 }} </li>
		<li> {{ $item->post->tagline_2 }} </li>
		<li> {{ $item->post->tagline_3 }} </li>
	</ul>
	
</div>