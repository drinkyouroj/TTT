<?php 
class Post extends Eloquent {
	
	public function __construct() {
		Validator::extend('Twothousand', function($attribute, $value, $parameters)
		{	
		    if(!is_null($value)) {//make sure its not empty.
		    	//currently only includes alphanumeric.
		    	$word_count = count(str_word_count($value, 1, '0..9'));
				//might want to add a character limit in the future also.
				if($word_count <= 2100) {
					return true;
				} else {
					return false;
				}
		    }
		});
		
	}
	
	//Just to be sure!
	protected $table = 'posts';
	
	public function user() {
		return $this->belongsTo('User');
	}
	
	public function comments()
    {
        return $this->hasMany('Comment', 'post_id');
    }
	
	public function reposts()
	{
		return $this->belongsToMany('Repost');
	}
	
	public function categories()
	{
		return $this->belongsToMany('Category', 'category_post');
	}
	
	public function validate($input)
	{
		$rules = array(
				'title' => 'Required|Unique:posts',
				'tagline_1' => 'Required',
				'tagline_2' => 'Required',
				'tagline_3' => 'Required',
				'body' => 'Twothousand'
		);
		return Validator::make($input, $rules);
	}
	
}