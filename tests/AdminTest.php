<?php

namespace s3rgiosan\WP\Plugin\Smartlook\Tests;

use s3rgiosan\WP\Plugin\Smartlook\Admin;
use s3rgiosan\WP\Plugin\Smartlook\Plugin;

class AdminTest extends \WP_UnitTestCase {

	public $admin;

	function setUp() {
		parent::setUp();

		$plugin      = new Plugin( 'wpsmartlook', '1.0.0' );
		$this->admin = new Admin( $plugin );
	}

	function test_settings_name() {
		$actual = $this->admin->get_settings_name();
		$this->assertEquals( 'smartlook_settings', $actual );
	}
}
