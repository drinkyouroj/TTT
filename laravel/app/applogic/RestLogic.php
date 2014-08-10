<?php namespace AppLogic\RestLogic;

use App, 
	AppStorage\Post\PostRepository,
	AppStorage\Category\CategoryRespository,
	AppStorage\Notification\NotificationRepository,


	;

/**
 * This class holds many of the business logic for the Rest Controllers
 */
class RestLogic {

	public function __construct() {
		//Below sucks compared to how the interface is usually implemented, but its having issues so we're doing it this way.
		$this->post = App::make('AppStorage\Post\PostRepository');
		$this->notification = App::make('AppStorage\Notification\NotificationRepository');

	}

	/**
	* Notification Section
	*/


}
	