<?php 
class Category extends Eloquent {
		
	//Just to be sure!
	protected $table = 'categories';
	
	public function posts()
    {
        return $this->belongsToMany('Post', 'category_post')
					->orderBy('id', 'DESC');
    }
	
	public function postsviews()
    {
        return $this->belongsToMany('Post', 'category_post')
					->orderBy('views', 'DESC');
    }
	
	
	public function postsfavorites()
    {
        return $this->belongsToMany('Post', 'category_post')
					->with('favorites');
					
    }
	
}
