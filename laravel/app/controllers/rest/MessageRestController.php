<?php
//This is for your Inbox
class MessageRestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()//or really inbox
	{
		//Get the messages that are addressed to you with the from data for each sender
		$messages = Message::with('from')->where('to_uid', Auth::user()->id)->get();
		error_log('test');
		//send an ok response
		return Response::json(
			array('messages' => $messages->toArray()),
			200//response is OK!
		);
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//Crete a new Message
		$message = new Message;
		
		$message->from_uid = Auth::user()->id;//Gotta be from you.
		$message->to_uid = Request::get('to_uid');
		
		$reply_id = Request::get('reply_id', 0);//defaults to 0
		if($reply_id == 0) {
			$message->reply_id = 0;//replies to none. not a thread.
		} else {
			$message->reply_id = $reply_id;//replies to none. not a thread.
		}
		$message->body = Request::get('body');
		
		//Gotta add some validation work here.
		
		$message->save();
		
		//send an ok response
		return Response::json(
			'Message Sent',
			200//response is OK!
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
						->where('to_uid', Auth::user()->id)//Where the to id is me
						->where('id', $id)// Where the id is of the object I'm looking for.
						->take(1)->get();
		
		$message = $message->toArray();
		return Response::json(
			array('message'=>$message[0]),
			200//response is OK!
		);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//NO need to update a message since there exists no drafting system at this point.
		
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
	}

}