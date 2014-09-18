<?php 
class Featured extends Eloquent {
		
	//Just to be sure!
	protected $table = 'featured';
	
	public function user()
	{
		return $this->belongsTo('User');
	}
	
	public function post()
	{
		return $this->belongsTo('Post')
							->select(
								array(
									'id',
									'user_id',
									'title',
									'alias',
									'tagline_1',
									'tagline_2',
									'tagline_3',
									'story_type',
									'image',
									'published_at'
								)
							);
	}
	
}