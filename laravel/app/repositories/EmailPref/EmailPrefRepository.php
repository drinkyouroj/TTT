<?php
namespace AppStorage\EmailPref;

interface EmailPrefRepository {

	//Instance
	public function instance();

	public function create($data);

	public function find($user_id);

	public function exists($user_id, $count=false);

	//Create
	public function update($data);

}