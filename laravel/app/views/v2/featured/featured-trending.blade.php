	<div class="featured-user-container">	
		<h3 class="user-label">- Featured User -</h3>
		@include('v2.partials.featured-user')
	</div>

	<div class="category-wrapper">
		{{--
		<section class="filters">
			<div class="filters-container category-filter-container">
				<div class="category-filter-title cat-container">
					categorycategoCategories
				</div>
				<ul class="filter-dropdown list-unstyled">					
					@foreach ( $categories as $category )
						<li><a href="{{ URL::to('categories/'.$category->alias) }}" class="filter filter-category" data-category-filter="{{$category->alias}}">{{$category->title}}</a></li>
					@endforeach
				</ul>
			</div>
		<div class="clearfix"></div>
		</section>
		--}}
		<div class="new-pop-container container">
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
		</div>
		<div class="clearfix"></div>

		<div class="footer-cat-wrapper">
			<div class="footer-cat-container container">
				<div class="row">
					<div class="footer-cat col-md-12">
						@include( 'v2/partials/category-listing' )
					</div>
				</div>
			</div>
		</div>
	</div>