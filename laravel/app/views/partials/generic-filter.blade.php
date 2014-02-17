<aside class="filters-container">
	<div class="container">
		<div class="row filters">
			<div class="col-md-8 col-sm-8 ">
				<ul class="category-filter">
					<li>{{ link_to('', 'Featured') }}</li><!--Featured = Home-->
				@foreach($categories as $k => $category)
					<li><span class="ex">x</span> {{ link_to('categories/'.$category->alias, $category->title) }}</li>
				@endforeach
				</ul>
			</div>
			
			{{--We need to figure out a way to do the redirect sort based on what controller you're coming from--}}
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
		</div>
	</div>
</aside>