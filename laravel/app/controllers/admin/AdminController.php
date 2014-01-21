<?php
class AdminController extends Controller {
	
	public function __construct() {
		
	}
	
	public function getIndex() {
		$ryuhei = User::where('username', '=', 'ryuhei')
				->first();
		
		
	}
	
	//Below sets a certain 
	public function getFeatured() {
		echo 'test';
	} 
	
}