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
			$prompt_link = URL::to('prompts/'.$prompt->link.'?prompt_id='.$prompt->_id);
		}

		$view->with('prompt', $prompt);
		// TODO: modify link so that we can keep track of statistics
		$view->with('prompt_link', $prompt_link);
	}
}