<?php 
class Repost extends Eloquent {
		
	//Just to be sure!
	protected $table = 'reposts';

	public function posts()
    {
        return $this->belongsTo('Post', 'post_id');
    }	
	
	public function users()
	{
		return $this->belongsTo('User','user_id');
	}
	
}
