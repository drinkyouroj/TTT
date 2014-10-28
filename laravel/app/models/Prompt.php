<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Prompt extends Eloquent {
	
	protected $connection = 'mongodb';
	protected $collection = 'prompts';
	protected $dates = array('created_at');

	public function validate ( $input ) {
		$rules = array(
				'body' => 'required|max:140',
				'clicks' => 'required|integer|min:0',
				'link_type' => 'required', // link to signup page, post page, category page, etc...
				'link' => 'required'  // The actual link: /signup, /posts/Post-Alias-12-22-1, etc...
		);
		return Validator::make( $input, $rules );
	}

}