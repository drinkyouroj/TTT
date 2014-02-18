<aside class="filters-container">
	<div class="container">
		<div class="row filters">
			{? $nofilter = false ?}
			@if(Request::segment(1) == 'categories')
				{? $nofilter = true ?}
			@endif
			
			<div class="@if(!$nofilter) visible-md visible-lg col-md-12 no-bar  @else col-md-8 col-sm-8 @endif">
				<ul class="category-filter">
					<li>{{ link_to('', 'Featured') }}</li><!--Featured = Home-->
				@foreach($categories as $k => $category)
					<li><span class="ex">x</span> {{ link_to('categories/'.$category->alias, $category->title) }}</li>
				@endforeach
				</ul>
			</div>
			
			<div class="@if(!$nofilter) hidden-md hidden-lg mobile-filters col-md-12 no-bar  @else col-md-8 col-sm-8 @endif">
			<button type="button" class="categories-button navbar-toggle" data-toggle="collapse" data-target="#filter-dropdown">Categories</button>
				<div id="filter-dropdown" class="navbar-collapse collapse">
				<ul class="category-filter">
					<li>{{ link_to('', 'Featured') }}</li><!--Featured = Home-->
				@foreach($categories as $k => $category)
					<li><span class="ex">x</span> {{ link_to('categories/'.$category->alias, $category->title) }}</li>
				@endforeach
				</ul>
				</div>
			</div>
			
			{{--We need to figure out a way to do the redirect sort based on what controller you're coming from--}}
			@if($nofilter)
			<div class="col-md-4 col-sm-4">
				<select name="sort_by" class="sort_by_filter pull-right">
					<option selected="selected">sort by</option>
					{{--This might need some weird switches and stuff for different scenarios--}}
					{? $current_seg = Request::segment(1).'/'.Request::segment(2) ?}
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/popular">Most Popular</option>
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/viewed">Most Viewed</option>
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/recent">Most Recent</option>
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/discussed">Most Discussed</option>
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/longest">Longest</option>
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/shortest">Shortest</option>
				</select>
			</div>
			@endif
		</div>
	</div>
</aside>