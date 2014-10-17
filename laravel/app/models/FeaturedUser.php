<?php
class FeaturedUser extends Eloquent {
	protected $table = 'featured_users';

	public function user()
	{
		return $this->belongsTo('User');
	}

}