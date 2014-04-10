<?php
namespace TTT\Storage;

use User;
 
class ConfideUserRepository implements UserRepository {

	public function create($input) {
		return User::create($input);
	}
	 
	public function all() {
		return User::all();
	}
	 
	public function find($id) {
		return User::find($id);
	}

//Update
	public function update($input) {
		return User::update($input);
	}
	
//Delete
	public function delete($id) {
		return User::delete($id);
	}
}