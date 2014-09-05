<?php namespace AppStorage\Featured;

use DB,
	Featured,
	Post
	;

class EloquentFeaturedRepository implements FeaturedRepository {

	public function __construct(Featured $featured, Post $post)
	{
		$this->featured = $featured;
		$this->post = $post;
	}

	//Instance
	public function instance() {
		return new Featured;
	}

	public function create($post_id, $position = 'top1') {
		
		//Check to see if the current featured exists on a selected position.
		$current_position = $this->featured
									->where('position', $position)
									->where('front',1);

		if( $current_position->count() ) {
			$current = $current_position->first();
			$current->front = false;
			$current->save();
		}

		$post = $this->post->find($post_id);

		$featured = self::instance();
		$featured->user_id = $post->user_id;
		$featured->post_id = $post_id;
		$featured->position = $position;
		$featured->front = true;
		$featured->save();

		return $featured;
	}

	public function find($paginate = 8, $page = 1, $rest = false, $front = false) {
		$query = $this->featured
						->where('front', false)//we have to make sure that it isn't already on the front page.
						->orderBy('created_at', 'ASC')
						->skip(($page-1)*$paginate)
						->take($paginate);

		if($rest) {
			return $query->with('post.user')->get();
		} else {
			return $query->get();
		}
			
	}

	public function delete($post_id) {
		$this->featured->where('post_id', $post_id)->delete();
	}
}