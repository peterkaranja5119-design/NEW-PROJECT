<?php
/**
 * AOSARS Commerce Skin — redesigned shop/archive product card.
 *
 * Override of WooCommerce content-product.php served via the
 * woocommerce_locate_template filter. Uses only WooCommerce APIs and
 * escapes all output. Falls back safely if $product is unavailable.
 *
 * @package AOSARS_Commerce_Skin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( empty( $product ) || ! is_a( $product, 'WC_Product' ) || ! $product->is_visible() ) {
	return;
}

$acs_link    = get_permalink( $product->get_id() );
$acs_name    = $product->get_name();
$acs_is_course = false;
if ( function_exists( 'has_term' ) ) {
	$acs_is_course = has_term( array( 'e-course', 'courses', 'course', 'e-courses' ), 'product_cat', $product->get_id() );
}
$acs_cat = '';
if ( function_exists( 'wc_get_product_category_list' ) ) {
	$acs_cat = wp_strip_all_tags( wc_get_product_category_list( $product->get_id() ) );
	$acs_cat = trim( explode( ',', $acs_cat )[0] );
}
$acs_rating = (float) $product->get_average_rating();
?>
<li <?php wc_product_class( 'acs-pcard' . ( $acs_is_course ? ' acs-course' : '' ), $product ); ?>>
	<div class="acs-pc-media">
		<?php if ( $product->is_on_sale() ) : ?>
			<div class="acs-pc-flags"><span class="acs-pc-flag acs-sale"><?php echo esc_html( \ACS\sale_badge( $product ) ); ?></span></div>
		<?php elseif ( $product->is_featured() ) : ?>
			<div class="acs-pc-flags"><span class="acs-pc-flag acs-feat"><?php echo esc_html__( 'Featured', 'aosars-commerce-skin' ); ?></span></div>
		<?php endif; ?>

		<a class="acs-pc-img" href="<?php echo esc_url( $acs_link ); ?>" aria-label="<?php echo esc_attr( $acs_name ); ?>">
			<?php echo wp_kses_post( $product->get_image( 'woocommerce_thumbnail' ) ); ?>
		</a>

		<div class="acs-pc-quick">
			<a class="acs-pc-quick-btn" href="<?php echo esc_url( $acs_link ); ?>">
				<?php echo $acs_is_course ? esc_html__( 'Preview course', 'aosars-commerce-skin' ) : esc_html__( 'View', 'aosars-commerce-skin' ); ?>
			</a>
		</div>
	</div>

	<div class="acs-pc-body">
		<?php if ( $acs_cat ) : ?>
			<span class="acs-pc-cat"><?php echo esc_html( $acs_cat ); ?></span>
		<?php endif; ?>

		<h3 class="acs-pc-title">
			<a href="<?php echo esc_url( $acs_link ); ?>"><?php echo esc_html( $acs_name ); ?></a>
		</h3>

		<?php if ( function_exists( 'wc_review_ratings_enabled' ) && wc_review_ratings_enabled() && $acs_rating > 0 ) : ?>
			<div class="acs-pc-rating">
				<span class="acs-pc-stars" aria-hidden="true"><?php echo esc_html( \ACS\stars( $acs_rating ) ); ?></span>
				<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s rating */ __( 'Rated %s out of 5', 'aosars-commerce-skin' ), number_format( $acs_rating, 1 ) ) ); ?></span>
				<?php echo esc_html( number_format( $acs_rating, 1 ) ); ?>
			</div>
		<?php endif; ?>

		<div class="acs-pc-foot">
			<div class="acs-pc-price"><?php echo wp_kses_post( $product->get_price_html() ); ?></div>
			<?php
			// A custom 'class' REPLACES WooCommerce's default button classes
			// (wp_parse_args does not merge strings), so rebuild the full list:
			// without add_to_cart_button/ajax_add_to_cart the click falls back to
			// a full page load and the added_to_cart event (mini-cart drawer)
			// never fires.
			$acs_ajax = $product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock();
			woocommerce_template_loop_add_to_cart(
				array(
					'class' => implode(
						' ',
						array_filter(
							array(
								'acs-pc-add',
								'button',
								function_exists( 'wc_wp_theme_get_element_class_name' ) ? wc_wp_theme_get_element_class_name( 'button' ) : '',
								'product_type_' . $product->get_type(),
								$acs_ajax ? 'add_to_cart_button' : '',
								$acs_ajax ? 'ajax_add_to_cart' : '',
							)
						)
					),
				)
			);
			?>
		</div>
	</div>
</li>
