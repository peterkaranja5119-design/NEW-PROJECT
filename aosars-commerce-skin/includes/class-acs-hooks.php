<?php
/**
 * Presentation hooks: trust bar, mini-cart drawer, body class, shortcodes.
 *
 * @package AOSARS_Commerce_Skin
 */

namespace ACS;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Injects markup at supported WooCommerce hook points. Never owns a template.
 */
class Hooks {

	/**
	 * Register hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		add_filter( 'body_class', acs_guard( array( $this, 'body_class' ) ) );

		if ( enabled( 'm7' ) ) {
			add_action( 'woocommerce_before_shop_loop', acs_guard( array( $this, 'trust_bar' ) ), 4 );
			add_shortcode( 'acs_trust_bar', acs_guard( array( $this, 'sc_trust_bar' ) ) );
		}
		if ( enabled( 'm9' ) ) {
			add_action( 'wp_footer', acs_guard( array( $this, 'drawer' ) ) );
		}
		add_shortcode( 'acs_products', acs_guard( array( $this, 'sc_products' ) ) );
	}

	/**
	 * Mark the body so the scoped CSS applies.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function body_class( $classes ) {
		$classes[] = 'acs-skin';
		return $classes;
	}

	/**
	 * Trust bar markup (escaped).
	 *
	 * @return string
	 */
	private function trust_bar_html() {
		$items = array(
			array( esc_html__( 'Instant access', 'aosars-commerce-skin' ), esc_html__( 'Download on payment', 'aosars-commerce-skin' ) ),
			array( esc_html__( 'Secure payment', 'aosars-commerce-skin' ), esc_html__( 'Mobile money & card', 'aosars-commerce-skin' ) ),
			array( esc_html__( 'Built for Africa', 'aosars-commerce-skin' ), esc_html__( 'Pay in your currency', 'aosars-commerce-skin' ) ),
			array( esc_html__( 'Trusted by scholars', 'aosars-commerce-skin' ), esc_html__( 'Across the continent', 'aosars-commerce-skin' ) ),
		);
		$html = '<div class="acs-trustbar" role="list">';
		foreach ( $items as $it ) {
			$html .= '<div class="acs-tb" role="listitem"><b>' . esc_html( $it[0] ) . '</b><span>' . esc_html( $it[1] ) . '</span></div>';
		}
		$html .= '</div>';
		return $html;
	}

	/**
	 * Echo the trust bar before the loop.
	 *
	 * @return void
	 */
	public function trust_bar() {
		echo wp_kses_post( $this->trust_bar_html() );
	}

	/**
	 * Trust bar shortcode.
	 *
	 * @return string
	 */
	public function sc_trust_bar() {
		return $this->trust_bar_html();
	}

	/**
	 * Products shortcode passthrough to WooCommerce with the skin class.
	 *
	 * @param array $atts Attributes.
	 * @return string
	 */
	public function sc_products( $atts ) {
		$atts = shortcode_atts(
			array(
				'limit'   => '8',
				'columns' => '4',
				'category' => '',
			),
			is_array( $atts ) ? $atts : array(),
			'acs_products'
		);
		if ( ! function_exists( 'do_shortcode' ) ) {
			return '';
		}
		$limit    = (int) $atts['limit'];
		$columns  = (int) $atts['columns'];
		$category = sanitize_text_field( $atts['category'] );
		$inner    = '[products limit="' . $limit . '" columns="' . $columns . '"' . ( $category ? ' category="' . esc_attr( $category ) . '"' : '' ) . ']';
		return '<div class="acs-skin acs-shortcode">' . do_shortcode( $inner ) . '</div>';
	}

	/**
	 * Mini-cart drawer shell in the footer (hidden until an add-to-cart).
	 *
	 * @return void
	 */
	public function drawer() {
		if ( ! wc_active() ) {
			return;
		}
		?>
		<div class="acs-scrim" id="acsScrim"></div>
		<aside class="acs-drawer" id="acsDrawer" role="dialog" aria-modal="true" aria-labelledby="acsDrawerTitle" aria-hidden="true">
			<div class="acs-drawer-head">
				<span class="acs-dh-ic" aria-hidden="true">✓</span>
				<b id="acsDrawerTitle"><?php echo esc_html__( 'Added to your cart', 'aosars-commerce-skin' ); ?></b>
				<button class="acs-drawer-close" id="acsDrawerClose" aria-label="<?php echo esc_attr__( 'Close cart', 'aosars-commerce-skin' ); ?>">&times;</button>
			</div>
			<div class="acs-drawer-body" id="acsDrawerBody"></div>
			<div class="acs-drawer-foot">
				<p class="acs-reassure"><?php echo esc_html__( 'Instant download after payment — nothing to ship.', 'aosars-commerce-skin' ); ?></p>
				<a class="acs-btn acs-btn-primary" id="acsCheckout" href="<?php echo esc_url( function_exists( 'wc_get_checkout_url' ) ? wc_get_checkout_url() : '#' ); ?>"><?php echo esc_html__( 'Checkout', 'aosars-commerce-skin' ); ?></a>
				<div class="acs-drawer-sec">
					<a class="acs-btn acs-btn-line" id="acsViewCart" href="<?php echo esc_url( function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : '#' ); ?>"><?php echo esc_html__( 'View cart', 'aosars-commerce-skin' ); ?></a>
					<button class="acs-btn acs-btn-line" id="acsContinue"><?php echo esc_html__( 'Continue shopping', 'aosars-commerce-skin' ); ?></button>
				</div>
			</div>
		</aside>
		<?php
	}
}
