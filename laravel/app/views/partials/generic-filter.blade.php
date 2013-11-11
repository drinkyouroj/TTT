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
			
			<div class="col-md-3 col-sm-3">
				<select name="sort_by" class="sort_by_filter pull-right">
					<option selected="selected">sort by</option>
					<option>Most Favorited</option>
					<option>Most Viewed</option>
					<option>Most Fucked Up</option>
					<option>Advanced</option>
				</select>
			</div>
		</div>
	</div>
</aside>