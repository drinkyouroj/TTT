	<div class="trending-wrapper">
		{{--
		<section class="filters">
			<div class="filters-container category-filter-container">
				<div class="category-filter-title cat-container">
					Categories
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
		<div class="trending-content">
			<h3>Past Featured</h3>
			@foreach($randoms as $featured)
				<?php $post = $featured->post ?>
				@include('v2.partials.post-listing-partial')
			@endforeach
		</div>
		<div class="clearfix"></div>
	</div>

	<div class="sectional-line"></div>
	<div class="footer-cat-container container">
		<div class="row">
			<div class="footer-cat col-md-12">
				@include( 'v2/partials/category-listing' )
			</div>
		</div>
	</div>