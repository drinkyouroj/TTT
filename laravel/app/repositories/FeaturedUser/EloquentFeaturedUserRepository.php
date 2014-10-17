<?php namespace AppStorage\FeaturedUser;

use DB,
	FeaturedUser,
	User,
	Cache;

class EloquentFeaturedUserRepository implements FeaturedUserRepository {

	public function __construct(FeaturedUser $featureduser, User $user)
	{
		$this->featureduser = $featureduser;
		$this->user = $user;
	}

	//Instance
	public function instance() {
		return new FeaturedUser;
	}

	public function create($user_id,$excerpt) {
		$user = $this->user->where('id', $user_id)->first();
		if( $user instanceof User && strlen($excerpt)) {
			$current = $this->featureduser->where('current', true)->first();
			if(isset($current->id)) {
				$current->current = false;
				$current->save();
			}

			$fuser = self::instance();
			$fuser->user_id = $user->id;
			$fuser->excerpt = $excerpt;
			$fuser->current = true;
			$fuser->save();
			return $fuser;
		} else {
			return false;
		}
	}

	public function delete($id) {
		$this->featureduser->where('id', $id)->delete();	
		return true;
	}

}