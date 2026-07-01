<?php
/**
 * [simple_events] shortcode for rendering an events list.
 *
 * @package SimpleEvents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Registers and renders the events shortcode.
 */
class Simple_Events_Shortcode {

	/**
	 * Register the shortcode.
	 */
	public function init() {
		add_shortcode( 'simple_events', array( $this, 'render' ) );
	}

	/**
	 * Render the [simple_events] shortcode.
	 *
	 * Supported attributes:
	 *   limit    - number of events (default 5)
	 *   scope    - upcoming|past|all (default upcoming)
	 *   category - event category slug (default: none)
	 *   show_excerpt - yes|no (default yes)
	 *
	 * @param array $atts Shortcode attributes.
	 * @return string HTML markup.
	 */
	public function render( $atts ) {
		$atts = shortcode_atts(
			array(
				'limit'        => 5,
				'scope'        => 'upcoming',
				'category'     => '',
				'show_excerpt' => 'yes',
			),
			$atts,
			'simple_events'
		);

		$query = Simple_Events_Query::get_events(
			array(
				'limit'    => (int) $atts['limit'],
				'scope'    => sanitize_key( $atts['scope'] ),
				'category' => sanitize_title( $atts['category'] ),
			)
		);

		if ( ! $query->have_posts() ) {
			return '<p class="simple-events-empty">' . esc_html__( 'No events found.', 'simple-events' ) . '</p>';
		}

		$show_excerpt = ( 'yes' === strtolower( $atts['show_excerpt'] ) );

		ob_start();
		echo '<ul class="simple-events-list">';

		while ( $query->have_posts() ) {
			$query->the_post();
			$post_id  = get_the_ID();
			$datetime = Simple_Events_Query::format_datetime( $post_id );
			$venue    = get_post_meta( $post_id, '_event_venue', true );
			?>
			<li class="simple-events-item">
				<h3 class="simple-events-title">
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
				</h3>
				<?php if ( $datetime ) : ?>
					<div class="simple-events-date"><span class="dashicons dashicons-calendar-alt"></span> <?php echo wp_kses_post( $datetime ); ?></div>
				<?php endif; ?>
				<?php if ( $venue ) : ?>
					<div class="simple-events-venue"><span class="dashicons dashicons-location"></span> <?php echo esc_html( $venue ); ?></div>
				<?php endif; ?>
				<?php if ( $show_excerpt && has_excerpt() ) : ?>
					<div class="simple-events-excerpt"><?php the_excerpt(); ?></div>
				<?php endif; ?>
			</li>
			<?php
		}

		echo '</ul>';
		wp_reset_postdata();

		return ob_get_clean();
	}
}
