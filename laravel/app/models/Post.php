<?php 
class Post extends Eloquent {
		
	//Just to be sure!
	protected $table = 'posts';
	
	
	public function comments()
    {
        return $this->hasMany('Comment', 'post_id');
    }
	
}
