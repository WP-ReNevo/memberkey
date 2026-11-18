<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Rest;

use MemberKey\Installer;
use WP_REST_Response;

final class StatusController {

	public const NAMESPACE = 'memberkey/v1';

	public function register_routes(): void {
		register_rest_route(
			self::NAMESPACE,
			'/status',
			array(
				'methods'             => 'GET',
				'callback'            => array( $this, 'show' ),
				'permission_callback' => static function () {
					return current_user_can( 'manage_options' );
				},
			)
		);
	}

	public function show(): WP_REST_Response {
		return new WP_REST_Response(
			array(
				'version'        => MEMBERKEY_VERSION,
				'schema_version' => Installer::SCHEMA_VERSION,
			)
		);
	}
}
