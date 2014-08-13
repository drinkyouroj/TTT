<?php
/**
 * This is basically a wrapper around the Flickr API
 * 	The license is currently set to no attribution, but we'll figure that out soon.
 */

class FlickrRestController extends \BaseController {

	/**
	 * API Keys and stuff
	 */
	private static $api_key = "199d51aaaeb7c32a953f13ec958358c9";
	private static $secret = "d35085a81eb05474";
	private static $url = "https://api.flickr.com/services/rest/?";

	/**
	 * Display a listing of pictures based on a tag based search.
	 *
	 * @return Response
	 */
	public function index()
	{
		$page = Input::get('page');
		
		if(!$page) {//As in if no page is defined.
			$page = 1;
		}
		
		//
		$params = array(
			'api_key' => self::$api_key,
			'method' => 'flickr.photos.search',
			'format'	=> 'php_serial',
			'text' => Input::get( 'text' ),//This one we'll have to think about a bit, but it shouldn't be too hard.
			'safe_search' => '2',
			'license' => '1,2,3,4,5,6,7,8',//This one can be CSVed
			'per_page' => 30,
			'page' => $page
		);
		
		$encoded_params = array();
		
		foreach ($params as $k => $v){
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		
		$res = curl_init(self::$url.implode('&', $encoded_params));
		
		curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($res);
		
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

	/**
	 * Display the specified image from Flickr
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
		$params = array(
			'api_key'	=> self::$api_key,
			'method'	=> 'flickr.photos.getSizes',
			'photo_id'	=> $id,
			'format'	=> 'php_serial',
		);
		
		$encoded_params = array();
		
		foreach ($params as $k => $v){
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		
		$res = curl_init(self::$url.implode('&', $encoded_params));
		
		curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
		
		$response = curl_exec($res);
		
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

}