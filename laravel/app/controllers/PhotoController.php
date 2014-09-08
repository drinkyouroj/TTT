<?php
class PhotoController extends BaseController {

	public function __construct(PhotoRepository $photo) {
		$this->photo = $photo;
	}

	//Does a photo Search on Flickr and returns an image set.
	public function getPhotoSearch() {
		$page = Input::get( 'page' );
		$text = Input::get( 'text' );

		//Search for the photos.
		$response = $this->photo->search($text, $page);

		if(count($response)) {
			return Response::json(
				unserialize($response),//kind of stupid that we're doing this json response thing by decoding and encoding again...
				200//response is OK!
			);
		} else {
			return Response::json(
				'Error',
				404//response is NOT OK!
			);
		}
	}

	//Grabs the specific image and places it in the correct location for filter application.
	public function getPhoto($photo_id) {
		//Grab a single photo info.  Mostly for the license info.
		$response = $this->photo->single($photo_id);

		if(count($response)) {
			return Response::json(
				unserialize($response),
				200//response is OK!
			);
		} else {
			return Response::json(
				'Error',
				404//response is NOT OK!
			);
		}
	}

	//Processes the photo with filters
	public function getProcessPhoto() {
		$url = urldecode(Input::get('url'));//decode the URL first
		$process = Input::get('process');
		$response = $this->photo->filter($url, $process);

		//We'll need to work on this a bit, but good enough for now.
		return Response::json(
				$response,
				200
			);
	}

	public function getProfilePhoto() {
		
	}

}