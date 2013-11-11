<?php
 
class MessageTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('messages')->delete();
 
        $message = new Message;
		
		//First message
		$message->from_uid = 1;
		$message->to_uid = 2;
        $message->reply_id = 0;//replies to none. not a thread.
		$message->body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In viverra aliquam malesuada. Phasellus sit amet magna ac ante fringilla pretium vel sed neque. Donec gravida in magna nec dapibus. Morbi interdum purus eu ligula accumsan, eu luctus massa ultricies. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris purus velit, elementum sit amet tortor vel, placerat volutpat augue. Maecenas elementum lorem sed euismod egestas. Nullam ac purus urna.';
		$message->save();
		
		//Second message
		$message->from_uid = 2;
		$message->to_uid = 1;
        $message->reply_id = 0;//replies to none. not a thread.
		$message->body = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. In viverra aliquam malesuada. Phasellus sit amet magna ac ante fringilla pretium vel sed neque. Donec gravida in magna nec dapibus. Morbi interdum purus eu ligula accumsan, eu luctus massa ultricies. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos. Mauris purus velit, elementum sit amet tortor vel, placerat volutpat augue. Maecenas elementum lorem sed euismod egestas. Nullam ac purus urna.';
		$message->save();
		
    }
	
}