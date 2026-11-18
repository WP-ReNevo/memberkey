<?php
/**
 * PHPUnit bootstrap: runs the suite inside a real WordPress install.
 *
 * @package MemberKey
 */

if ( false === getenv( 'WP_PHPUNIT__TESTS_CONFIG' ) ) {
	putenv( 'WP_PHPUNIT__TESTS_CONFIG=' . __DIR__ . '/wp-tests-config.php' ); // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.runtime_configuration_putenv
}

$memberkey_root      = dirname( __DIR__, 2 );
$memberkey_tests_dir = getenv( 'WP_PHPUNIT__DIR' ) ? getenv( 'WP_PHPUNIT__DIR' ) : $memberkey_root . '/vendor/wp-phpunit/wp-phpunit';

require_once $memberkey_root . '/vendor/autoload.php';
require_once $memberkey_root . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php';
require_once $memberkey_tests_dir . '/includes/functions.php';

tests_add_filter(
	'muplugins_loaded',
	static function () use ( $memberkey_root ) {
		require $memberkey_root . '/memberkey.php';
	}
);

require $memberkey_tests_dir . '/includes/bootstrap.php';
