<?php
/**
 * Small helpers: option access, module toggles, presentation.
 *
 * @package AOSARS_Commerce_Skin
 */

namespace ACS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Read a plugin option.
 *
 * @param string $key     Key.
 * @param mixed  $default Default.
 * @return mixed
 */
function opt( $key, $default = '' ) {
	$options = get_option( ACS_OPT, array() );
	if ( ! is_array( $options ) ) {
		return $default;
	}
	return isset( $options[ $key ] ) ? $options[ $key ] : $default;
}

/**
 * Is a module enabled? Default ON when unset.
 *
 * @param string $module Module key, e.g. "m1".
 * @return bool
 */
function enabled( $module ) {
	$options = get_option( ACS_OPT, array() );
	$key     = 'mod_' . $module;
	if ( ! is_array( $options ) || ! array_key_exists( $key, $options ) ) {
		return true;
	}
	return '1' === (string) $options[ $key ];
}

/**
 * WooCommerce active?
 *
 * @return bool
 */
function wc_active() {
	return class_exists( 'WooCommerce' ) && function_exists( 'is_woocommerce' );
}

/**
 * Star string for a rating (indigo/cyan stars are styled by CSS).
 *
 * @param float $rating Rating 0-5.
 * @return string
 */
function stars( $rating ) {
	$rating = (float) $rating;
	$full   = (int) floor( $rating );
	$half   = ( $rating - $full ) >= 0.5 ? 1 : 0;
	$out    = str_repeat( '★', max( 0, min( 5, $full ) ) );
	if ( $half && $full < 5 ) {
		$out .= '☆';
	}
	return $out;
}

/**
 * Sale badge like "-44%".
 *
 * @param \WC_Product $product Product.
 * @return string
 */
function sale_badge( $product ) {
	if ( ! is_object( $product ) || ! method_exists( $product, 'get_regular_price' ) ) {
		return '';
	}
	$regular = (float) $product->get_regular_price();
	$sale    = (float) $product->get_sale_price();
	if ( $regular > 0 && $sale > 0 && $sale < $regular ) {
		$pct = (int) round( ( ( $regular - $sale ) / $regular ) * 100 );
		return '-' . $pct . '%';
	}
	return esc_html__( 'Sale', 'aosars-commerce-skin' );
}
