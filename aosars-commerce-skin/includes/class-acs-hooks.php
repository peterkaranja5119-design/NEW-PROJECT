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

		if ( enabled( 'm2' ) ) {
			add_action( 'woocommerce_single_product_summary', acs_guard( array( $this, 'single_trust' ) ), 35 );
			add_action( 'wp_footer', acs_guard( array( $this, 'sticky_bar' ) ) );
		}
		if ( enabled( 'm3' ) ) {
			add_filter( 'woocommerce_output_related_products_args', acs_guard( array( $this, 'related_args' ) ), 20 );
		}
		if ( enabled( 'm5' ) ) {
			add_action( 'woocommerce_checkout_before_customer_details', acs_guard( array( $this, 'checkout_step_1' ) ) );
			add_action( 'woocommerce_checkout_before_order_review_heading', acs_guard( array( $this, 'checkout_step_2' ) ) );
			add_action( 'woocommerce_review_order_before_payment', acs_guard( array( $this, 'payment_strip' ) ) );
		}
		if ( enabled( 'm6' ) ) {
			add_action( 'woocommerce_before_shop_loop', acs_guard( array( $this, 'toolbar' ) ), 6 );
		}
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
	 * Mark the body so the scoped CSS applies; per-module classes gate the
	 * page-level CSS (cart, checkout, events) without extra stylesheets.
	 *
	 * @param array $classes Body classes.
	 * @return array
	 */
	public function body_class( $classes ) {
		$classes[] = 'acs-skin';
		foreach ( array( 'm2', 'm3', 'm4', 'm5', 'm6', 'm8' ) as $m ) {
			if ( enabled( $m ) ) {
				$classes[] = 'acs-has-' . $m;
			}
		}
		return $classes;
	}

	/* ==================== M6 — shop toolbar / filter bar ==================== */

	/**
	 * Replace WooCommerce's/Storefront's result count + ordering row with the
	 * toolbar: category select, sort select, Apply, result count and pills.
	 *
	 * @return void
	 */
	public function toolbar() {
		if ( ! wc_active() ) {
			return;
		}
		// Ours replaces the stock controls (both WC core and Storefront re-hooks).
		foreach ( array( 9, 10, 19, 20, 30 ) as $p ) {
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', $p );
			remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', $p );
			remove_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', $p );
			remove_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', $p );
		}
		if ( function_exists( 'storefront_sorting_wrapper' ) ) {
			remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper', 9 );
			remove_action( 'woocommerce_before_shop_loop', 'storefront_sorting_wrapper_close', 31 );
			remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper', 9 );
			remove_action( 'woocommerce_after_shop_loop', 'storefront_sorting_wrapper_close', 31 );
		}

		$action  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : '';
		$orderby = isset( $_GET['orderby'] ) ? wc_clean( wp_unslash( $_GET['orderby'] ) ) : get_option( 'woocommerce_default_catalog_orderby', 'menu_order' ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		$sorts   = apply_filters(
			'woocommerce_catalog_orderby',
			array(
				'menu_order' => __( 'Default sorting', 'woocommerce' ),
				'popularity' => __( 'Sort by popularity', 'woocommerce' ),
				'rating'     => __( 'Sort by average rating', 'woocommerce' ),
				'date'       => __( 'Sort by latest', 'woocommerce' ),
				'price'      => __( 'Sort by price: low to high', 'woocommerce' ),
				'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
			)
		);
		$total   = function_exists( 'wc_get_loop_prop' ) ? (int) wc_get_loop_prop( 'total' ) : 0;
		$terms   = get_terms(
			array(
				'taxonomy'   => 'product_cat',
				'hide_empty' => true,
				'parent'     => 0,
				'number'     => 12,
			)
		);
		$terms   = is_array( $terms ) ? $terms : array();
		$current = '';
		if ( function_exists( 'is_product_category' ) && is_product_category() ) {
			$qo      = get_queried_object();
			$current = ( $qo && isset( $qo->slug ) ) ? $qo->slug : '';
		} elseif ( isset( $_GET['product_cat'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$current = wc_clean( wp_unslash( $_GET['product_cat'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		?>
		<div class="acs-toolbar" id="acsToolbar">
			<form class="acs-tb-form" method="get" action="<?php echo esc_url( $action ); ?>">
				<?php if ( isset( $_GET['post_type'] ) && 'product' === $_GET['post_type'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
					<input type="hidden" name="post_type" value="product" />
				<?php endif; ?>
				<?php if ( get_search_query() ) : ?>
					<input type="hidden" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" />
				<?php endif; ?>
				<label class="acs-tb-field">
					<span><?php echo esc_html__( 'Category', 'aosars-commerce-skin' ); ?></span>
					<select name="product_cat">
						<option value=""><?php echo esc_html__( 'All categories', 'aosars-commerce-skin' ); ?></option>
						<?php foreach ( $terms as $t ) : ?>
							<option value="<?php echo esc_attr( $t->slug ); ?>" <?php selected( $current, $t->slug ); ?>><?php echo esc_html( $t->name ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<label class="acs-tb-field">
					<span><?php echo esc_html__( 'Sort by', 'aosars-commerce-skin' ); ?></span>
					<select name="orderby">
						<?php foreach ( $sorts as $key => $label ) : ?>
							<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $orderby, $key ); ?>><?php echo esc_html( $label ); ?></option>
						<?php endforeach; ?>
					</select>
				</label>
				<button type="submit" class="acs-tb-apply"><?php echo esc_html__( 'Apply', 'aosars-commerce-skin' ); ?></button>
				<span class="acs-tb-count">
					<?php
					/* translators: %d: number of items. */
					echo esc_html( sprintf( _n( '%d item', '%d items', $total, 'aosars-commerce-skin' ), $total ) );
					?>
				</span>
			</form>
			<?php if ( $terms ) : ?>
				<div class="acs-tb-pills">
					<a class="acs-tb-pill<?php echo '' === $current ? ' is-active' : ''; ?>" href="<?php echo esc_url( $action ); ?>"><?php echo esc_html__( 'All', 'aosars-commerce-skin' ); ?></a>
					<?php foreach ( $terms as $t ) : ?>
						<a class="acs-tb-pill<?php echo $current === $t->slug ? ' is-active' : ''; ?>" href="<?php echo esc_url( get_term_link( $t ) ); ?>"><?php echo esc_html( $t->name ); ?></a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php
	}

	/* ==================== M2 — single product ==================== */

	/**
	 * Mini trust strip under the add-to-cart area of a single product.
	 *
	 * @return void
	 */
	public function single_trust() {
		?>
		<div class="acs-strust" role="list">
			<span role="listitem"><?php echo esc_html__( 'Instant access on payment', 'aosars-commerce-skin' ); ?></span>
			<span role="listitem"><?php echo esc_html__( 'Secure mobile money & card', 'aosars-commerce-skin' ); ?></span>
			<span role="listitem"><?php echo esc_html__( 'Pay in your currency', 'aosars-commerce-skin' ); ?></span>
		</div>
		<?php
	}

	/**
	 * Sticky add-to-cart bar shell; JS reveals it when the summary scrolls away.
	 *
	 * @return void
	 */
	public function sticky_bar() {
		if ( ! wc_active() || ! function_exists( 'is_product' ) || ! is_product() ) {
			return;
		}
		$product = function_exists( 'wc_get_product' ) ? wc_get_product( get_the_ID() ) : null;
		if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			return;
		}
		$ajax = $product->supports( 'ajax_add_to_cart' );
		?>
		<div class="acs-stickybar" id="acsSticky" aria-hidden="true">
			<span class="acs-sb-thumb"><?php echo wp_kses_post( $product->get_image( 'woocommerce_gallery_thumbnail' ) ); ?></span>
			<span class="acs-sb-name"><?php echo esc_html( $product->get_name() ); ?></span>
			<span class="acs-sb-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></span>
			<a href="<?php echo esc_url( $ajax ? '?add-to-cart=' . $product->get_id() : $product->add_to_cart_url() ); ?>"
				data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"
				data-quantity="1"
				aria-label="<?php echo esc_attr( $product->get_name() ); ?>"
				class="acs-sb-add button<?php echo $ajax ? ' add_to_cart_button ajax_add_to_cart' : ''; ?> product_type_<?php echo esc_attr( $product->get_type() ); ?>">
				<?php echo esc_html( $product->add_to_cart_text() ); ?>
			</a>
		</div>
		<?php
	}

	/* ==================== M3 — related products carousel ==================== */

	/**
	 * 3-up related products (the carousel look; JS adds the rail controls).
	 *
	 * @param array $args Related args.
	 * @return array
	 */
	public function related_args( $args ) {
		$args = is_array( $args ) ? $args : array();
		$args['posts_per_page'] = 6;
		$args['columns']        = 3;
		return $args;
	}

	/* ==================== M5 — checkout steps + payment strip ==================== */

	/**
	 * Step heading 1 (classic checkout).
	 *
	 * @return void
	 */
	public function checkout_step_1() {
		echo '<div class="acs-step"><span class="acs-step-n">1</span><b>' . esc_html__( 'Your details', 'aosars-commerce-skin' ) . '</b></div>';
	}

	/**
	 * Step heading 2 (classic checkout).
	 *
	 * @return void
	 */
	public function checkout_step_2() {
		echo '<div class="acs-step"><span class="acs-step-n">2</span><b>' . esc_html__( 'Order & payment', 'aosars-commerce-skin' ) . '</b></div>';
	}

	/**
	 * Accepted-payments strip above the gateway list. Text badges only —
	 * gateway availability itself stays with the payment/currency plugins.
	 *
	 * @return void
	 */
	public function payment_strip() {
		$methods = apply_filters(
			'acs_payment_strip_methods',
			array( 'M-Pesa', 'Airtel Money', 'Visa', 'Mastercard', 'Flutterwave' )
		);
		if ( ! is_array( $methods ) || ! $methods ) {
			return;
		}
		echo '<div class="acs-paystrip" aria-label="' . esc_attr__( 'Accepted payment methods', 'aosars-commerce-skin' ) . '">';
		echo '<span class="acs-ps-t">' . esc_html__( 'We accept', 'aosars-commerce-skin' ) . '</span>';
		foreach ( $methods as $m ) {
			echo '<span class="acs-ps-badge">' . esc_html( $m ) . '</span>';
		}
		echo '</div>';
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
