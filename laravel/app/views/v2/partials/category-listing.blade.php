{{-- This is the partial template for the category listing at the end of posts and on the featured page --}}

<div class="new-pop-container">
	<a href="{{ URL::to('categories/new') }}">
		<h3 class="category-label">- Explore Our Categories -</h3>
		<div class="new-container">
			<div class="new-inner">
				<h4>New</h4>
			</div>
		</div>
	</a>
	<a href="{{ URL::to('categories/all') }}">
		<div class="popular-container">
			<div class="popular-inner">
				<h4> Popular</h4>
			</div>
		</div>
	</a>
<div class="clearfix"></div>
</div>

<div class="footer-cat-listing">
	<ul class="category-listing">
		@foreach( $categories as $category )
			<li> 
				<a href="{{ URL::to('categories/'.$category->alias) }}">
					<div class="cat-img {{ strtolower($category->title) }}"> </div>
					<h4>{{ $category->title }}</h4>
				</a> 
			</li>
		@endforeach
	</ul>
	<div class="clearfix"></div>
</div>