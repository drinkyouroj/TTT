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
        $user->password = 'Abba666!!!';
		$user->password_confirmation = 'Abba666!!!';
        $user->confirmed = 1;
		$user->save();
		
		unset($user);
		
		$user = new User;
		
		//First user
        $user->username = 'max';
        $user->email = 'max@y-designs.com';
        $user->password = 'Abba666!!!';
		$user->password_confirmation = 'Abba666!!!';
        $user->confirmed = 1;
		$user->save();
		
		unset($user);
		
		$user = new User;
		
		//First user
        $user->username = 'Eells';
        $user->email = 'eells@y-designs.com';
        $user->password = 'eik5gsih';
		$user->password_confirmation = 'eik5gsih';
        $user->confirmed = 1;
		$user->save();
		
		unset($user);
		
		$user = new User;
		
		//First user
        $user->username = 'Seabass';
        $user->email = 'seabass@y-designs.com';
        $user->password = '1ualhy4n';
		$user->password_confirmation = '1ualhy4n';
        $user->confirmed = 1;
		$user->save();
		
		unset($user);
		
		
		//Below are the 3 user Roles for now.
		$nobody = new Role;
		$nobody->name = 'Nobody';
		$nobody->save();
		
		$admin = new Role;
		$admin->name = 'Admin';
		$admin->save();
		
		$mod = new Role;
		$mod->name = 'Moderator';
		$mod->save();
		
		//Let's give the admins/mods their rights
		$ryuhei = User::where('username', 'ryuhei')
				->first();
		
		$max = User::where('username', 'max')
				->first();
		
		$eells = User::where('username', 'Eells')
				->first();
		$seabass = User::where('username', 'Seabass')
				->first();
		
		$eells->attachRole( $admin );
		$eells->attachRole( $mod );
		
		$seabass->attachRole( $admin );
		$seabass->attachRole( $mod );
		
		$ryuhei->attachRole( $admin );
		$ryuhei->attachRole( $mod );
		$max->attachRole( $mod );
		
		
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
		$admin->perms()->sync(array($setFeatured->id,$manageUsers->id));
		$mod->perms()->sync(array($manageUsers->id));
		
    }
	
}