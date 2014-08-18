<?php namespace AppStorage\Feed;

use Feed ,DB;

//This class marks the actions done by people you are following (new post and repost)
class MongoFeedRepository implements FeedRepository {

	public function __construct(Feed $feed)
	{
		$this->feed = $feed;
	}
	
	public function instance() {
		return new Feed;
	}

	//below is more of an upsert function.
	public function create($data) {
		$exists = self::exists(
						$data['user_id'],
						$data['post_id'],
						$data['feed_type'],
						false//We want the query and not the final count.
						);

		//if this doesn't exist.
		if(! $exists->count() ) {
			$feed = self::instance();
			$feed->user_id = $data['user_id']; //Person that is getting the feed.
			$feed->post_title = $data['post_title'];
			$feed->post_id = $data['post_id'];
			$feed->feed_type = $data['feed_type'];
			$feed->updated_at = $data['updated_at']; //we should just do a ternary and assign today's date here.
			$feed->user = (!empty($data['user'])) ? $data['user'] : '' ; //We have a single user since its easier to access if its a new post.
			$feed->users = (!empty($data['users'])) ? array($data['users']) : array() ;//Stores the users who are involved in the reposting action.
			$feed->save();
		} else {
			$feed = $exists->first();
			$feed->push('users', $data['users'], true);
		}

		return $feed;
	}

	public function exists($user_id, $post_id, $feed_type, $count = true) {
		$query = $this->feed
			->where('user_id', $user_id)
			->where('post_id', $post_id)
			->where('feed_type', $feed_type)
			;

		if($count) {
			return $query->count();
		} else {
			return $query;
		}

	}

	public function find($user_id, $paginate = 12 , $page = 1, $rest = false) {
		$query = $this->feed
					->where('user_id', $user_id)
					->orderBy('updated_at', 'DESC')
					->skip(($page-1)*$paginate)
					->take($paginate);

		if($rest) {
			return $query->with('post.user')->get();
		} else {
			return $query->get();
		}
	}

	//We'll need to 
	public function delete($data) {
		//reposts are more complex.
		if($data['feed_type'] == 'repost') {
			$exists = self::exists(
						$data['user_id'],
						$data['post_id'],
						$data['feed_type'],
						false//We want the query and not the final count.
						);
			if($exists->count() === 1) {
				$this->feed
					->where('user_id', $data['user_id'])
					->where('post_id', $data['post_id'])
					->where('feed_type', $data['feed_type'])
					->delete();
			} else {
				
			}
			
		}
	}

}