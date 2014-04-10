<?php
namespace TTT\Storage\User;

interface UserRepository {

//Create
	public function create($input);

//Read
	public function all();
	
	public function find($id);

//Update
	public function update($input);
	
//Delete
	public function delete($id);
	
}
