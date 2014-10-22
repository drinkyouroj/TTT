<?php namespace AppStorage\EmailPref;

use Config,
	EmailPref
	;

class MongoEmailPrefRepository implements EmailPrefRepository {

	public function __construct( EmailPref $pref )
	{
		$this->pref = $pref;
	}

	public function instance() {
		return new EmailPref;
	}

	public function create($data) {
		$pref = self::instance();
		$pref->user_id = $data['user_id'];
		$pref = self::dataInit($data, $pref);
		$pref->save();
		return $pref;
	}

	public function find($user_id) {
		return $this->pref->where('user_id', $user_id)->first();
	}

	//Does this user have a set of preferences
	public function exists($user_id, $count=false) {		
		$query = $this->pref->where('user_id', $user_id);

		if($count) {
			return $query->count();
		} else {
			return $query->first();
		}
	}

	//Update the preference set.
	public function update($data) {
		$pref = self::exists($data['user_id'], false);
		$pref = self::dataInit($data, $pref);
		$pref->save();
	}

		private function dataInit($data, $pref) {
			$pref->views 	=	(!empty($data['views'])) ? strlen($data['views']) : 1;
			$pref->comment  =	(!empty($data['comment'])) ? strlen($data['comment']) : 1;
			$pref->reply 	=	(!empty($data['reply'])) ? strlen($data['reply']) : 1;
			$pref->follow 	= 	(!empty($data['follow'])) ? strlen($data['follow']) : 1;
			$pref->like 	=	(!empty($data['like'])) ? strlen($data['like']) : 0;
			$pref->repost 	=	(!empty($data['repost'])) ? strlen($data['repost']) : 0;
			return $pref;
		}

}