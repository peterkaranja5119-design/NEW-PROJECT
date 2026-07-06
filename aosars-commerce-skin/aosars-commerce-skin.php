<?php
/**
 * Plugin Name:       AOSARS Commerce Skin
 * Plugin URI:        https://aosars.com/
 * Description:       Non-interfering redesign skin for WooCommerce on AOSARS EDGE. Restyles shop cards, adds a mini-cart drawer, trust bar and toolbar via template overrides and hooks, without editing WooCommerce, Tutor LMS, the theme or the currency plugin. Per-module toggles in the admin menu.
 * Version:           1.0.3
 * Author:            Karanja Maina
 * Author URI:        https://aosars.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       aosars-commerce-skin
 * Domain Path:       /languages
 * Requires at least: 5.6
 * Requires PHP:      7.4
 * WC requires at least: 7.0
 * WC tested up to:   9.0
 * Tested up to:      6.5
 *
 * @package AOSARS_Commerce_Skin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

/* CP2 - double-load guard. */
if ( defined( 'ACS_VER' ) ) {
	return;
}

/* CP3 - define every constant the code reads, at load. */
define( 'ACS_VER', '1.0.3' );
define( 'ACS_FILE', __FILE__ );
define( 'ACS_DIR', plugin_dir_path( __FILE__ ) );
define( 'ACS_URL', plugin_dir_url( __FILE__ ) );
define( 'ACS_SLUG', 'aosars-commerce-skin' );
define( 'ACS_OPT', 'acs_settings' );
define( 'ACS_MIN_PHP', '7.4' );

/* Runtime PHP floor guard: self-deactivate gracefully below the floor. */
if ( version_compare( PHP_VERSION, ACS_MIN_PHP, '<' ) ) {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>' . esc_html__( 'AOSARS Commerce Skin requires PHP 7.4 or higher and has not started.', 'aosars-commerce-skin' ) . '</p></div>';
		}
	);
	return;
}

/* CP1 - fatal firewall. Wrap every hook callback in this. */
if ( ! function_exists( 'acs_guard' ) ) {
	/**
	 * Wrap a callback so any Throwable degrades one feature instead of the page.
	 *
	 * @param callable $cb Callback.
	 * @return callable
	 */
	function acs_guard( $cb ) {
		return function () use ( $cb ) {
			$args = func_get_args();
			try {
				return call_user_func_array( $cb, $args );
			} catch ( \Throwable $e ) {
				if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
					error_log( '[AOSARS Commerce Skin] ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine() );
				}
				return isset( $args[0] ) ? $args[0] : null;
			}
		};
	}
}

/* CP5 - missing-file tolerance. */
$acs_boot = ACS_DIR . 'includes/class-acs-plugin.php';
if ( ! file_exists( $acs_boot ) ) {
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>AOSARS Commerce Skin: a core file is missing. Please deactivate, delete and reinstall the plugin.</p></div>';
		}
	);
	return;
}
require $acs_boot;

/* CP7 - bootstrap in a try/catch. */
try {
	\ACS\Plugin::instance()->boot();
} catch ( \Throwable $e ) {
	if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
		error_log( '[AOSARS Commerce Skin] boot: ' . $e->getMessage() );
	}
	add_action(
		'admin_notices',
		function () {
			echo '<div class="notice notice-error"><p>AOSARS Commerce Skin could not start; the rest of your site is unaffected.</p></div>';
		}
	);
}

/* CP6 - guarded activation. */
register_activation_hook(
	__FILE__,
	function () {
		try {
			if ( false === get_option( ACS_OPT ) ) {
				add_option( ACS_OPT, array() );
			}
		} catch ( \Throwable $e ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) {
				error_log( '[AOSARS Commerce Skin] activation: ' . $e->getMessage() );
			}
		}
	}
);
