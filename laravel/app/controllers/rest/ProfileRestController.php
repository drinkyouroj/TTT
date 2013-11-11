<?php
//This controller is the REST Controller for the users.
class ProfileRestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//Don't really know what this should list.  Probably nothing for now.
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//if its your profile page you want to get all the info, but if its other people, screw that.
		if($id == Auth::user()->id) {
			$user = User::with( 
								'posts', 
								'inbox',
								'followers',
								'following'
								)
						->where('id', $id)
						->where('confirmed', true)
						->take(1)->get();
			$user = $user->toArray();
			
			//Below is a processor that goes through each of the types and packages them correctly for Ember
			$reprocess = array('posts', 'inbox', 'followers', 'following');
			foreach($reprocess as $process) {
				$process_ids = array();
				if(isset($user[0][$process])) {
					foreach($user[0][$process] as $item) {
						array_push($process_ids, $item['id']);
					}
					$user[0][$process] = $process_ids;
				}
			}
						
		} else {
			$user = User::with('posts')
						->where('id', $id)
						->where('confirmed', true)
						->take(1)->get();
			
			$user = $user->toArray();
		}
		
		unset($user[0]['confirmation_code']);//Don't want that going out via JSON.
		unset($user[0]['confirmed']);
		
		if(isset($user[0])) {
			return Response::json(
				array('user'=>$user[0]),
				200//response is OK!
			);
		} else {
			return Response::json(
				array('user'=>'no user'),
				404//response is OK!
			);
		}
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
				
	}

}