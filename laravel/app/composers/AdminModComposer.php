<?php
//use App, Auth, Session, Request;

class AdminModComposer {
	public function compose($view) {
		// Admin/Moderator
		$is_mod = Session::get('mod');
		$is_admin = Session::get('admin');
		if ( $is_mod || $is_admin ) {

			$flagged = App::make('AppStorage\FlaggedContent\FlaggedContentRepository');
			$users_rep = App::make('AppStorage\User\UserRepository');
			$post_rep = App::make('AppStorage\Post\PostRepository');
			$weekly_digest_rep = App::make('AppStorage\WeeklyDigest\WeeklyDigestRepository');
			// Include the flagged content
			$flagged_post_content = $flagged->getFlaggedOfType( 'post' );
			$flagged_comment_content = $flagged->getFlaggedOfType( 'comment' );
			// Include some stats
			$num_users = $users_rep->getUserCount();
			$num_confirmed_users = $users_rep->getVerifiedUserCount();
			$num_users_created_today = $users_rep->getUserCreatedTodayCount();
			$num_published_posts = $post_rep->getPublishedCount();
			$num_published_posts_today = $post_rep->getPublishedTodayCount();
			$num_drafts_today = $post_rep->getDraftsTodayCount();
			// Include weekly digest
			$digest = $weekly_digest_rep->getWeeklyDigest();

			// Check if we are on user and/or post page (additional functionalities will be given)
			$seg = Request::segment(1);
			if( $seg == 'profile') {
				$view->with( 'is_profile_page', true );
			} else if ( $seg == 'posts') {
				$view->with( 'is_post_page', true );
			} else if ( $seg == 'categories' ) {
				$view->with( 'is_categories_page', true );
			}
			$view->with( 'flagged_post_content', $flagged_post_content )
				 ->with( 'flagged_comment_content', $flagged_comment_content )
				 ->with( 'num_users', $num_users )
				 ->with( 'num_confirmed_users', $num_confirmed_users )
				 ->with( 'num_users_created_today', $num_users_created_today )
				 ->with( 'num_published_posts', $num_published_posts )
				 ->with( 'num_published_posts_today', $num_published_posts_today )
				 ->with( 'num_drafts_today', $num_drafts_today )
				 ->with( 'weekly_digest', $digest );
		}
	}
}
