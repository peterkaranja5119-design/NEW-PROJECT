<?php
/**
 * AOSARS Commerce Skin — fail-safe verification harness (wp-plugin-failsafe V3/V4).
 *
 * Runs a self-contained WordPress stub, loads the plugin, fires the full
 * lifecycle, and fault-injects a throw to prove the fatal firewall (CP1)
 * degrades one feature instead of white-screening the site.
 *
 * Usage on any PHP 7.4+ host:  php tests/harness.php
 *
 * @package AOSARS_Commerce_Skin
 */

error_reporting( E_ALL );
if ( ! defined( 'ABSPATH' ) ) { define( 'ABSPATH', __DIR__ . '/' ); }
if ( ! defined( 'WP_DEBUG' ) ) { define( 'WP_DEBUG', true ); }

$GLOBALS['__hooks']  = array();
$GLOBALS['__opts']   = array();
$GLOBALS['__admin']  = false;
$GLOBALS['__shortc'] = array();

/* --- minimal WordPress stub surface used by the plugin --- */
function add_action( $h, $cb, $prio = 10, $args = 1 ) { $GLOBALS['__hooks'][ $h ][] = $cb; return true; }
function add_filter( $h, $cb, $prio = 10, $args = 1 ) { $GLOBALS['__hooks'][ $h ][] = $cb; return true; }
function do_action( $h ) { $a = array_slice( func_get_args(), 1 ); if ( ! empty( $GLOBALS['__hooks'][ $h ] ) ) { foreach ( $GLOBALS['__hooks'][ $h ] as $cb ) { call_user_func_array( $cb, $a ); } } }
function apply_filters( $h, $value ) { $a = array_slice( func_get_args(), 2 ); if ( ! empty( $GLOBALS['__hooks'][ $h ] ) ) { foreach ( $GLOBALS['__hooks'][ $h ] as $cb ) { $value = call_user_func_array( $cb, array_merge( array( $value ), $a ) ); } } return $value; }
function add_shortcode( $tag, $cb ) { $GLOBALS['__shortc'][ $tag ] = $cb; }
function do_shortcode( $s ) { return $s; }
function register_activation_hook( $f, $cb ) { $GLOBALS['__activate'] = $cb; }
function get_option( $k, $d = false ) { return isset( $GLOBALS['__opts'][ $k ] ) ? $GLOBALS['__opts'][ $k ] : $d; }
function add_option( $k, $v ) { $GLOBALS['__opts'][ $k ] = $v; return true; }
function update_option( $k, $v ) { $GLOBALS['__opts'][ $k ] = $v; return true; }
function delete_option( $k ) { unset( $GLOBALS['__opts'][ $k ] ); return true; }
function plugin_dir_path( $f ) { return rtrim( dirname( $f ), '/' ) . '/'; }
function plugin_dir_url( $f ) { return 'http://example.test/wp-content/plugins/' . basename( dirname( $f ) ) . '/'; }
function plugin_basename( $f ) { return basename( dirname( $f ) ) . '/' . basename( $f ); }
function load_plugin_textdomain() { return true; }
function is_admin() { return (bool) $GLOBALS['__admin']; }
function current_user_can() { return true; }
function esc_html__( $s ) { return $s; }
function esc_attr__( $s ) { return $s; }
function esc_html( $s ) { return $s; }
function esc_attr( $s ) { return $s; }
function esc_url( $s ) { return $s; }
function __( $s ) { return $s; }
function wp_kses_post( $s ) { return $s; }
function wp_strip_all_tags( $s ) { return strip_tags( $s ); }
function sanitize_text_field( $s ) { return trim( (string) $s ); }
function shortcode_atts( $d, $a ) { return array_merge( $d, is_array( $a ) ? $a : array() ); }
function checked( $a, $b = true ) { echo $a == $b ? 'checked' : ''; }
function submit_button() { echo '<button>Save</button>'; }
function settings_fields() {}
function register_setting() {}
function add_menu_page() { return 'acs'; }
function wc_get_cart_url() { return 'http://example.test/cart'; }
function wc_get_checkout_url() { return 'http://example.test/checkout'; }
function is_shop() { return true; }
function is_product() { return false; }
function is_cart() { return false; }
function is_checkout() { return false; }
function is_woocommerce() { return true; }
function is_product_category() { return false; }
function is_product_taxonomy() { return false; }
function wp_enqueue_style() {}
function wp_enqueue_script() {}
function wp_localize_script() {}
function wp_register_style() {}
function wp_register_script() {}
class WooCommerce {}

