<?php
class CommentRestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//This if statement is to figure out if the request is for "ids" //something ember does to get multiple elements
		if($ids = Input::get('ids')) {
			$comments = Comment::with('votes', 'users')->whereIn('id', $ids)->get();
		} else {
			$comments = Comment::with('votes', 'users')->get();//returns all the damn comments
		}
		$comments = $comments->toArray();
		
		return Response::json(
			array(
				'comments' => $comments
			),
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
		$comment = Comment::where('id', $id)->first();
		$comment = $comment->toArray();
		
		return Response::json(
			array(
				'comments' => $comment
			),
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
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{	
		$owns = Comment::where('user_id', '=', Auth::user()->id)
						->where('id', '=', $id)
						->count();
		
		if($owns) {
			$owns = Comment::where('user_id', '=', Auth::user()->id)
							->where('id', '=', $id)
							->update(array('published' => 0));
							//unpublished = deleted.	
			
			return Response::json(
				array(
					'result' => 'deleted'
				),
				200//response is OK!
			);
		} else {
			return Response::json(
				array(
					'result' => 'failed'
				),
				200//response is OK!
			);
		}
		
		

	}

}