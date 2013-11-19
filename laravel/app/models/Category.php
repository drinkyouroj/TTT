<?php 
class Category extends Eloquent {
		
	//Just to be sure!
	protected $table = 'categories';
	
	public function posts()
    {
        return $this->belongsToMany('Post', 'category_post');
    }
	
}
