<?php

class PromptController extends BaseController {

	public function __construct( PromptRepository $prompt ) {
		$this->prompt = $prompt;
	}

	public function routePrompt ( $action ) {
		// So far we only have two actions (signup and post_input)
		// Give credit to prompt id
		$prompt_id = Input::has('prompt_id') ? Input::get('prompt_id') : 0;
		$this->prompt->incrementPromptClicks( $prompt_id );
		if ( $action == 'signup' ) {
			return Redirect::to('user/signup');
		} elseif ( $action == 'post_input' ) {
			return Redirect::to('myprofile/newpost');
		} else {
			return Redirect::to('/');  // Shouldnt hit this case unless someone add new type of action.
		}
	}

}
