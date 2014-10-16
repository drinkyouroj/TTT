<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class PostCreatedCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'post:created';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Updates the published date for id lower than 290.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
		$posts = Post::where('id','<',290)->get();
		foreach($posts as $post) {
			$post->published_at = $post->created_at;
			$post->save();
		}
	}


	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
            array('page', InputOption::VALUE_OPTIONAL, 'Nope'),

        );
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('mock', null, InputOption::VALUE_OPTIONAL, 'An example option.', true),
		);
	}

}
