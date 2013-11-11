<?php
class SentRestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//Grabs info for the to_uid person also.
		$messages = Message::with('to')->where('from_uid', Auth::user()->id)->get();
		
		return Response::json(
			array('messages' => $messages),
			200
		);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		$message = Message::with('from')
						->where('from_uid', Auth::user()->id)
						->where('id', $id)
						->take(1)->get();
						
		return Response::json(
			array('message'=>$message),
			200//response is OK!
		);
	}

}