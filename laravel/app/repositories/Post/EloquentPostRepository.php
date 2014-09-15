<?php namespace AppStorage\Post;

use Post, DB, Request, Auth, Session, FeaturedRepository, SearchRepository;

class EloquentPostRepository implements PostRepository {

	public function __construct(Post $post, 
		          FeaturedRepository $featured,
		          SearchRepository $search )
	{
		$this->post = $post;
		$this->featured = $featured;
		$this->search = $search;
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
			
			$alias = str_replace(' ', '-', Request::get('title'));
			//Gotta make sure to make the alias only alunum.  Don't change alias on the update.  We don't want to have to track this change.
			$post->alias = preg_replace('/[^A-Za-z0-9\-]/', '', $alias).'-'.str_random(5).'-'.date('m-d-Y');//makes alias.  Maybe it should exclude other bits too...
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

		if(!$post->draft) {
			$post->published_at = DB::raw('NOW()');
		}

		return $post;
	}

	//Read
	public function findById($id, $published = true, $replationships = array()) {
		$post = $this->post->where('id', $id);
		return self::count_check($post, $published, $replationships);
	}
	
	public function findByAlias($alias, $published = true) {
		$post = $this->post->withTrashed()->where('alias', $alias);
		return self::count_check($post, $published, array());
	}
	
	public function findByUserId($user_id, $published = true) {
		$post = $this->post->where('user_id', $user_id);
		return self::count_check($post, $published, array());
	}
	
	public function findByTitle($title, $published = true) {
		$post = $this->post->where('title',$title);
		return self::count_check($post, $published, array());
	}
	
	public function lastPostUserId($user_id, $published = true) {
		$post = $this->post->where('user_id', $user_id)
					->where('draft', 0)
					->where('published',1)
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

	//All Drafts belonging to a user
	public function allDraftsByUserId($user_id, $paginate, $page, $rest) {
		$query = $this->post->where('user_id', $user_id)
						->where('draft', 1)
						->where('published',0)
						->orderBy('updated_at', 'DESC')
						->skip(($page-1)*$paginate)
						->take($paginate);
						;
		if($rest) {
			return $query->with('user')->get();
		} else {
			return $query->get();
		}
	}

	public function allByPostIds($post_ids, $published = true) {
		return $this->post->whereIn('id',$post_ids)
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

	//Just a simple date check.
	public function checkEditable($published_at) {
		if(!Session::get('admin') &&
		   strtotime(date('Y-m-d H:i:s', strtotime('-72 hours'))) >= 
		   strtotime($published_at)) 
		{
			return false;
		} else {
			return true;
		}
	}

	//Update
	public function update($input) {}
	
	
	public function publish($id) {
		// update published field
		$post = $this->post->where('id', $id)->first();
		if ( $post instanceof Post ) {
			$post->published = 1;
			$post->save();
			// update search db
			$this->search->updatePost( $post );
		}
	}
	
	public function unpublish($id) {
		$this->post->where('id', $id)
					->update(array('published'=>0));
		// IMPORTANT! Make sure we remove from the featured page (if applicable)
		$this->featured->delete( $id );
		// update search db
		$this->search->deletePost( $id );
	}
	
	//restores All User posts
	public function restore($user_id) {
		$posts = $this->post->where('user_id', $user_id)->get();
		foreach ($posts as $key => $post) {
			$post->published = 1;
			$post->save();
			// Add back to search db
			$this->search->updatePost( $post );
		}
	}
	
	public function archive($user_id) {
		$posts = $this->post->where('user_id', $user_id)->get();
		foreach ($posts as $key => $post) {
			$post->published = 0;
			$post->save();
			// Add back to search db
			$this->search->deletePost( $post->id );
		}
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
		// IMPORTANT! Make sure we remove from the featured page (if applicable)
		$this->featured->delete( $id );
		// Remvoe from search db
		$this->search->deletePost( $id );
	}
	
	public function undelete($id) {
		$post = $this->post->where('id', $id)->withTrashed()->first();
		if ( $post instanceof Post ) {
			$post->restore();
			if ( $post->published ) {
				// Add back to search database
				$this->search->updatePost( $post );
			}
		}
	}

	public function deleteAllByUserId ( $id ) {
		$posts = $this->allByUserId( $id );
		foreach ($posts as $post) {
			$this->delete( $post->id );
		}
	}

	public function restoreAllByUserId($id) {
		$posts = $this->post->where( 'user_id', $id )->withTrashed();
		foreach ($posts as $key => $post) {
			$post->restore();
			if ( $post->published ) {
				// Add back to search database
				$this->search->updatePost( $post );
			}
		}
	}

	public function allDeletedByUserId($user_id){
		return $this->post->onlyTrashed()->where('user_id', $user_id)->get();
	}


	public function getPublishedCount() {
		return $this->post->where( 'published', 1 )->count();
	}
	public function getPublishedTodayCount() {
		return $this->post->where( 'published', 1 )
				   ->where( 'created_at', '>=', new \DateTime('today') )
				   ->count();
	}
	public function getDraftsTodayCount() {
		return $this->post->where( 'draft', 1 )
						  ->where( 'created_at', '>=', new \DateTime('today') )
						  ->count();
	}
	
}