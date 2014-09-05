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

	public function findByPostId ( $post_id ) {
		$result = $this->featured->where( 'post_id', $post_id )->get()->first();
		if ( $result instanceof Featured ) {
			return $result;
		}
		return false;
	}

	public function delete($post_id) {
		$this->featured->where('post_id', intval( $post_id ) )->delete();
	}

	/**
	 *	Swaps a featured items position with the target position item. If no item
	 * 	exists in the target position, just move this one there.
	 */
	public function swapFeaturedItems ( $post_id, $target_position ) {
		$existing = $this->findByPostId( $post_id );
		$target_featured = $this->featured->where('front', true)
										  ->where('position', $target_position)
										  ->get();
		// Make sure we have featured item and it is on the front page
		if ( $existing && $existing->front ) {
			$current_position = $existing->position;
			$existing->position = $target_position;
			if ( $target_featured instanceof Featured ) {
				// There is a featured post in that position.
				$target_featured->position = $current_position;
				$target_featured->save();
			}
			$existing->save();
			return true;
		} else {
			return false;
		}
	}
}