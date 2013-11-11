<?php
 
class PostTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('posts')->delete();
 
        $post = new Post;
		
		//First post
		$post->user_id = 1;
        $post->title = 'test';
        $post->alias = 'test1';
        $post->tagline_1 = 'bathroom';
		$post->tagline_2 = 'gross';
		$post->tagline_3 = 'things';
		$post->category = 1;
        $post->image = 1;
		$post->featured = 1;
		$post->body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In viverra aliquam malesuada. Phasellus sit amet magna ac ante fringilla pretium vel sed neque. Donec gravida in magna nec dapibus. Morbi interdum purus eu ligula accumsan, eu luctus massa ultricies. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris purus velit, elementum sit amet tortor vel, placerat volutpat augue. Maecenas elementum lorem sed euismod egestas. Nullam ac purus urna.';
		$post->save();
		
		 $post = new Post;
		
		//First post
		$post->user_id = 1;
        $post->title = 'test';
        $post->alias = 'test2';
        $post->tagline_1 = 'bathroom';
		$post->tagline_2 = 'gross';
		$post->tagline_3 = 'things';
		$post->category = 1;
        $post->image = 1;
		$post->featured = 1;
		$post->body = '2nd Lorem ipsum dolor sit amet, consectetur adipiscing elit. In viverra aliquam malesuada. Phasellus sit amet magna ac ante fringilla pretium vel sed neque. Donec gravida in magna nec dapibus. Morbi interdum purus eu ligula accumsan, eu luctus massa ultricies. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris purus velit, elementum sit amet tortor vel, placerat volutpat augue. Maecenas elementum lorem sed euismod egestas. Nullam ac purus urna.';
		$post->save();
		
		 $post = new Post;
		
		//First post
		$post->user_id = 1;
        $post->title = 'test';
        $post->alias = 'test3';
        $post->tagline_1 = 'bathroom';
		$post->tagline_2 = 'gross';
		$post->tagline_3 = 'things';
		$post->category = 1;
        $post->image = 1;
		$post->featured = 1;
		$post->body = '3rd Lorem ipsum dolor sit amet, consectetur adipiscing elit. In viverra aliquam malesuada. Phasellus sit amet magna ac ante fringilla pretium vel sed neque. Donec gravida in magna nec dapibus. Morbi interdum purus eu ligula accumsan, eu luctus massa ultricies. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris purus velit, elementum sit amet tortor vel, placerat volutpat augue. Maecenas elementum lorem sed euismod egestas. Nullam ac purus urna.';
		$post->save();
    }
	
}