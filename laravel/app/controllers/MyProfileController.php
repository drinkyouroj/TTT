<?php
class MyProfileController extends BaseController {

	public function __construct(
							NotificationRepository $not,
							FeedRepository $feed
							) {
		$this->not = $not;
		$this->feed = $feed;
	}


	/**
	 *	Get the default feed.
	 */
	public function getFeed () {
		$user_id = Auth::user()->id;
		$paginate = 12;
		$page = 1;
		$feed_type = 'all';
		$feed = $this->feed->find( $user_id, $paginate, $page, $feed_type, false );
		// TODO: populate the view accordingly...
		return View::make('v2/myprofile/feed')
				   ->with('feed', $feed);
	}

	/**
	 *	Get the feed via rest call.
	 */
	public function getRestFeed ( $feed_type = 'all', $page = 1 ) {
		$user_id = Auth::user()->id;
		$paginate = 12;  // Default number of item to fetch
		
		$feed_types = array( 'all', 'posts', 'reposts' );

		// Make sure we have appropriate feed type...
		if ( in_array( $feed_type, $feed_types ) ) {
			// Fetch the feed based on given params.
			$feed = $this->feed->find( $user_id, $paginate, $page, $feed_type, true );
			return Response::json(
				array( 'feed' => $feed ),
				200
			);
		} else {
			return Response::json(
				array( 'error' => true ),
				200
				);
		}
		
	}

}