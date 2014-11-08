<?php 
use Jenssegers\Mongodb\Model as Eloquent;

/*
	to:
	from:
	body:
	read:
	created_at:
*/

class Message extends Eloquent {
		
	protected $connection = 'mongodb';
	protected $collection = 'messages';
	protected $dates = array('created_at');

	/*
	 * Relationship between comment and user model
	 */
	public function to() {
		return $this->belongsTo('User', 'to')->select('id', 'username');
	}
	public function from() {
		return $this->belongsTo('User', 'from')->select('id', 'username');	
	}

}