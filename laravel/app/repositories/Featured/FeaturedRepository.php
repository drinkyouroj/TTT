<?php
namespace AppStorage\Featured;

interface FeaturedRepository {

		//Instance
	public function instance();

	public function create($post_id);

	public function find($paginate, $page, $rest, $front);

	public function findFront();

	public function random();

	public function findByPostId($post_id);

	public function delete($post_id);

	public function swapFeaturedItems ( $post_id, $target_position );
}