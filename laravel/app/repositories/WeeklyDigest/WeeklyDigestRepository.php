<?php
namespace AppStorage\WeeklyDigest;

interface WeeklyDigestRepository {

	public function instance();

	public function create( $data );
	public function delete( $id );

	public function all( $sent );

	public function addPost( $alias, $position );
	public function incrementViews( $digest_id, $alias );

}