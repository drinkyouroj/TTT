<?php
namespace AppStorage\Featured;

interface FeaturedRepository {

		//Instance
	public function instance();

	public function create($post_id);


	public function find($paginate, $page, $rest);

	public function delete($post_id);

}