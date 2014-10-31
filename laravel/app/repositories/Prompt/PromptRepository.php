<?php
namespace AppStorage\Prompt;

interface PromptRepository {

	public function instance();

	public function create( $data );
	public function delete( $id );

	public function activate( $id );
	public function deactivate( $id );

	public function getAll();

	public function getPromptForSignup();
	public function getPromptForPostInput();

}