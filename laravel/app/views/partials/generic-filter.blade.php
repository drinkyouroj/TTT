<aside class="filters-container">
	<div class="container">
		<div class="row filters">
			<div class="col-md-7 col-sm-7 col-md-offset-1 col-sm-offset-1">
				<ul class="category-filter">
					<li>{{ link_to('', 'Featured') }}</li><!--Featured = Home-->
				@foreach($categories as $k => $category)
					<li>x {{ link_to('categories/'.$category->alias, $category->title) }}</li>
				@endforeach
				</ul>
			</div>
			
			{{--We need to figure out a way to do the redirect sort based on what controller you're coming from--}}
			<div class="col-md-3 col-sm-3">
				<select name="sort_by" class="sort_by_filter pull-right">
					<option selected="selected">sort by</option>
					{{--This might need some weird switches and stuff for different scenarios--}}
					{? $current_seg = Request::segment(1).'/'.Request::segment(2) ?}
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/favorited">Most Favorited</option>
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/viewed">Most Viewed</option>
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/fucked">Most Fucked Up</option>
					<option value="{{Config::get('app.url')}}/{{$current_seg}}/advanced">Advanced</option>
				</select>
			</div>
		</div>
	</div>
</aside>