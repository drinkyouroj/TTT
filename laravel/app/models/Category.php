<?php 
class Category extends Eloquent {
		
	//Just to be sure!
	protected $table = 'categories';
	
	public function __construct() {
		$this->select = array(

									'user_id',
									'title',
									'alias',
									'tagline_1',
									'tagline_2',
									'tagline_3',
									'story_type',
									'image',
									'published_at'
								);
	}
	
	public function posts()
    {
        return $this->belongsToMany('Post', 'category_post')
					->where('published',1);
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
		//$select = array('alias','featured', 'image', 'like_count', 'published', 'story_type', 'tagline_1', 'tagline_2', 'tagline_3', 'title', 'user_id');	
		return $this->belongsToMany('Post', 'category_post')
					->where('published',1)
					->orderBy('like_count','DESC')
					//->select($select)//Currently not possible. FUCK!
					;
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
					->select($this->select)
					->orderBy('published_at','DESC');
	}

	public function validate($input) {
		$rules = array(
			'title' => 'Required|Unique:categories'
		);
		return Validator::make($input, $rules);
	}

}
