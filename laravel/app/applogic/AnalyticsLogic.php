<?php namespace AppLogic\AnalyticsLogic;

use Session;

class AnalyticsLogic {
	
	/**
	 *	Store the user session/beginning of user engagement
	 */
	public function createSession ( $session_id, $user ) {
		if ( !self::isAdminOrMod() ) {
			// TODO	
		}
	}

	/**
	 *	Save an engagement/interaction to the current session analytics
	 *
	 *	@param $action: string - one of the following
	 *							'navigate',
	 *							'like', 'unlike',
	 *							'repost', 'unrepost',
	 *							'save', 'unsave',
	 *							'follow', 'unfollow',
	 *							'comment-new', 'comment-delete', 'comment-reply',
	 *							'post-delete', 'post-new', 'post-edit', 'post-read',
	 *							'featured-set',
	 *							'flag-comment', 'flag-post',
	 *							'unflag-comment', 'unflag-post',
	 *							'account-deactivate', 'account-restore', 'account-update-email'
	 *							
	 *	@param $path: string - the uri path only if $action == 'navigate'
	 */
	public function createSessionEngagement ( $action, $path = '' ) {
		if ( !self::isAdminOrMod() ) {
			$session_id = Session::getId();
			// TODO	
			if ( $action == 'navigate' ) {
				$path = '/'.$path;
			}
		}
	}

		// Filter so that we dont track admin/mod behavior
		private function isAdminOrMod () {
			return Session::get('mod') || Session::get('admin');
		}
}