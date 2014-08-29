<?php


class AuthRestTest extends TestCase {

	/**
	 *	Test that we have the default user seeded.
	 */
	public function testDefaultUserSeeded () {
		$this->assertEquals( Auth::user()->username, 'seededUser' );
		$this->assertEquals( Auth::user()->id, 121314 );
	}

	/**
	 * Test that the Favorite functionality works! Runs 2 tests to make sure
	 * that the second call returns the opposite of the first (due to toggle).
	 *
	 * Note! This method requires there to be a post with id == 1
	 */
	public function testFavorite () {
		try {
			$response = $this->call('GET', 'rest/favorites/1');
			$content = json_decode( $response->getContent() );
			return $content->result;
		} catch ( Exception $e ) {
			$this->fail( 'Failed at Get:rest/favorites/1  '.$e);
		}
	}
		/**
		 *	@depends testFavorite
		 */
		public function testFavorite2 ( $firstResult ) {
			try {
				$response = $this->call('GET', 'rest/favorites/1');
				$content = json_decode( $response->getContent() );
				if ( $content->result == 'success' ) {
					$this->assertEquals( $firstResult, 'deleted' );
				} else {
					$this->assertEquals( $content->result, 'deleted' );
					$this->assertEquals( $firstResult, 'success' );
				}
			} catch ( Exception $e ) {
				$this->fail( 'Failed at Get:rest/favorites/1  '.$e);
			}
		}

	/**
	 *	Test that the Repost functionality works via rest api. Also runs two
	 *	tests to ensure the second call does the opposite of the first.
	 *
	 *	Note! This method requires there to be a post with id == 1
	 */
	public function testRepost () {
		try {
			$response = $this->call('GET', 'rest/reposts/1');
			$content = json_decode( $response->getContent() );
			return $content->result;
		} catch ( Exception $e ) {
			$this->fail( 'Failed at Get:rest/reposts/1  '.$e);
		}
	}
		/**
		 *	@depends testRepost
		 */
		public function testRepost2 ( $firstResult ) {
			try {
				$response = $this->call('GET', 'rest/reposts/1');
				$content = json_decode( $response->getContent() );
				if ( $content->result == 'success' ) {
					$this->assertEquals( $firstResult, 'deleted' );
				} else {
					$this->assertEquals( $content->result, 'deleted' );
					$this->assertEquals( $firstResult, 'success' );
				}
			} catch ( Exception $e ) {
				$this->fail( 'Failed at Get:rest/reposts/1  '.$e);
			}
		}

	/**
	 *	Test that the Follow functionality works via rest api. Also runs two
	 *	tests to ensure the second call does the opposite of the first.
	 *
	 *	Note! This method requires there to be a user with id == 1
	 */
	public function testFollow () {
		try {
			$response = $this->call('GET', 'rest/follows/1');
			$content = json_decode( $response->getContent() );
			return $content->result;
		} catch ( Exception $e ) {
			$this->fail( 'Failed at Get:rest/follows/1  '.$e);
		}
	}
		/**
		 *	@depends testFollow
		 */
		public function testFollow2 ( $firstResult ) {
			try {
				$response = $this->call('GET', 'rest/follows/1');
				$content = json_decode( $response->getContent() );
				if ( $content->result == 'success' ) {
					$this->assertEquals( $firstResult, 'deleted', 'Expected second response to be \'deleted\'.' );
				} else {
					$this->assertEquals( $content->result, 'deleted', 'Expected first response to be \'deleted\'.' );
					$this->assertEquals( $firstResult, 'success', 'Expected second response to be \'success\'.' );
				}
			} catch ( Exception $e ) {
				$this->fail( 'Failed at Get:rest/follows/1  '.$e);
			}
		}

	/**
	 *	Test the getFollowers functionality. Only tests the response contains
	 *	the followers field.
	 *
	 *	Note! This method requires there to be a user with id == 1
	 */
	public function testGetFollowers () {
		$response = $this->call('GET', 'rest/followers/1');
		$this->assertResponseOk();
		$content = json_decode( $response->getContent() );
		$this->assertEquals( 'array', gettype( $content->followers ), 'Expected response to contain array of followers.' );
	}

	/**
	 *	Test the getFollowing functionality. Only tests the response contains
	 *	the following field.
	 *
	 *	Note! This method requires there to be a user with id == 1
	 */
	public function testGetFollowing () {
		$response = $this->call('GET', 'rest/following/1');
		$this->assertResponseOk();
		$content = json_decode( $response->getContent() );
		$this->assertEquals( 'array', gettype( $content->following ), 'Expected response to contain array of following.' );
	}

	/**
	 *	Test that the getComments fails for user that does not own the comment
	 *
	 *	Note! This method is using comment with id == 1
	 */
	public function testGetCommentsExpectedFail () {
		$response = $this->call('GET', 'rest/comments/1');
		$this->assertResponseOk();
		$content = json_decode( $response->getContent() );
		$this->assertEquals( 'failed', $content->result, 'User should not have been able to unpublish this comment!.' );
	}

}