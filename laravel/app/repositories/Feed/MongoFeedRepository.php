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
		//Figure out if the data exists.
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
			$feed->users = (!empty($data['users'])) ? array($data['users']) : array() ;//Stores the users who are involved in the reposting action.
			$feed->save();
		} else {
			$feed = $exists->first();
			if($feed) {
				$feed->push('users', $data['users'], true);
			}
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

	public function find( $user_id, $paginate = 12 , $page = 1, $type = 'all' , $rest = false ) {
		$query = $this->feed
					->where('user_id', $user_id)
					->orderBy('updated_at', 'DESC')
					->skip(($page-1)*$paginate)
					->take($paginate);

		if ($type == 'post') {
			$query = $query->where('feed_type', 'post');
		} else if ($type == 'repost') {
			$query = $query->where('feed_type', 'repost');
		}

		if($rest) {
			return $query->with('post.user')->get();
		} else {
			return $query->get();
		}
	}

	public function findOne($user_id, $type) {
		$query = $this->feed
						->where('user_id', $user_id)
						->where('feed_type','post')
						->orderBy('updated_at', 'DESC')
						->first();
		return $query;
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

			$feed = $exists->first();

			//gotta see if below works
			if($feed) {
				if(count($feed->users) <= 1 ) {
					$exists->delete();
				} else {
					$feed->pull('users', $data['users']);//just delete that one user.
				}
			}
			
		} elseif($data['feed_type'] == 'post') {
			//only 1 user per post
			$this->feed
				->where('user_id', $data['user_id'])
				->where('post_id', $data['post_id'])
				->where('feed_type', $data['feed_type'])
				->where('user', $data['user'])
				->delete();
		}
	}

}