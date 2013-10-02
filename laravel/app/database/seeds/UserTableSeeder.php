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
		
    }
	
}