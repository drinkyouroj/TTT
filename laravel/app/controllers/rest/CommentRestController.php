<?php
class CommentRestController extends \BaseController {

	public function __construct(CommentRepository $comment) {
		$this->comment = $comment;
	}

	public function show() {}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user_id = Auth::user()->id;	
		$owns = $this->comment->owns($id, $user_id);
		
		if($owns) {
				
			$this->comment->unpublish($id, $user_id);//unpublished = deleted.
						
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