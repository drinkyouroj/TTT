<?php
/**
 *	This command should sweep final_images that are not in use!
 *		- Post Images: multiple images may be uploaded during post creation
 *		- Avatars: everytime an avatar is changed the old one remains
 *	This script matches files against both the post images and the avatar images
 *	
 *
 *	TODO: Implement the removal of files from s3! Mainly, the removeImageFromS3() method.
 *
 *
 */


use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class ImageUploadSweepCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'imageupload:sweep';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Compares uploaded images to database, removing images that are not in use. We are not a hosting service after all.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->post = App::make('AppStorage\Post\PostRepository');
		$this->user = App::make('AppStorage\User\UserRepository');
		// Path to local uploaded images. May need to change??
		$this->localImageDirectory = getcwd().'/public/uploads/final_images/';
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire() {

		// TODO: Fetch images (image filenames) from s3 (or whatever storage is being used)
		$images = $this->fetchImages();
		
		foreach ($images as $image) {
			$is_post_image = $this->post->findByImage( $image, true ) instanceof Post;
			$is_user_avatar = $this->user->findByImage( $image, true ) instanceof User;

			if ( !$is_post_image && !$is_user_avatar ) {
				// The image is NOT attached to ANY post/user --> delete it.
				$this->removeImage( $image );
			} else {
				$this->line('Save: '.$image);
			}

		}

	}

	// Fetches list of images depending on app environment...
	private function fetchImages () {
		
		if ( Config::get('app.cdn_upload') ) {
			// Images are in s3
			$this->line('=== Scanning images stored in s3 ===');
			// TODO: Fetch image filenames from s3
			$images = array();
		} else {
			// Images are on local disk
			$this->line('=== Scanning images stored on local disk ===');
			$images = scandir( $this->localImageDirectory );
		}
		return $images;
	}

	private function removeImage ( $image ) {
		if ( Config::get('app.cdn_upload') ) {
			$this->removeImageFromS3( $image );
		} else {
			$this->removeImageFromLocalDisk( $image );
		}
	}

		private function removeImageFromLocalDisk ( $image ) {

			$file = $this->localImageDirectory.$image;
			if ( is_file( $file ) ) {
				unlink( $file );
				$this->line('Deleted image from local disk: '.$file);
			}
		}

		private function removeImageFromS3 ( $image ) {
			$this->line('TODO: Deleted image from s3: '.$image);
		}

	// /**
	//  * Get the console command arguments.
	//  *
	//  * @return array
	//  */
	// protected function getArguments()
	// {
	// 	return array(
	// 		array('example', InputArgument::REQUIRED, 'An example argument.'),
	// 	);
	// }

	// /**
	//  * Get the console command options.
	//  *
	//  * @return array
	//  */
	// protected function getOptions()
	// {
	// 	return array(
	// 		array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
	// 	);
	// }

}
