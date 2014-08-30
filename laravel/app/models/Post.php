<?php 
class Post extends Eloquent {
	
	//Just to be sure!
	protected $table = 'posts';
	protected $connection = 'mysql';
	protected $softDelete = true;
	protected $with = array('user');
	
	public function __construct() {
		Validator::extend('Twothousand', function($attribute, $value, $parameters)
		{		
		    if(!is_null($value)) {//make sure its not empty.
		    	if(strlen($value) <= 11500) {
		    		//currently only includes alphanumeric.
			    	$word_count = count(str_word_count($value, 1, '0..9'));
					//might want to add a character limit in the future also.
					if($word_count <= 2100) {
						return true;
					} else {
						return false;//more than 2100 words
					}
		    	} else {
		    		return false;//more than 11500 chars
		    	}
		    } else {
		    	return false;//no value
		    }
		});
	}
	
	public function user() {
		return $this->belongsTo('User')->select('id','username');//maybe take out the id later.
	}
	
	public function comments()
    {
        return $this->hasMany('Comment', 'post_id')->orderBy('created_at', 'DESC');  //Gotta be in chronological order.
    }
	
	public function nochildcomments()
	{
		return $this->hasMany('Comment', 'post_id')->where('parent_id','=', 0);
	}
	
	public function favorites()
	{
		return $this->hasMany('Favorite');
	}
	
	public function reposts()
	{
		return $this->hasMany('Repost');
	}
	
	public function likes()
	{
		return $this->hasMany('Like');
	}
	
	public function categories()
	{
		return $this->belongsToMany('Category', 'category_post');
	}
	
	/**
	 * Filters for ALL Category (This is matched against Category Model's relationship names)
	 */
	
	public function scopePostspopular() 
	{
		return $this->where('published', true)->orderBy('like_count','DESC');
	}
	
	public function scopePostsviews()
	{
		return $this->where('published', true)->orderBy('views', 'DESC');
	}

	public function scopeRecent()
	{
		return $this->where('published', true)->orderBy('created_at','DESC');
	}
	
	public function scopePostsdiscussed()
	{
		return $this->where('published', true)->orderBy('comment_count','DESC');
	}
	
	public function scopeLongest()
	{
		return $this->where('published', true)->orderBy(DB::raw('LENGTH(body)'),'DESC');
	}

	public function scopeShortest()
	{
		return $this->where('published', true)->orderBy(DB::raw('LENGTH(body)'),'ASC');
	}

	
	/**
	 * Now with existing id checker
	 */
	public function validate($input, $id = false)
	{
		//If id is false its a new situation.
		if($id == false) {
			$rules = array(
				'title' => 'Required',
				'tagline_1' => 'Required',
				'tagline_2' => 'Required',
				'tagline_3' => 'Required',
				'body' => 'Twothousand',
				'image' => 'required'
			);
		} else {
			$rules = array(
				'title' => 'Required',
				'tagline_1' => 'Required',
				'tagline_2' => 'Required',
				'tagline_3' => 'Required',
				'body' => 'Twothousand'
		);
		}
		
		return Validator::make($input, $rules);
	}
	
	
}