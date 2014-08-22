<?php

class RestTest extends TestCase {
	
	/**
	 * A basic functional test example.
	 */
	public function testBasicExample () {
		$crawler = $this->client->request('GET', '/');
		$this->assertTrue($this->client->getResponse()->isOk());
	}

	/**
	 *	Test that we have the default user seeded.
	 */
	public function testDefaultUserSeeded () {
		$this->assertEquals( Auth::user()->username, 'seededUser' );
	}

	/**
	 *	Test GET:rest/categories/all
	 */
	public function testGetCategoriesAll () {
		// $response = $this->call($method, $uri, $parameters, $files, $server, $content);
		try {
			$response = $this->call('GET', 'rest/categories/all');
		} catch ( Exception $e ) {
			$this->fail( 'Failed to load resource at Get:rest/categories/all\n'.$e );
		}
	}

	/**
	 *	Test GET:rest/categories/all/viewed
	 *
	 *	@todo Check that the response is in sorted order based on view count.
	 *	@todo Also add the remaining category sorting methods: recent, popular(default), discussed, longest, shortest.
	 */
	public function testGetCategoriesAllViewed () {
		try {
			$response = $this->call('GET', 'rest/categories/all/viewed');
			// TODO: make sure sorted by views...
		} catch ( Exception $e ) {
			$this->fail( 'Failed to load resource at Get:rest/categories/all/viewed\n'.$e );
		}
	}

	/**
	 *	Test GET:rest/profile
	 */
	public function testGetProfile () {
		try {
			$response = $this->call('GET', 'rest/profile');
		} catch ( Exception $e ) {
			$this->fail( 'Failed to load resource at Get:rest/profile:\n'.$e );
		}
	}

	/**
	 *	Test GET:rest/featured
	 */
	public function testGetFeatured () {
		try {
			$response = $this->call('GET', 'rest/featured');
		} catch ( Exception $e ) {
			$this->fail( 'Failed to load resource at Get:rest/profile:\n'.$e );
		}
	}
}