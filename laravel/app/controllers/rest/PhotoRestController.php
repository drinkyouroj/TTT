<?php
class PhotoRestController extends \BaseController {

	public function __construct() {
		Validator::extend('Processes', function($attribute, $value, $parameters)
		{
		    if(!is_null($value)) {
		    	if(in_array($value, $parameters)) {//needle in a parameters haystack
		    		return true;
		    	} else {
		    		return false;
		    	}
		    } else {
		    	return false;
		    }
		});
	}
	
	public function index()
	{
		$url = urldecode(Input::get('url'));//decode the URL first
		$process = Input::get('process');
		
		$validator = Validator::make(
			array(
				'url' => $url,
				'process' => $process
			),
			array(
				'url' => 'required|url',
				'process' => 'Processes:Gotham,Toaster,Nashville,Lomo,Kelvin,TiltShift,nofilter'
			)
		);
		
		if($validator->passes()) {
			//Before grabbing the image, let's make sure that we didn't get fooled into downloading something terribly big or not from flickr.
			
			$headers = get_headers($url, 1);
			if($headers['Content-Length'] >= 5242880) {  //Bigger than 5mb
				return Response::json(
					array('File Greater than 5 megabyte'),
					500
				);
			}
			
			//fuck using titles, let's just use the time stamp as the source of unique.
			$md5_title = md5(date('Ymdhis').rand()); //Titles Must be unique at all times, but let's add the "rand" just to be sure.
			
			//Let's grab the images (if no filter, it'll just return after grabbing the file.)
			if($process == 'nofilter'){
					
				$file_path = public_path().'/uploads/final_images/'.$md5_title.'.jpg';
				
				file_put_contents($file_path , fopen($url, 'r'));
				
				//If this is the web server upload this content to the cdn.
				if(App::environment() == 'web') {
					$file = OpenCloud::upload('Images', $file_path, $md5_title.'.jpg');
				}
				
				//just return the damn thing as no filter
				return Response::json(
					$md5_title.'.jpg',//will need to send back the image name for the image.
					200
				);
				
			} else {
				file_put_contents(public_path().'/uploads/orig_images/'.$md5_title.'.jpg' , fopen($url, 'r'));
			}
			
			$insta = new Instagraph;
			
			//process the image as an Instagraph Object first
			try
			{	
				$instagraph = $insta->setInput(public_path().'/uploads/orig_images/'.$md5_title.'.jpg', public_path().'/uploads/factory_images/'.$md5_title.'.jpg');
			}
			catch (Exception $e) {
				return Response::json(
					$e->getMessage(),//Send back the Exception Message
					200
				);
			}
			
			$insta->setOutput(public_path().'/uploads/final_images/'.$md5_title.'_'.$process.'.jpg');
			$insta->process($process);
			
			if( App::environment() == 'web') {
				$file = OpenCloud::upload('Images', public_path().'/uploads/final_images/'.$md5_title.'_'.$process.'.jpg', $md5_title.'_'.$process.'.jpg');
			}
			
			return Response::json(
				$md5_title.'_'.$process.'.jpg',//will need to send back the image name for the image.
				200
			);
			
		} else {
			return Response::json(
				'Validation Failed'.$url,
				200
			);
		}
	}
	
	//POST info
	public function store()
	{		
		
	}
	
	//GET info
	public function show()
	{
		
	}
	
	
}
