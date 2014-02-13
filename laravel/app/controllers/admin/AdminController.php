<?php
class AdminController extends Controller {
	
	public function __construct() {
		
	}

	//Landing page for admin.	
	public function getIndex() {
		
		//provide currently featured articles.
		$featured = Post::where('featured',1)
					->where('published', 1)
					->orderBy('created_at', 'DESC')
					->get();
					
		
		return View::make('admin.index')
				->with('featured', $featured);
	}

	//Add a new Category
	public function postAddcategory() {
		$category = self::cat_object_input_filter();
		$validator = $category->validate($category->toArray());
		
		if($validator->passes()) {
			$category->save();
			return Redirect::to('admin');
		} else {
			return Redirect::to('admin')
							->withErrors($validator)
							->withInput();
		}
		
	}
	
		private function cat_object_input_filter() {
			
			$category = new Category;
			$category->title = Request::get('title');
			$category->alias = preg_replace('/[^A-Za-z0-9]/', '', Request::get('title') );
			$category->description = Request::get('description');
			
			return $category;
		}
	
	//Delete category
	public function postDelcategory() {
		$categories = Request::get('category');
		
		Category::whereIn('id', $categories)->delete();
		
		return Redirect::to('admin');
	}


	/**************************************************************
	 * RESTFUL stuff
	 */


	//Below sets a certain 
	public function getFeature() {
		$feature_id = Request::segment(3);
		$post = Post::where('id', '=', $feature_id)->first();
		
		//flip the featured
		if($post->featured) {
			$post->featured = false;
			$post->featured_date = date('Y-m-d');
		} else {
			$post->featured = true;
			$post->featured_date = date('Y-m-d');
		}
		$post->save();
		
		return Response::json(
			array(
				'status' => $post->featured 
			),
			200//response is OK!
		);
		
	} 
	
}