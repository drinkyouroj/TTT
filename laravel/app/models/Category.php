<?php 
class Category extends Eloquent {
		
	//Just to be sure!
	protected $table = 'categories';
	
	public function posts()
    {
        return $this->hasMany('Post', 'category_id');
    }
	
}
