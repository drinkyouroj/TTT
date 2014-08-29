<?php 
class Comment extends Eloquent {
		
	//Just to be sure!
	protected $table = 'comments';
	
	public function validate($input) {
		$rules = array(
				'body' => 'Required'
		);
		return Validator::make($input, $rules);
	}
	
	public function children()
	{
		return $this->hasMany('Comment', 'parent_id');
	}
		
	public function post()
	{
		return $this->belongsTo('Post')->select('id', 'title','user_id','alias');
	}
	
	public function votes()
    {
        return $this->hasMany('Votedcomment', 'comment_id');
    }
	
	public function user()
	{
		return $this->belongsTo('User', 'user_id')->select('id','username');
	}
}
