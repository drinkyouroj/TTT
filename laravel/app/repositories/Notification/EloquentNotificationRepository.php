<?php namespace AppStorage\Notification;

use Notification,DB, Request;

class EloquentNotificationRepository implements NotificationRepository {

	public function __construct(Notification $notification)
	{
		$this->comment = $notification;
	}
	
}