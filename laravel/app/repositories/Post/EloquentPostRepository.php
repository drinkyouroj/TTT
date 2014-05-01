<?php namespace AppStorage\Post;

use Post,DB;

class EloquentPostRepository implements PostRepository {

	public function __construct(Post $post)
	{
		$this->post = $post;
	}

	//Instance
	public function instance($data = array()) {
		return new Post;
	}

	//Create
	public function create($input) {
		
	}

	//Read
	public function findById($id, $published = true, $replationships = array()) {
		$post = $this->post->where('id', $id)
							->where('published', $published);
		return self::count_check($post, $replationships);
	}
	
	public function findByAlias($alias, $published = true) {
		$post = $this->post->where('alias', $alias)
							->where('published', $published);
		return self::count_check($post);
	}
	
	public function findByUserId($user_id, $published = true) {
		$post = $this->post->where('user_id', $alias)
							->where('published', $published);
		return self::count_check($post);
	}
	
	public function findByTitle($title, $published = true) {
		$post = $this->post->where('title',$title);
		return self::count_check($post);
	}
	
	public function lastPostUserId($user_id, $published = true) {
		$post = $this->post->where('user_id', $user_id)
					->where('published', $published)
					->orderBy('created_at', 'DESC');
		return self::count_check($post);
	}
	
	public function random() {
		$post = $this->post->orderBy(DB::raw('RAND()'));
		return self::count_check($post); 
	}
	
		private function count_check($post, $relationships = array()) {
			if($post->count()) {
				if(is_array($relationships) && !empty($relationships) ) {
					foreach($relationships as $relationship) {
						$post->with($relationship);
					}
				}	
				return $post->first();
			} else {
				return false;
			}
		}
	
	//Read Multi
	public function all() {
		return $this->post->get();//unlikely
	}
	
	public function allByUserId($user_id, $published = true) {
		return $this->post->where('user_id',$user_id)
						->where('published', $published)
						->get();
	}
	
	public function allByUserIds($user_ids, $published = true) {
		return $this->post->whereIn('user_id',$user_ids)
						->where('published', $published)
						->get();
	}

	public function owns($post_id, $user_id) {
		return $this->post->where('id', $post_id)
						->where('user_id', $user_id)
						->count();
	}

	//Update
	public function update($input) {}
	
	
	
	public function publish($id) {
		$this->post->where('id', $id)
					->update(array('published'=>0));
	}
	
	public function unpublish($id) {
		$this->post->where('id', $id)
					->update(array('published'=>0));
	}
	
	//restores All User posts
	public function restore($user_id) {
		$this->post->where('user_id', $user_id)->update(array('published'=>1));
	}
	
	public function archive($user_id) {
		$this->post->where('user_id', $user_id)->update(array('published'=>0));
	}
	
	public function incrementView($id) {
		$this->post->where('id', $id)->increment('views', 1);
	}
	
	public function incrementComment($id) {
		$this->post->where('id', $id)->increment('comment_count', 1);
	}
	
	public function incrementLike($id) {
		$this->post->where('id', '=', $id)
					->increment('like_count',1);
	}
	
	public function decrementLike($id) {
		$this->post->where('id', '=', $id)
					->decrement('like_count',1);
	}
	
	
	//Delete
	public function delete($id) {}
	
	
}