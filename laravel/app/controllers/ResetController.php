<?php

class ResetController extends BaseController {

	public function __construct() {
		
	}
	
	public function start() {
		//meant as a place to test code on L4.
		$max_user = new User;
		
		//First user
        $max_user->username = 'max';
        $max_user->email = 'max@y-designs.com';
        $max_user->password = 'maximus';
		$max_user->password_confirmation = 'max';
        $max_user->confirmed = 1;
		$max_user->save();
	}
	
}