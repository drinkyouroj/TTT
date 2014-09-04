<?php
namespace AppStorage\User;

interface UserRepository {

	public function instance();

//Create
	public function create($data, $flag);

//Read
	public function exists($id);

	public function find($id);

	public function all();

//Update
	public function update($input);
	
//Delete
	public function delete($id);

//Credentials
	public function login($data);

	public function logout();

	public function check($id);

//Confirms a user with an e-mail.
	public function confirm($data);

//Registers an unregistered user.
	public function register($data);
	
}