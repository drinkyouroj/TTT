<?php
 
class UserTableSeeder extends Seeder {
 
    public function run()
    {
        DB::table('users')->delete();
 
 
 		$user = new User;
				
		//First user
        $user->username = 'nobody';
        $user->email = 'nobody@twothousandtimes.com';
        $user->password = 'nobody';
		$user->password_confirmation = 'nobody';
        $user->confirmed = 1;
		$user->save();
		
		unset($user);
 
 
        $user = new User;
				
		//First user
        $user->username = 'ryuhei';
        $user->email = 'ryuhei@y-designs.com';
        $user->password = 'ryuhei';
		$user->password_confirmation = 'ryuhei';
        $user->confirmed = 1;
		$user->save();
		
		unset($user);
		
		$max_user = new User;
		
		//First user
        $max_user->username = 'max';
        $max_user->email = 'max@y-designs.com';
        $max_user->password = 'maximus';
		$max_user->password_confirmation = 'max';
        $max_user->confirmed = 1;
		$max_user->save();
		
		unset($user);
		
		//Below are the 2 user Roles for now.
		$nobody = new Role;
		$nobody->name = 'Nobody';
		$nobody->save();
		
		$admin = new Role;
		$admin->name = 'Admin';
		$admin->save();
		
		//Below are set of permissions for TTT
		$setFeatured = new Permission;
		$setFeatured->name = 'set_featured';
		$setFeatured->display_name = 'Set/unset Featured';
		$setFeatured->save();
		
		$manageUsers = new Permission;
		$manageUsers->name = 'manage_users';
		$manageUsers->display_name = 'Manage Users';
		$manageUsers->save();
		
		//Attach the permissions to the role
		$admin->attachPermission($setFeatured);
		$admin->attachPermission($manageUsers);
		
		//Let's give the admins their rights
		$ryuhei = User::where('username', '=', 'ryuhei')
				->first();
		
		$max = User::where('username', '=', 'max')
				->first();
		
		$ryuhei->attachRole($admin);
		
    }
	
}