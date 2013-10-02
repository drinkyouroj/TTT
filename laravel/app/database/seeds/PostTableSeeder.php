<?php
 
class PostTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('posts')->delete();
 
        $post = new Post;
		
		//First post
		$post->user_id = 1;
        $post->title = 'test';
        $post->alias = 'test';
        $post->tagline = 'ryuhei';
		$post->category = 1;
        $post->image = 1;
		$post->body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In viverra aliquam malesuada. Phasellus sit amet magna ac ante fringilla pretium vel sed neque. Donec gravida in magna nec dapibus. Morbi interdum purus eu ligula accumsan, eu luctus massa ultricies. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris purus velit, elementum sit amet tortor vel, placerat volutpat augue. Maecenas elementum lorem sed euismod egestas. Nullam ac purus urna.';
		$post->save();
		
    }
	
}