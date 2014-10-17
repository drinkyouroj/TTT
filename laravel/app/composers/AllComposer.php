<?php
//use \App, Auth, File, NotificationLogic, Carbon\Carbon;

class AllComposer {
	public function compose($view) {
		if(!Auth::guest()) {
			$user = Auth::user();
			// ===================== ITEMS FOR THE SIDEBAR =====================
			$favRep = App::make('AppStorage\Favorite\FavoriteRepository');
			$saves = $favRep->allByUserId( $user->id, 6 );
			//The new Mongo notifications
			$compiled = NotificationLogic::top( $user->id );
			// Count of how many unread notifications the user has
			$notification_count = NotificationLogic::getUnreadCount( $user->id );

			//Unfortunately, we'll have to do this for now.
			$notification_ids = array();
			foreach($compiled as $k => $nots) {
				$notification_ids[$k] = $nots->_id;
			}
			
			$user_image = $user->image ? $user->image : false;

			$view->with('notifications', $compiled)
				 ->with('notification_count', $notification_count)
				 ->with('saves', $saves)
				 ->with('notifications_ids', $notification_ids)
				 ->with('user_image', $user_image);
				 
		}
		
		$contents = File::get(base_path().'/gitversion');
		$version =str_replace("\n", "", $contents);//gotta get rid of the returns.
		$view->with('version', $version);

		/*
		$expiresAt = Carbon::now()->addMinutes(10);
		if(Cache::has('viewtimes')) {
			$viewtimes = Cache::get('viewtimes');
			Cache::put('viewtimes', 1, $expiresAt);
		} else {
			$viewtimes = 1;
			Cache::put('viewtimes', 1, $expiresAt);
		}
		$view->with('times',$viewtimes);
		*/
	}
}
