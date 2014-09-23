	<div class="trending-wrapper">
		<h2>Trending</h2>
		<section class="filters">
			<div class="filters-container category-filter-container">
				<div class="category-filter-title cat-container">
					Categories
				</div>
				<ul class="filter-dropdown list-unstyled">
					{{-- I dont display the active category in the dropdown list --}}
					@foreach ( $categories as $category )
						<li><a href="{{ URL::to('categories/'.$category->alias) }}" class="filter filter-category" data-category-filter="{{$category->alias}}">{{$category->title}}</a></li>
					@endforeach
				</ul>
			</div>
		<div class="clearfix"></div>
		</section>
		<div class="trending-content">

		</div>
		<div class="clearfix"></div>
	</div>

	<div class="loading-container">
		<img src="{{ URL::to('images/posts/comment-loading.gif') }}">
	</div>