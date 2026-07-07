<?php
/**
 * Asset registration and conditional enqueue.
 *
 * @package AOSARS_Commerce_Skin
 */

namespace ACS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Loads the skin only on WooCommerce pages.
 */
class Assets {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		add_action( 'wp_enqueue_scripts', acs_guard( array( $this, 'front' ) ) );
	}

	/**
	 * Enqueue front assets on commerce pages only.
	 *
	 * @return void
	 */
	public function front() {
		// Load on the front end everywhere by default so the skin also reaches
		// Tutor LMS course archives and page-builder (ElementsKit) product grids,
		// not only native WooCommerce pages. The stylesheet is scoped to the
		// body.acs-skin class and to specific WooCommerce/Tutor selectors, so it
		// only affects elements it is meant to. Filterable for fine control.
		if ( is_admin() || ( function_exists( 'is_feed' ) && is_feed() ) ) {
			return;
		}
		$load = apply_filters( 'acs_load_assets', true );
		if ( ! $load ) {
			return;
		}

		wp_enqueue_style(
			'acs-montserrat',
			'https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap',
			array(),
			ACS_VER
		);
		wp_enqueue_style( 'acs-front', ACS_URL . 'public/css/acs-front.css', array(), ACS_VER );

		wp_enqueue_script( 'acs-front', ACS_URL . 'public/js/acs-front.js', array( 'jquery' ), ACS_VER, true );
		wp_localize_script(
			'acs-front',
			'ACS_DATA',
			array(
				'drawer'      => enabled( 'm9' ) ? 1 : 0,
				'single'      => enabled( 'm2' ) ? 1 : 0,
				'carousel'    => enabled( 'm3' ) ? 1 : 0,
				'cartUrl'     => function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '',
				'checkoutUrl' => function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '',
				'i18n'        => array(
					'added'    => esc_html__( 'Added to your cart', 'aosars-commerce-skin' ),
					'checkout' => esc_html__( 'Checkout', 'aosars-commerce-skin' ),
					'viewcart' => esc_html__( 'View cart', 'aosars-commerce-skin' ),
					'continue' => esc_html__( 'Continue shopping', 'aosars-commerce-skin' ),
					'instant'  => esc_html__( 'Instant download after payment — nothing to ship.', 'aosars-commerce-skin' ),
				),
			)
		);
	}
}
