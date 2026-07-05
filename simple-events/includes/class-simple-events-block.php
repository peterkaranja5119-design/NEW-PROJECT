<?php
/**
 * "Events List" block (server-side rendered).
 *
 * Reuses the shortcode markup so the block and shortcode stay in sync.
 *
 * @package SimpleEvents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders the Events List block.
 */
class Simple_Events_Block {

	/**
	 * Register hooks.
	 */
	public function init() {
		add_action( 'init', array( $this, 'register_block' ) );
	}

	/**
	 * Register the block type from its block.json.
	 */
	public function register_block() {
		// register_block_type with a directory requires WP 5.8+. Bail gracefully otherwise.
		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		register_block_type(
			SIMPLE_EVENTS_DIR . 'blocks/events-list',
			array(
				'render_callback' => array( $this, 'render' ),
			)
		);
	}

	/**
	 * Render callback: delegate to the shortcode handler.
	 *
	 * @param array $attributes Block attributes.
	 * @return string
	 */
	public function render( $attributes ) {
		$shortcode = new Simple_Events_Shortcode();

		return $shortcode->render(
			array(
				'limit'        => isset( $attributes['limit'] ) ? absint( $attributes['limit'] ) : 5,
				'scope'        => isset( $attributes['scope'] ) ? sanitize_key( $attributes['scope'] ) : 'upcoming',
				'category'     => isset( $attributes['category'] ) ? sanitize_title( $attributes['category'] ) : '',
				'show_excerpt' => ( ! isset( $attributes['showExcerpt'] ) || $attributes['showExcerpt'] ) ? 'yes' : 'no',
			)
		);
	}
}
