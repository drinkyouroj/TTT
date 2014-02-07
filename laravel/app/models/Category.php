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
	
	public function postspopular()
	{
		return $this->belongsToMany('Post', 'category_post')
					->orderBy('like_count','DESC');
	}
	
	public function postsdiscussed()
	{
		return $this->belongsToMany('Post', 'category_post')
					->orderBy('comment_count','DESC');
	}
	
	public function longest()
	{
		return $this->belongsToMany('Post', 'category_post')
					->orderBy(DB::raw('LENGTH(body)'),'DESC');	
	}
	
	public function shortest()
	{
		return $this->belongsToMany('Post', 'category_post')
					->orderBy(DB::raw('LENGTH(body)'),'ASC');	
	}
}
