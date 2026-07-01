<?php
/**
 * Plugin Name:       Simple Events
 * Plugin URI:        https://example.com/simple-events
 * Description:       Manage and display events with a custom post type, event details (date, time, venue), shortcodes, and a widget.
 * Version:           1.0.0
 * Requires at least: 5.6
 * Requires PHP:      7.2
 * Author:            Simple Events
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       simple-events
 * Domain Path:       /languages
 *
 * @package SimpleEvents
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Plugin constants.
define( 'SIMPLE_EVENTS_VERSION', '1.0.0' );
define( 'SIMPLE_EVENTS_FILE', __FILE__ );
define( 'SIMPLE_EVENTS_DIR', plugin_dir_path( __FILE__ ) );
define( 'SIMPLE_EVENTS_URL', plugin_dir_url( __FILE__ ) );

// Load plugin classes.
require_once SIMPLE_EVENTS_DIR . 'includes/class-simple-events-cpt.php';
require_once SIMPLE_EVENTS_DIR . 'includes/class-simple-events-meta.php';
require_once SIMPLE_EVENTS_DIR . 'includes/class-simple-events-query.php';
require_once SIMPLE_EVENTS_DIR . 'includes/class-simple-events-shortcode.php';
require_once SIMPLE_EVENTS_DIR . 'includes/class-simple-events-widget.php';
require_once SIMPLE_EVENTS_DIR . 'includes/class-simple-events-plugin.php';

/**
 * Returns the main plugin instance.
 *
 * @return Simple_Events_Plugin
 */
function simple_events() {
	return Simple_Events_Plugin::instance();
}

// Boot the plugin.
simple_events();

/**
 * Activation: register the post type and flush rewrite rules so the
 * event archive/single URLs work immediately.
 */
function simple_events_activate() {
	Simple_Events_CPT::register();
	flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'simple_events_activate' );

/**
 * Deactivation: flush rewrite rules to clean up event URLs.
 */
function simple_events_deactivate() {
	flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'simple_events_deactivate' );
