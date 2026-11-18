<?php
/**
 * @package MemberKey
 */

namespace MemberKey\Tests;

use MemberKey\Installer;

class InstallerTest extends TestCase {

	private const TABLES = array( 'mk_subscriptions', 'mk_payments', 'mk_download_log' );

	public function test_activation_creates_the_three_tables_and_stores_the_schema_version() {
		$this->drop_plugin_tables();
		delete_option( Installer::VERSION_OPTION );

		do_action( 'activate_memberkey/memberkey.php' );

		foreach ( self::TABLES as $table ) {
			$this->assertTrue( $this->table_exists( $table ), "Missing table {$table}" );
		}
		$this->assertSame( Installer::SCHEMA_VERSION, (int) get_option( Installer::VERSION_OPTION ) );
	}

	public function test_reactivation_keeps_existing_rows() {
		global $wpdb;
		$wpdb->insert(
			$wpdb->prefix . 'mk_download_log',
			array(
				'user_id'       => 7,
				'download_id'   => 3,
				'downloaded_at' => '2026-01-01 12:00:00',
			)
		);

		do_action( 'activate_memberkey/memberkey.php' );

		$this->assertSame( '1', $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->prefix}mk_download_log WHERE user_id = 7" ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	public function test_update_migrates_the_schema_when_the_stored_version_is_older() {
		$this->run_ddl( 'ALTER TABLE %s DROP COLUMN cancelled_at', 'mk_subscriptions' );
		update_option( Installer::VERSION_OPTION, 0 );

		do_action( 'plugins_loaded' );

		$this->assertTrue( $this->column_exists( 'mk_subscriptions', 'cancelled_at' ) );
		$this->assertSame( Installer::SCHEMA_VERSION, (int) get_option( Installer::VERSION_OPTION ) );
	}

	public function test_no_migration_runs_when_the_schema_is_current() {
		$this->run_ddl( 'ALTER TABLE %s DROP COLUMN cancelled_at', 'mk_subscriptions' );

		do_action( 'plugins_loaded' );

		$this->assertFalse( $this->column_exists( 'mk_subscriptions', 'cancelled_at' ) );
	}

	public function tear_down() {
		parent::tear_down();

		// DDL commits implicitly, so the rollback may have left the schema or the stored version half-done.
		if ( $this->ddl_ran ) {
			remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );
			remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
			wp_cache_flush();
			Installer::activate();
		}
	}

	/**
	 * @var bool
	 */
	private $ddl_ran = false;

	/**
	 * DDL bypasses the per-test transaction, so it has to hit the real tables, not temporary ones.
	 */
	private function run_ddl( $sql, $table ) {
		global $wpdb;

		remove_filter( 'query', array( $this, '_drop_temporary_tables' ) );
		remove_filter( 'query', array( $this, '_create_temporary_tables' ) );
		$this->ddl_ran = true;

		$wpdb->query( sprintf( $sql, $wpdb->prefix . $table ) ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
	}

	private function drop_plugin_tables() {
		foreach ( self::TABLES as $table ) {
			$this->run_ddl( 'DROP TABLE IF EXISTS %s', $table );
		}
	}

	private function column_exists( $table, $column ) {
		global $wpdb;
		return null !== $wpdb->get_var( $wpdb->prepare( "SHOW COLUMNS FROM {$wpdb->prefix}{$table} LIKE %s", $column ) ); // phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
	}

	private function table_exists( $table ) {
		global $wpdb;
		$name = $wpdb->prefix . $table;
		return $name === $wpdb->get_var( $wpdb->prepare( 'SHOW TABLES LIKE %s', $name ) );
	}
}
