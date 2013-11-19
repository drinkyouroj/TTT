<?php 
class Post extends Eloquent {
		
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
				'body' => 'Required'
		);
		return Validator::make($input, $rules);
	}
	
}