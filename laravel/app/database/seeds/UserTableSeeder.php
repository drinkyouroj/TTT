<?php
 
class UserTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('users')->delete();
 
        $user = new User;
		
		//First user
        $user->username = 'ryuhei';
        $user->email = 'ryuhei@y-designs.com';
        $user->password = 'ryuhei';
		$user->password_confirmation = 'ryuhei';
        $user->confirmed = 1;
		$user->save();
		
		$user = new User;
		
		//First user
        $user->username = 'max';
        $user->email = 'max@y-designs.com';
        $user->password = 'max';
		$user->password_confirmation = 'max';
        $user->confirmed = 1;
		$user->save();
		
    }
	
}