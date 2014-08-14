<?php namespace AppStorage\Featured;

use DB,
	Featured,
	User,
	Request
	;

class EloquentFeaturedRepository implements FeaturedRepository {

	public function __construct(Featured $featured)
	{
		$this->featured = $featured;
	}

	//Instance
	public function instance() {
		return new Featured;
	}

	public function create($post_id) {

	}

	public function find($paginate = 8, $page = 1, $rest = false) {
		$query = $this->featured
						->orderBy('order', 'ASC')
						->skip(($page-1)*$paginate)
						->take($paginate);

		if($rest) {
			return $query->with('post.user')->get();
		} else {
			return $query->get();
		}
			
	}

	public function delete($post_id) {}
}