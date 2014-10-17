<?php
namespace AppStorage\FeaturedUser;

interface FeaturedUserRepository {

		//Instance
	public function instance();

	public function create($user_id, $excerpt);

	public function find();

	public function delete($id);

}