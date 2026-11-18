<?php
/**
 * Plugin Name:       MemberKey
 * Description:       Membership, subscriptions, content restriction and protected downloads.
 * Version:           0.1.0
 * Requires at least: 6.4
 * Requires PHP:      7.4
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       memberkey
 *
 * @package MemberKey
 */

defined( 'ABSPATH' ) || exit;

define( 'MEMBERKEY_VERSION', '0.1.0' );
define( 'MEMBERKEY_FILE', __FILE__ );
define( 'MEMBERKEY_DIR', plugin_dir_path( __FILE__ ) );

spl_autoload_register(
	static function ( $class_name ) {
		$prefix = 'MemberKey\\';
		if ( 0 !== strpos( $class_name, $prefix ) ) {
			return;
		}
		$path = MEMBERKEY_DIR . 'includes/' . str_replace( '\\', '/', substr( $class_name, strlen( $prefix ) ) ) . '.php';
		if ( is_readable( $path ) ) {
			require $path;
		}
	}
);

/**
 * The plugin instance, which holds the shared adapters (Clock, Mailer).
 */
function memberkey(): \MemberKey\Plugin {
	return \MemberKey\Plugin::instance();
}

register_activation_hook( __FILE__, array( \MemberKey\Installer::class, 'activate' ) );

memberkey()->boot();
