<?php
namespace AppStorage\User;

interface UserRepository {

	public function instance();

//Create
	public function create($data, $flag);

//Read
	public function exists($id);
	public function usernameExists( $username );

	public function find($id);
	
	/**
	 *	Find a user by their image. Used for image sweep command!
	 */
	public function findByImage( $image, $with_trashed );

	public function all($emails_only);
	public function allByIds( $user_ids );

//Update
	public function update($input);
	
	public function updatePassword($user, $password);

	public function updateEmail($confirm_code);
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
	public function restoreByConfirmation($restore_confirm);

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
	 * Reset the password for a user who has an e-mail address.
	 * @return an array with the same return from resetPassword.
	 */
	public function forgotPassword($email,$username);

	/**
	 *	Get the number of usernames attached to the given email.
	 */
	public function usernamesPerEmailCount( $email );


	public function getUserCount();
	public function getVerifiedUserCount();
	public function getUserCreatedTodayCount();
}