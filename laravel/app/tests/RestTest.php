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
			 *	Test GET:rest/categories/all/recent
			 */
			public function testGetCategoriesAllRecent () {		
				try {
					$response = $this->call('GET', 'rest/categories/all/recent');
					// TODO: make sure sorted by views...
				} catch ( Exception $e ) {
					$this->fail( 'Failed to load resource at Get:rest/categories/all/recent\n'.$e );
				}
			}
			/**
			 *	Test GET:rest/categories/all/discussed
			 */
			public function testGetCategoriesAllDiscussed () {		
				try {
					$response = $this->call('GET', 'rest/categories/all/discussed');
					// TODO: make sure sorted by views...
				} catch ( Exception $e ) {
					$this->fail( 'Failed to load resource at Get:rest/categories/all/discussed\n'.$e );
				}
			}
			/**
			 *	Test GET:rest/categories/all/longest
			 */
			public function testGetCategoriesAllLongest () {		
				try {
					$response = $this->call('GET', 'rest/categories/all/longest');
					// TODO: make sure sorted by views...
				} catch ( Exception $e ) {
					$this->fail( 'Failed to load resource at Get:rest/categories/all/longest\n'.$e );
				}
			}
			/**
			 *	Test GET:rest/categories/all/shortest
			 */
			public function testGetCategoriesAllShortest () {		
				try {
					$response = $this->call('GET', 'rest/categories/all/shortest');
					// TODO: make sure sorted by views...
				} catch ( Exception $e ) {
					$this->fail( 'Failed to load resource at Get:rest/categories/all/shortest\n'.$e );
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