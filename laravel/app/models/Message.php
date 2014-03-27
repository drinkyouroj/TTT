<?php 
class Message extends Eloquent {
		
	//Just to be sure!
	protected $table = 'messages';
	
	public function to()
	{
		return $this->belongsTo('User', 'to_uid');
	}
	
	public function from()
	{
		return $this->belongsTo('User', 'from_uid');
	}
	
	public function last()
	{
		return $this->where('reply_id', $this->id)
			->orderby('created_at', 'DESC')//Ascending 
			->first();//first of acending = last message
	}
	
	
	public function validate($input) {
		$rules = array(
				'body' => 'Required',
				'to_uid' => 'Required',
				'from_uid' => 'Required'
		);
		return Validator::make($input, $rules);
	}
}