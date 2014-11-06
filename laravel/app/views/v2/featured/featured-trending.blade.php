	@if( is_object($fuser) )
	
		@include('v2.partials.featured-user')
	
	@endif

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
		

		<div class="footer-cat-wrapper">
			<div class="footer-cat-container container">
				<div class="footer-cat col-md-12">
					@include( 'v2/partials/category-listing' )
				</div>
			</div>
		</div>
	</div>