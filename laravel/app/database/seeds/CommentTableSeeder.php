<?php
 
class CommentTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('comments')->delete();
 
        $com = new Comment;
		
		//First com
        $com->user_id = 2;
        $com->post_id = 1;
        $com->body = 'your post sucks';
		$com->up = 1;
        $com->down = 2;
		$com->published = 1;
		$com->save();
		
		$com = new Comment;
		
		//Second com
        $com->user_id = 2;
        $com->post_id = 1;
        $com->body = 'your post sucks again';
		$com->up = 1;
        $com->down = 2;
		$com->published = 1;
		$com->save();
		
    }
	
}