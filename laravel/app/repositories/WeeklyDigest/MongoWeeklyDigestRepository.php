<?php namespace AppStorage\WeeklyDigest;

use WeeklyDigest, DateTime;

class MongoWeeklyDigestRepository implements WeeklyDigestRepository {

	public function __construct(WeeklyDigest $digest) {
		$this->digest = $digest;
	}

	public function instance () {
		return new WeeklyDigest;
	}

	public function create ( $data ) {
		
	}

	public function delete ( $id ) {

	}

	public function all( $sent = true ) {
		return $this->digest->where('sent', '=', true)->get();
	}	

	/*
	 *	Get the weekly digest for today (assume that weekly digest will be created and sent in same day)
	 */
	public function getWeeklyDigest () {
		return $this->digest->where('created_at', '>=', new DateTime('today'))->first();
	}

	/*
	 *	Add a post to the weekly digest. Also creates the digest if first post added.
	 */
	public function addPost ( $alias, $position ) {
		// Check to see if the weekly digest already exists for today and position
		$todays_digest = $this->digest->where('created_at', '>=', new DateTime('today'))->first();
		if ( $todays_digest instanceof WeeklyDigest ) {
			// There is already a digest for today, update the post at position	
			$posts = $todays_digest->posts;		
			$posts[$position]['post_alias'] = $alias;
			$todays_digest->posts = $posts;
			$todays_digest->save();
		} else {
			// Create the new weekly digest
			$new_digest = self::instance();
			$posts = array();
			for ( $i = 0; $i < 5; $i++ ) {
				$posts[$i] = array( 'post_alias' => '', 'clicks' => 0 );
			}
			$posts[$position] = array( 'post_alias' => $alias, 'clicks' => 0 );
			$new_digest->posts = $posts;
			$new_digest->sent = false;
			$new_digest->save();
		}
	}

	/*
	 *	Update the number of times that this post has been clicked in email
	 */
	public function incrementViews( $digest_id, $alias ) {
		$digest = $this->digest->where('_id', '=', $digest_id)
							   ->where('sent', '=', true)
							   ->first();
		if ( $digest instanceof WeeklyDigest ) {
			// Loop through posts to try and find post_alias
			for ( $i = 0; $i < 5; $i++ ) {
				if ( is_array($digest->posts[$i]) && $digest->posts[$i]['post_alias'] == $alias ) {
					$digest->posts[$i]['clicks'] += 1;
					$digest->save();
					break;
				}
			}			
		}
	}

}