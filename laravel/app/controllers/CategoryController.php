<?php
class CategoryController extends BaseController {

	private $paginate = 12;//Default Pagination amount for Category pulls.  Maybe make this as part of config later.

	public function __construct() 
	{
		
	}

    /**
     * Get Category Page
     */
    public function getCategory($alias, $request = false)
    {
    	//Grab the data.
		$data = CategoryLogic::data($alias, $request, abs(Request::get('page')), $this->paginate);  //pull the data.
		
		if(!$data) {
			return Redirect::to('categories/all');
		}

		$posts = $data['posts'];
				
		return View::make('v2/category/category')
					->with('cat_title', $data['cat_title'])  //Need the title when forming the actual page.
					->with('cat_desc', $data['cat_desc'])
					->with('posts', $posts)
					->with('current_filter', $data['filter'])
					->with('current_category', $alias);
    }

	/**
	 * Autoload function for the Category Pages.
	 */
	public function getRestCategory($alias, $request = false, $page = 1)
	{
		//Grab the data.
		$data = CategoryLogic::data($alias, $request, $page, $this->paginate);  //pull the data.
		
		$posts = $data['posts'];
		
		if(!count($posts)) {
			return Response::json(
				array('error' => 'No posts were found for the given search: categories/'.$alias.'/'.$request.'/'.$page),
				200
			);
		} else {
			return Response::json(
				array( 'posts' => $posts->toArray()),
				200
			);
		}
		
	}

}