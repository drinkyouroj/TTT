<?php
use Carbon\Carbon;

class PostComposer {
	public function compose($view) {
		$is_mod = Session::get('mod');
		$is_admin = Session::get('admin');
		if ( $is_mod || $is_admin ) {
			if(Request::segment(2,0)){
				$post_rep = App::make('AppStorage\Post\PostRepository');
				$post = $post_rep->findByAlias(Request::segment(2));
				if(is_object($post)) {
					$post_logic = App::make('AppLogic\PostLogic\PostLogic');
					$readability =  0;//$post_logic->readability($post->body);
					$grade =  0;//$post_logic->grade($post->body);
					$sentiment = $post_logic->sentiment($post->body);

					$view->with( 'readability', $readability)
						 ->with( 'grade', $grade)
						 ->with( 'sentiment', $sentiment );
				}
			}
		}
	}
}