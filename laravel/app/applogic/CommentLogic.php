<?php namespace AppLogic\CommentLogic;

//Below will be replaced with Repositories when we have the chance.
use App, 
	AppStorage\Comment\CommentRepository
	;


/**
 * This class holds all the business logic for the Comment Controller
 */
class CommentLogic {
	
	public function __construct() {
		//Below sucks compared to how the interface is usually implemented, but its having issues so we're doing it this way.
		$this->comment = App::make('AppStorage\Comment\CommentRepository');
	}
	
	/****
	 * Below function is a future function for filtering the body
	 * so that we can define links within the comments to profiles. (think of the @ on FB or Instagram)
	 */ 
	public function comment_body_filter($body)
	{
		
	}
	
}