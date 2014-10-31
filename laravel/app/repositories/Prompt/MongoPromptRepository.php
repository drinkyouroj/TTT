<?php namespace AppStorage\Prompt;

use Prompt;

class MongoPromptRepository implements PromptRepository {

	public function __construct(Prompt $prompt) {
		$this->prompt = $prompt;
	}

	public function instance () {
		return new Prompt;
	}

	public function create ( $data ) {
		$new_prompt = self::instance();
		$new_prompt->active = true;
		$new_prompt->clicks = 0;
		$new_prompt->body = $data['body'];
		$new_prompt->link = $data['link'];

		$validation = $new_prompt->validate( $new_prompt->toArray() );
		
		if ( $validation->fails() ) {
			return false;
		} else {
			$new_prompt->save();
			if ( $new_prompt->id ) {
				return $new_prompt;
			} else {
				return false;
			}
		}
	}

	public function delete ( $id ) {
		$this->prompt->where('_id', '=', $id)->delete();
	}

	public function activate ( $id ) {
		$this->prompt->where('_id', '=', $id)->update( array('active' => true) );
	}

	public function deactivate ( $id ) {
		$this->prompt->where('_id', '=', $id)->update( array('active' => false) );
	}

	public function getAll () {
		return $this->prompt->orderBy('clicks', 'desc')->get();
	}

	public function getPromptForSignup () {
		$query = $this->prompt->where('link', '=', 'signup')->get();
		if ( count($query) ) {
			$random = rand( 0, count($query) - 1 );
			return $query[$random];
		} else {
			return false;
		}
	}

	public function getPromptForPostInput () {
		$query = $this->prompt->where('link', '=', 'post_input')->get();
		if ( count($query) ) {
			$random = rand( 0, count($query) - 1 );
			return $query[$random];
		} else {
			return false;
		}
	}

}