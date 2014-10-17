<?php
use Carbon\Carbon;

class CategoryComposer {
	public function compose($view) {
		//Below is the filters for the Categories.  Its stored here since we needed to iterate through to see what is or is not active.
		//We can probably make this an admin function later.
		$filters = array(
						'popular'=> 'Most Popular',
						'recent' => 'Most Recent',
						'viewed' => 'Most Viewed',
						'discussed' => 'Most Discussed'
						);

		//Grab all the categories
		if(Cache::has('categories') && !Session::get('admin') ) {
			$categories = Cache::get('categories');
		} else {
			$category = App::make('AppStorage\Category\CategoryRepository');
			$categories = $category->all();
			$expiresAt = Carbon::now()->addMinutes(10);
			Cache::put('categories', $categories, $expiresAt);
		}

		$view->with('categories', $categories )
				 ->with('filters', $filters);
	}
}