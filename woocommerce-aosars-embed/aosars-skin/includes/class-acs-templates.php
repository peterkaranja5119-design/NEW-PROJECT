<?php
/**
 * Template overrides via supported WooCommerce filters.
 *
 * Shop cards are a template *part* (content-product.php) which WooCommerce
 * loads through wc_get_template_part(), so the correct filter is
 * `wc_get_template_part` (NOT woocommerce_locate_template, which only serves
 * full templates such as single-product.php / cart.php).
 *
 * @package AOSARS_Commerce_Skin
 */

namespace ACS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Points WooCommerce at our template copies for files we ship, only when the
 * owning module is enabled. Everything else falls through unchanged.
 */
class Templates {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		// Template PARTS (loop card).
		add_filter( 'wc_get_template_part', acs_guard( array( $this, 'template_part' ) ), 20, 3 );
		// Full templates (reserved for single/cart/checkout overrides).
		add_filter( 'woocommerce_locate_template', acs_guard( array( $this, 'locate' ) ), 20, 3 );
	}

	/**
	 * Override the shop loop card.
	 *
	 * @param string $template Resolved path.
	 * @param string $slug     Template slug, e.g. "content".
	 * @param string $name     Template name, e.g. "product".
	 * @return string
	 */
	public function template_part( $template, $slug, $name ) {
		if ( 'content' === $slug && 'product' === $name && enabled( 'm1' ) ) {
			$candidate = ACS_DIR . 'templates/woocommerce/content-product.php';
			if ( file_exists( $candidate ) ) {
				return $candidate;
			}
		}
		return $template;
	}

	/**
	 * Override map for full templates: template name => owning module.
	 *
	 * @param string $template      Resolved path.
	 * @param string $template_name Requested template.
	 * @param string $template_path Theme path.
	 * @return string
	 */
	public function locate( $template, $template_name, $template_path ) {
		$map = array();
		if ( isset( $map[ $template_name ] ) && enabled( $map[ $template_name ] ) ) {
			$candidate = ACS_DIR . 'templates/woocommerce/' . $template_name;
			if ( file_exists( $candidate ) ) {
				return $candidate;
			}
		}
		return $template;
	}
}
