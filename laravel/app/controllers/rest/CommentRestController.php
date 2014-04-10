<?php
class CommentRestController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		
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