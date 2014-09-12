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
	
	/**
	 *	Soft Delete a user. This method has the cascading effect
	 *	of soft deleting all posts authored by this user, as well
	 *	as unpublishing all comments by this user.
	 */
	public function delete($id);

	/**
	 *	Restore a previously deleted user's account. This also
	 *	restores the user's posts and re-publishes their comments.
	 */
	public function restore($id);

//Credentials
	public function login($data);

	public function logout();

	public function check($id);

//Confirms a user with an e-mail.
	public function confirm($data);

//Registers an unregistered user.
	public function register($data);

	public function ban($id);

	public function unban($id);

	/**
	 *	Reset the users password.
	 * @return an array containing the user and new password
	 */
	public function resetPassword($id);

	/**
	 *	Get the number of usernames attached to the given email.
	 */
	public function usernamesPerEmailCount( $email );

	public function getUserCount();
	public function getConfirmedUserCount();
	public function getUserCreatedTodayCount();
}