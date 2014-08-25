<?php
class MyProfileController extends BaseController {

	public function __construct(
							NotificationRepository $not,
							FollowRepository $follow
							) {
		$this->not = $not;
		$this->follow = $follow;
	}

}