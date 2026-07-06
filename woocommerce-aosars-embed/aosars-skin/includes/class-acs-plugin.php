<?php
/**
 * Bootstrap singleton.
 *
 * @package AOSARS_Commerce_Skin
 */

namespace ACS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Wires the plugin modules, each guarded, in the correct context.
 */
final class Plugin {

	/**
	 * Singleton instance.
	 *
	 * @var Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the instance.
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Load files and register modules.
	 *
	 * @return void
	 */
	public function boot() {
		add_action(
			'init',
			acs_guard(
				function () {
					load_plugin_textdomain( 'aosars-commerce-skin', false, dirname( plugin_basename( ACS_FILE ) ) . '/languages' );
				}
			)
		);

		$files = array( 'helpers.php', 'class-acs-settings.php', 'class-acs-assets.php', 'class-acs-templates.php', 'class-acs-hooks.php' );
		foreach ( $files as $file ) {
			$path = ACS_DIR . 'includes/' . $file;
			if ( file_exists( $path ) ) {
				require_once $path;
			}
		}

		// Front + integration modules (safe on every request).
		if ( class_exists( '\ACS\Assets' ) ) {
			( new Assets() )->hooks();
		}
		if ( class_exists( '\ACS\Templates' ) ) {
			( new Templates() )->hooks();
		}
		if ( class_exists( '\ACS\Hooks' ) ) {
			( new Hooks() )->hooks();
		}

		// Admin-only code loaded only in the admin (CP7).
		if ( is_admin() && class_exists( '\ACS\Settings' ) ) {
			( new Settings() )->hooks();
		}
	}
}
