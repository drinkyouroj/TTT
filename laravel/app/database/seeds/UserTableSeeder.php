<?php
 
class UserTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('users')->delete();
 
        User::create(array(
            'username' => 'firstuser',
            'password' => Hash::make('first_password'),
            'email' => 'email1@y-designs.com',
            'bio' => 'about me'
        ));
 
        User::create(array(
            'username' => 'seconduser',
            'password' => Hash::make('second_password'),
			'email' => 'email2@y-designs.com',
            'bio' => 'about me'
        ));
    }
 
}