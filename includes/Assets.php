<?php
/**
 * @package MemberKey
 */

namespace MemberKey;

/**
 * Registers the React bundles produced by `npm run build`; screens enqueue them by handle.
 */
final class Assets {

	private const BUNDLES = array(
		'memberkey-admin'    => 'admin',
		'memberkey-frontend' => 'frontend',
	);

	public function register(): void {
		foreach ( self::BUNDLES as $handle => $name ) {
			$asset_file = MEMBERKEY_DIR . "build/{$name}.asset.php";
			if ( ! is_readable( $asset_file ) ) {
				continue;
			}
			$asset = require $asset_file;

			wp_register_script(
				$handle,
				plugins_url( "build/{$name}.js", MEMBERKEY_FILE ),
				$asset['dependencies'],
				$asset['version'],
				true
			);
			wp_set_script_translations( $handle, 'memberkey' );

			if ( is_readable( MEMBERKEY_DIR . "build/{$name}.css" ) ) {
				wp_register_style( $handle, plugins_url( "build/{$name}.css", MEMBERKEY_FILE ), array(), $asset['version'] );
			}
		}
	}
}
