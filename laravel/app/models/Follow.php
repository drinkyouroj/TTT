<?php 
class Follow extends Eloquent {
		
	//Just to be sure!
	protected $table = 'follows';
	
	public function users()
	{
		return $this->belongsToMany('User');
	}
	
}
