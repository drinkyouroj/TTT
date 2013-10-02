<?php 
class Comment extends Eloquent {
		
	//Just to be sure!
	protected $table = 'comments';
	
	public function votes()
    {
        return $this->hasMany('Votedcomment', 'comment_id');
    }
	
	public function users()
	{
		return $this->belongsTo('User', 'user_id');
	}
	
}
