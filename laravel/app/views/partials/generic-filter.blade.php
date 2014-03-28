<aside class="filters-container">
	<div class="container">
		<div class="row filters">
			{? $nofilter = false ?}
			@if(Request::segment(1) == 'categories')
				{? $nofilter = true ?}
			@endif
			
			<div class="visible-sm visible-md visible-lg col-md-12 no-bar">
				<ul class="category-filter">
					<li>{{ link_to('', 'Featured') }}</li><!--Featured = Home-->
					<li><span class="ex">x</span> {{ link_to('categories/all', 'All') }}</li><!--Featured = Home-->
				{? $active = '';?}
				@foreach($categories as $k => $category)
					@if($category->alias == Request::segment(2))
						{? $active = 'active';?}
					@endif 
					<li><span class="ex">x</span> {{ link_to('categories/'.$category->alias, $category->title, array('class' => $active)) }}</li>
					{? $active = '';?}
				@endforeach
				</ul>
			</div>
			
			<div class=" hidden-sm hidden-md hidden-lg mobile-filters @if(!$nofilter) mobile-filters col-xs-12 no-bar  @else col-md-8 col-sm-8 no-bar @endif">
				<button type="button" class="categories-button navbar-toggle" data-toggle="collapse" data-target="#filter-dropdown">Categories</button>
				<div id="filter-dropdown" class="navbar-collapse collapse">
				<ul class="category-filter">
					<li>{{ link_to('', 'Featured') }}</li><!--Featured = Home-->
					<li><span class="ex">x</span> {{ link_to('all', 'All') }}</li><!--Featured = Home-->
					
				{? $active = '';?}
				@foreach($categories as $k => $category)
					@if($category->alias == Request::segment(2))
						{? $active = 'active';?}
					@endif
					<li><span class="ex">x</span> {{ link_to('categories/'.$category->alias, $category->title, array('class' => $active) ) }}</li>
					{? $active = '';?}
				@endforeach
				</ul>
				</div>
			</div>
			@if($nofilter)
			<div class="hidden-sm hidden-md hidden-lg mobile-sortby">
				<button type="button" class="sortby-btn navbar-toggle" data-toggle="collapse" data-target="#sortby-dropdown">Sort by <span class="caret"></button>
				<div id="sortby-dropdown" class="navbar-collapse collapse">
					<ul name="sort_by" class="sort_by_filter">
						{{--This might need some weird switches and stuff for different scenarios--}}
						{? $current_seg = Request::segment(1).'/'.Request::segment(2); ?}
						
						{{--Note that the system stores the $filters in filters.php file--}}
						@foreach($filters as $key => $value)
							<li class="{{ $key == Request::segment(3) ? 'active': '' }}">
								<a href="{{Config::get('app.url')}}/{{$current_seg}}/{{$key}}">{{$value}}</a>
							</li>
						@endforeach
						
					</ul>
				</div>
			<div class="clearfix"></div>
			</div>
			@endif
		</div>
	</div>
</aside>

@if($nofilter)
<aside class="sortby-container">
	<div class="container">
		<div class="row">
			<div class="col-md-10 col-sm-12 visible-sm visible-md visible-lg sort_by_container">
				<ul name="sort_by" class="sort_by_filter">
					{{--This might need some weird switches and stuff for different scenarios--}}
					{? $current_seg = Request::segment(1).'/'.Request::segment(2) ?}
					<li class="sortby-label">Sort by:</li>
					
					@foreach($filters as $key => $value)
						<li class="{{ $key == Request::segment(3) ? 'active': '' }}">
							<a href="{{Config::get('app.url')}}/{{$current_seg}}/{{$key}}">{{$value}}</a>
						</li>
					@endforeach
					
				</ul>
			<div class="clearfix"></div>
			</div>
		</div>
	</div>
<div class="clearfix"></div>
</aside>
@endif