<?php
class FooterComposer {

	public function compose( $view ) {
		$prompt_repo = App::make('AppStorage\Prompt\PromptRepository');

		// Dont display on certain pages
		// post input
		// signup
		// ...

		if ( Auth::guest() ) {
			// Pull promts for non logged in users
			$prompt = $prompt_repo->getPromptForSignup();
		} else {
			// Pull prompts for logged in users
			$prompt = $prompt_repo->getPromptForPostInput();
		}

		$prompt_link = '';
		if ( $prompt instanceof Prompt ) {
			if ( $prompt->link == 'signup' ) {
				$prompt_link = URL::to('user/signup');
			} else if ( $prompt->link == 'post_input' ) {
				$prompt_link = URL::to('myprofile/newpost');
			}
		}

		$view->with('prompt', $prompt);
		// TODO: modify link so that we can keep track of statistics
		$view->with('prompt_link', $prompt_link);
	}
}