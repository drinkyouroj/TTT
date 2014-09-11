<?php
namespace AppStorage\Photo;

use App,		//For env detection
	Config,		//For getting S3 Bucket configs
	Validator,	//For validating input
	Exception,	//For when process fails.
	Instagraph,	//We'll do the photo filter/processing in here for now.
	Aws\S3\S3Client//Send to S3 Bucket (datastore for CloudFront)
	;

//Note: we may want to use a csrf tokens or other methods to limit the way this controller is accessed.

class FlickrPhotoRepository implements PhotoRepository {

	public function __construct () {

		//Flickr stuff
		$this->api_key = Config::get('flickr.key');
		$this->secret = Config::get('flickr.secret');
		$this->url = "https://api.flickr.com/services/rest/?";//Note, SSL is important stuff

		//CDN upload
		$this->cdn_config = Config::get('app.cdn_upload');

		//Instagraph stuff
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

	//Grabs a number of images from the flickr API.
	public function search($keyword, $page = 1) {
		$params = array(
			'api_key' => $this->api_key,
			'method' => 'flickr.photos.search',
			'format'	=> 'php_serial',
			'text' => $keyword,//This one we'll have to think about a bit, but it shouldn't be too hard.
			'safe_search' => '2',
			'license' => '4,5,7,8',//This one can be CSVed
			'per_page' => 30,
			'sort' => 'relevance',
			'page' => $page,
		);
		
		$encoded_params = array();
		
		foreach ($params as $k => $v){
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		
		$res = curl_init($this->url.implode('&', $encoded_params));
		
		curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
		
		return $response = curl_exec($res);
	}

	//Grabs a single image from the flickr api.
	public function single($photo_id) {
		$params = array(
			'api_key'	=> $this->api_key,
			'method'	=> 'flickr.photos.getSizes',
			'photo_id'	=> $id,
			'format'	=> 'php_serial',
		);
		
		$encoded_params = array();
		
		foreach ($params as $k => $v){
			$encoded_params[] = urlencode($k).'='.urlencode($v);
		}
		
		$res = curl_init($this->url.implode('&', $encoded_params));
		
		curl_setopt($res, CURLOPT_RETURNTRANSFER, true);
		
		return $response = curl_exec($res);
	}


	//Realistically, we would like to move this to another repo, but we're not there yet.
	public function filter($url, $process = 'nofilter') {
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
				return false;
			}
			
			//fuck using titles, let's just use the time stamp as the source of unique.
			$md5_title = date('Ymdhis').md5(date('Ymdhis').rand()); //Titles Must be unique at all times, but let's add the "rand" just to be sure.
			
			$file_name = $md5_title.'.jpg';
			//Let's grab the images (if no filter, it'll just return after grabbing the file.)
			if($process == 'nofilter'){
				
				$file_path = public_path().'/uploads/final_images/'.$file_name;
				
				file_put_contents($file_path , fopen($url, 'r'));
				
				if( $this->cdn_config ) {
					//CDN Upload action.
					self::cdn_upload($file_path, $file_name);
				}

				//just return the damn thing as no filter
				return $file_name;
				
			} else {
				//to be processed for other filters
				file_put_contents(public_path().'/uploads/orig_images/'.$file_name , fopen($url, 'r'));
			}
			

			$insta = new Instagraph;
			//process the image as an Instagraph Object first
			try
			{	
				$instagraph = $insta->setInput(public_path().'/uploads/orig_images/'.$md5_title.'.jpg', public_path().'/uploads/factory_images/'.$md5_title.'.jpg');
			}
			catch (Exception $e) {
				return $e->getMessage();
			}
			
			//processed stuff has a different file name
			$file_name = $md5_title.'_'.$process.'.jpg';
			$final_path = public_path().'/uploads/final_images/'.$file_name;

			$insta->setOutput($final_path);
			$insta->process($process);

			if( $this->cdn_config ) {
				//CDN Upload.. Maybe put this into a queue later if things don't run so good.
				self::cdn_upload($final_path, $file_name);
			}
			
			return $md5_title.'_'.$process.'.jpg';
			
		} else {
			//validation failure.
			return false;
		}
	}

	public function cdn_upload($full_path, $file_name) {

		$bucket = Config::get('aws.cdn_bucket');
		$client = S3Client::factory(Config::get('aws.s3'));
		
		$client->putObject(
				array(
					'Bucket'	=> $bucket,
					'Key'		=> $file_name,
					'SourceFile'=> $full_path
				)
			);

		$client->waitUntil('ObjectExists', array(
					'Bucket'	=> $bucket,
					'Key'		=> $file_name
				)
			);

		return true;
	}

	public function delete($post_id) {

	}

}