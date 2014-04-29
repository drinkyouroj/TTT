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
		$data = CategoryLogic::data($alias, $request, abs(Request::get('page')), $this->paginate);//pull the data.
		
		$posts = $data['posts'];
		
		if(!count($posts)) {
			$posts = 'No Posts in this Category!';
		}
				
		return View::make('generic.index')
					->with('cat_title', $data['cat_title'])//Need the title when forming the actual page.
					->with('posts', $posts);
    }

	/**
	 * Autoload function for the Category Pages.
	 */
	public function getRestCategory($alias, $request = false) 
	{
		//Grab the data.
		$data = CategoryLogic::data($alias, $request, abs(Request::get('page')), $this->paginate);//pull the data.
		
		$posts = $data['posts'];
		
		if(!count($posts)) {
			return Response::json(
				array('error' => true),
				200
			);
		} else {
			return Response::json(
				$posts->toArray(),
				200
			);
		}
		
	}

}