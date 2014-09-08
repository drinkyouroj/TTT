<?php
namespace AppStorage\Photo;

interface PhotoRepository {

	public function search($keyword, $page);

	public function single($photo_id);

	public function filter($url, $process);

	public function delete($post_id);

}