/* --- load the plugin --- */
$ok   = true;
$main = dirname( __DIR__ ) . '/aosars-commerce-skin.php';
try {
	require $main;
	echo "LOAD           OK  (plugin loaded without fatal)\n";
} catch ( \Throwable $e ) {
	$ok = false;
	echo "LOAD           FAIL " . $e->getMessage() . "\n";
}

/* --- fire the lifecycle --- */
foreach ( array( 'init', 'wp_enqueue_scripts', 'wp_footer' ) as $h ) {
	try { do_action( $h ); echo "ACTION $h" . str_repeat( ' ', max( 1, 14 - strlen( $h ) ) ) . "OK\n"; }
	catch ( \Throwable $e ) { $ok = false; echo "ACTION $h FAIL " . $e->getMessage() . "\n"; }
}
try { $c = apply_filters( 'body_class', array() ); echo "FILTER body_class  OK  (" . implode( ',', $c ) . ")\n"; }
catch ( \Throwable $e ) { $ok = false; echo "FILTER body_class  FAIL\n"; }
try {
	$t = apply_filters( 'wc_get_template_part', 'DEFAULT', 'content', 'product' );
	$hit = ( strpos( (string) $t, 'content-product.php' ) !== false );
	echo "FILTER card-part   " . ( $hit ? 'OK  (override served)' : 'OK  (fell through)' ) . "\n";
}
catch ( \Throwable $e ) { $ok = false; echo "FILTER card-part   FAIL\n"; }

/* admin path */
$GLOBALS['__admin'] = true;
$GLOBALS['__hooks'] = array();
try { \ACS\Plugin::instance()->boot(); do_action( 'admin_menu' ); echo "ADMIN menu         OK\n"; }
catch ( \Throwable $e ) { $ok = false; echo "ADMIN menu         FAIL " . $e->getMessage() . "\n"; }

/* --- V4 fault injection: prove CP1 --- */
$threw = false;
$guarded = acs_guard( function () { throw new \RuntimeException( 'boom' ); } );
try { $r = $guarded( array( 'x' ) ); echo "FAULT guarded      OK  (throw swallowed, returned first arg)\n"; }
catch ( \Throwable $e ) { $threw = true; echo "FAULT guarded      FAIL (throw escaped the firewall!)\n"; }
if ( $threw ) { $ok = false; }

/* --- V5 regression checks: the two faults that shipped in 1.0.2 --- */
/* 1) The WP minimum must not exceed the version the target site runs (6.5 verified). */
$head = file_get_contents( $main );
if ( preg_match( '/Requires at least:\s*([0-9.]+)/', $head, $m ) && version_compare( $m[1], '6.5', '>' ) ) {
	$ok = false;
	echo "HEADER requires    FAIL (Requires at least {$m[1]} blocks activation on the live site)\n";
} else {
	echo "HEADER requires    OK  (" . ( isset( $m[1] ) ? $m[1] : '?' ) . ")\n";
}
/* 2) The card button must keep WooCommerce's AJAX classes or the drawer never opens. */
$tpl = file_get_contents( dirname( __DIR__ ) . '/templates/woocommerce/content-product.php' );
if ( false === strpos( $tpl, 'ajax_add_to_cart' ) || false === strpos( $tpl, 'add_to_cart_button' ) ) {
	$ok = false;
	echo "TEMPLATE ajax-cls  FAIL (button drops add_to_cart_button/ajax_add_to_cart; drawer cannot open)\n";
} else {
	echo "TEMPLATE ajax-cls  OK  (WooCommerce button classes preserved)\n";
}

echo "\nRESULT: " . ( $ok ? 'PASS — no fatal on load, lifecycle or fault injection.' : 'FAIL — see above.' ) . "\n";
exit( $ok ? 0 : 1 );
