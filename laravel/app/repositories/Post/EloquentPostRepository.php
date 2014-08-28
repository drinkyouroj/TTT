<?php namespace AppStorage\Post;

use Post, DB, Request, Auth;

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
	
	/**
	 * Input filtering.  (Probably move this piece to the repository)
	 * @param boolean $new Is this a new post?
	 * @param object $post Post object (for updates)
	 */
	public function input($new = false,$post = false)
	{	
		if($new) {
			//Creates a new post
			$post = self::instance();
			$post->user_id = Auth::user()->id;
			
			//Gotta make sure to make the alias only alunum.  Don't change alias on the update.  We don't want to have to track this change.
			$post->alias = preg_replace('/[^A-Za-z0-9]/', '', Request::get('title')).'-'.str_random(5).'-'.date('m-d-Y');//makes alias.  Maybe it should exclude other bits too...
			$post->story_type = Request::get('story_type');
			
			$post->category = serialize(Request::get('category'));
			$post->image = Request::get('image','0');//If 0, then it means no photo.
		}
		
		$post->title = Request::get('title');
		$post->tagline_1 = Request::get('tagline_1');
		$post->tagline_2 = Request::get('tagline_2');
		$post->tagline_3 = Request::get('tagline_3');
		
		$post->body = strip_tags(Request::get('body'), '<p><i><b><br>');//Body is the only updatable thing in an update scenario.
		$post->published = 1;
		$post->draft = Request::get('draft', 1);//default is 1 so that it won't accidentally get published.
		return $post;
	}

	//Read
	public function findById($id, $published = true, $replationships = array()) {
		$post = $this->post->where('id', $id);
		return self::count_check($post, $published, $replationships);
	}
	
	public function findByAlias($alias, $published = true) {
		$post = $this->post->where('alias', $alias);
		return self::count_check($post, $published, array());
	}
	
	public function findByUserId($user_id, $published = true) {
		$post = $this->post->where('user_id', $alias);
		return self::count_check($post, $published, array());
	}
	
	public function findByTitle($title, $published = true) {
		$post = $this->post->where('title',$title);
		return self::count_check($post, $published, array());
	}
	
	public function lastPostUserId($user_id, $published = true) {
		$post = $this->post->where('user_id', $user_id)
					->orderBy('created_at', 'DESC');
		return self::count_check($post, $published, array());
	}
	
	public function random() {
		$post = $this->post->orderBy(DB::raw('RAND()'));
		return self::count_check($post);
	}
	
		private function count_check($post, $published = NULL, $relationships = array()) {
			if($post->count()) {
				if($published != 'any' && is_bool($published)) {
					$post = $post->where('published', $published);
				} elseif($published == 'trashed') {
					$post = $post->withTrashed();
				}
				if(is_array($relationships) && !empty($relationships) ) {
					foreach($relationships as $relationship) {
						$post = $post->with($relationship);
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
	
	public function allFeatured() {
		return $this->post->where('featured',1)
						->where('published', 1)
						->orderBy('created_at', 'DESC')
						->get();
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

	//Count
	public function countPublished() {
		return $this->post->where('published', 1)->count();
	}

	//Check
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
		$this->post->where('id', $id)
					->increment('like_count',1);
	}
	
	public function decrementLike($id) {
		$this->post->where('id', $id)
					->decrement('like_count',1);
	}
	
	//Delete
	public function delete($id) {
		$this->post->where('id', $id)->delete();//remember that the Eloquent model has softdelete.
	}
	
	public function undelete($id) {
		$this->post->where('id', $id)->withTrashed()->restore();
	}
	
}