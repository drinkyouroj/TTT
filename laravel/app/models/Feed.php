<?php 
use Jenssegers\Mongodb\Model as Eloquent;

class Feed extends Eloquent {
		
	protected $connection = 'mongodb';
	protected $collection = 'feed';

	public function post()
    {
        return $this->belongsTo('Post', 'post_id');
    }	
	
	public function user()
	{
		return $this->belongsTo('User','user_id');
	}
	
}
