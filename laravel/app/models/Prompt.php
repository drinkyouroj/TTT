<?php
use Jenssegers\Mongodb\Model as Eloquent;

class Prompt extends Eloquent {
	
	protected $connection = 'mongodb';
	protected $collection = 'prompts';
	protected $dates = array('created_at');

	/*
		NOTE: link field simply tells us where to link to, not the actual link
	*/
	public function validate ( $input ) {
		$rules = array(
				'body' => 'required|max:140',
				'clicks' => 'required|integer|min:0',
				'link' => 'required'
		);
		return Validator::make( $input, $rules );
	}

}