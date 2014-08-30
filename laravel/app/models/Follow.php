<?php 
class Follow extends Eloquent {
		
	//Just to be sure!
	protected $table = 'follows';
	
	public function users()
	{
		return $this->belongsTo('User','user_id');
	}
	
	public function followers()
	{
		return $this->belongsTo('User','follower_id')
					->where('banned', 0)
					->select('id', 'username');
	}


	public function following()
	{
		return $this->belongsTo('User','user_id')
					->where('banned', 0)
					->select('id', 'username');
	}
	
}
