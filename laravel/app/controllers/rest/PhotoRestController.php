<?php
class PhotoRestController extends \BaseController {

	public function __construct() {
		$this->beforeFilter('auth');
	}
	
	public function index()
	{
		
	}
	
	//POST info
	public function store()
	{
			
		$url = Input::get('url');
		$process = Input::get('process');
		
		$validator = Validator::make(
			array(
				'url' => $url,
				'process' => $process
			),
			array(
				'url' => 'required|url'
			)
		);
		
		if($validator->passes) {
			//process the image.
			
			
			
			return Response::json(
				'',
				200
			);
			
		} else {
			return Response::json(
				'Validation Failed',
				500
			);
		}	
		
		
	}
	
	//GET info
	public function show()
	{
		
	}
	
	
}
