{{-- This is the partial template for the category listing at the end of posts and on the featured page --}}

<div class="footer-cat-listing">
	<label>explore our categories</label>
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