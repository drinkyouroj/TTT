<?php
namespace AppStorage\Email;

interface EmailRepository {

	//Instance
	public function instance();

	//Create
	public function create($data);

	public function test($data);
}