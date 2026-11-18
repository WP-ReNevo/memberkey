<?php
/**
 * @package MemberKey
 */

namespace MemberKey;

final class Installer {

	/**
	 * Bump whenever Schema changes; dbDelta then applies the difference on update.
	 */
	public const SCHEMA_VERSION = 1;

	public const VERSION_OPTION = 'memberkey_db_version';

	public static function activate(): void {
		self::migrate();
	}

	/**
	 * Runs on every request so updates (which skip the activation hook) and new multisite sites get migrated.
	 */
	public static function maybe_upgrade(): void {
		if ( (int) get_option( self::VERSION_OPTION, 0 ) < self::SCHEMA_VERSION ) {
			self::migrate();
		}
	}

	private static function migrate(): void {
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		dbDelta( Schema::tables() );
		update_option( self::VERSION_OPTION, self::SCHEMA_VERSION );
	}
}
