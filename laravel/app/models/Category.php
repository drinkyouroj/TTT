<?php 
class Category extends Eloquent {
		
	//Just to be sure!
	protected $table = 'categories';
	
	public function posts()
    {
        return $this->belongsToMany('Post', 'category_post')
					->where('published',1)
					->orderBy('id', 'DESC');
    }
	
	public function postsviews()
    {
        return $this->belongsToMany('Post', 'category_post')
					->where('published',1)
					->orderBy('views', 'DESC');
    }
	
	
	public function postsfavorites()
    {
        return $this->belongsToMany('Post', 'category_post')
					->where('published',1)
					->with('favorites');
    }
	
	public function postspopular()
	{
		return $this->belongsToMany('Post', 'category_post')
					->where('published',1)
					->orderBy('like_count','DESC');
	}
	
	public function postsdiscussed()
	{
		return $this->belongsToMany('Post', 'category_post')
					->where('published',1)
					->orderBy('comment_count','DESC');
	}
	
	public function longest()
	{
		return $this->belongsToMany('Post', 'category_post')
					->where('published',1)
					->orderBy(DB::raw('LENGTH(body)'),'DESC');	
	}
	
	public function shortest()
	{
		return $this->belongsToMany('Post', 'category_post')
					->where('published',1)
					->orderBy(DB::raw('LENGTH(body)'),'ASC');	
	}

	public function recent()
	{
		return $this->belongsToMany('Post', 'category_post')
					->where('published',1)
					->orderBy('created_at','DESC');
	}

	public function validate($input) {
		$rules = array(
			'title' => 'Required|Unique:categories'
		);
		return Validator::make($input, $rules);
	}

}
