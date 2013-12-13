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
					<option value="{{Request::url()}}/favorited">Most Favorited</option>
					<option value="{{Request::url()}}/viewed">Most Viewed</option>
					<option value="{{Request::url()}}/fucked">Most Fucked Up</option>
					<option value="{{Request::url()}}/advanced">Advanced</option>
				</select>
			</div>
		</div>
	</div>
</aside>