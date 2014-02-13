<?php
class UserAction {
	
	function messageAll($job, $data) {
		
		$users = User::select('id')->get();//get the entire list.
		
		
		$job->delete();
	}

}