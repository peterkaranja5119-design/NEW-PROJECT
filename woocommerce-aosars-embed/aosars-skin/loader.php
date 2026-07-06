<?php
/**
 * AOSARS Commerce Skin — EMBEDDED variant (v1.0.5), loaded by a single guarded
 * require appended to woocommerce.php (marker: AOSARS-EMBED). The standalone
 * "AOSARS Commerce Skin" plugin is the source of truth for this code; every
 * file under includes/, templates/, public/ and admin/ here is a byte-identical
 * copy of that tested plugin. Only this bootstrap differs: the plugin-lifecycle
 * code that only makes sense for a standalone plugin is removed, and the
 * double-load guard yields to the standalone plugin. See README-EMBED.md here
 * for the change inventory and the re-apply procedure after WooCommerce updates.
 *
 * @package AOSARS_Commerce_Skin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // No direct access.
}

/* CP2 - double-load guard, embedded flavour. The standalone plugin directory
   sorts alphabetically before "woocommerce", so if it is still active it has
   already defined ACS_VER by the time this loader runs. Yield silently
   (dormancy is a designed state, not an error) and tell the admin — once,
   dismissibly — that the skin is now built into WooCommerce. */
if ( defined( 'ACS_VER' ) ) {
	if ( function_exists( 'add_action' ) ) {
		add_action(
			'admin_notices',
			function () {
				if ( ! function_exists( 'current_user_can' ) || ! current_user_can( 'activate_plugins' ) ) {
					return;
				}
				if ( get_option( 'acs_embedded_notice_dismissed' ) ) {
					return;
				}
				if ( isset( $_GET['acs_embed_dismiss'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
					update_option( 'acs_embedded_notice_dismissed', 1 );
					return;
				}
				echo '<div class="notice notice-info"><p><strong>AOSARS Commerce Skin:</strong> the skin is now built into WooCommerce, so the standalone “AOSARS Commerce Skin” plugin is no longer needed — the standalone copy is currently running and the built-in copy is dormant. You can deactivate (and keep or delete) the standalone plugin; the built-in skin takes over automatically. <a href="' . esc_url( add_query_arg( 'acs_embed_dismiss', '1' ) ) . '">Dismiss</a></p></div>';
			}
		);
	}
	return;
}

/* CP3 - define every constant the code reads, at load. plugin_dir_path/url
   work for any file under wp-content/plugins, so these resolve to
   .../plugins/woocommerce/includes/aosars-skin/. */
define( 'ACS_VER', '1.0.5' );
define( 'ACS_EMBEDDED', true );
define( 'ACS_FILE', __FILE__ );
define( 'ACS_DIR', plugin_dir_path( __FILE__ ) );
define( 'ACS_URL', plugin_dir_url( __FILE__ ) );
define( 'ACS_SLUG', 'aosars-commerce-skin' );
define( 'ACS_OPT', 'acs_settings' );
define( 'ACS_MIN_PHP', '7.4' );

/* Runtime PHP floor guard: skip the skin gracefully below the floor. */
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
			echo '<div class="notice notice-error"><p>AOSARS Commerce Skin (embedded): a core file is missing. Restore the includes/aosars-skin folder from the integration package.</p></div>';
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
			echo '<div class="notice notice-error"><p>AOSARS Commerce Skin (embedded) could not start; WooCommerce and the rest of your site are unaffected.</p></div>';
		}
	);
}
