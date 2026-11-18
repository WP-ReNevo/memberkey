<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Tests;

use MemberKey\Installer;
use WP_REST_Request;

class RestStatusTest extends TestCase {

	public function test_namespace_is_registered() {
		$this->assertContains( 'memberkey/v1', rest_get_server()->get_namespaces() );
	}

	public function test_admin_gets_plugin_and_schema_version() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'administrator' ) ) );

		$response = rest_do_request( new WP_REST_Request( 'GET', '/memberkey/v1/status' ) );

		$this->assertSame( 200, $response->get_status() );
		$this->assertSame(
			array(
				'version'        => MEMBERKEY_VERSION,
				'schema_version' => Installer::SCHEMA_VERSION,
			),
			$response->get_data()
		);
	}

	public function test_visitor_is_refused() {
		wp_set_current_user( 0 );

		$response = rest_do_request( new WP_REST_Request( 'GET', '/memberkey/v1/status' ) );

		$this->assertSame( 401, $response->get_status() );
	}

	public function test_member_without_manage_options_is_refused() {
		wp_set_current_user( self::factory()->user->create( array( 'role' => 'subscriber' ) ) );

		$response = rest_do_request( new WP_REST_Request( 'GET', '/memberkey/v1/status' ) );

		$this->assertSame( 403, $response->get_status() );
	}
}
