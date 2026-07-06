<?php
/**
 * Uninstall: remove plugin options. Documented, reversible-by-reinstall policy.
 *
 * @package AOSARS_Commerce_Skin
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'acs_settings' );
