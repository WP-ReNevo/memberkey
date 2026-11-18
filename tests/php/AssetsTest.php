<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Tests;

class AssetsTest extends TestCase {

	public function set_up() {
		parent::set_up();
		if ( ! file_exists( MEMBERKEY_DIR . 'build/admin.asset.php' ) ) {
			$this->markTestSkipped( 'Run `npm run build` first.' );
		}
	}

	public function test_admin_bundle_is_registered_with_its_wordpress_dependencies() {
		$script = wp_scripts()->query( 'memberkey-admin' );

		$this->assertNotFalse( $script );
		$this->assertContains( 'wp-element', $script->deps );
		$this->assertContains( 'wp-i18n', $script->deps );
		$this->assertSame( 'memberkey', $script->textdomain );
	}

	public function test_frontend_bundle_is_registered() {
		$this->assertTrue( wp_script_is( 'memberkey-frontend', 'registered' ) );
	}
}
