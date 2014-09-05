<?php
namespace AppStorage\FlaggedContent;

interface FlaggedContentRepository {

	public function instance();
	
	public function create ( $type, $content_id );

	public function delete ( $id );

	public function getFlaggedOfType ( $type );

}