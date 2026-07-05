<?php
/**
 * Upcoming Events widget.
 *
 * @package SimpleEvents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Displays a list of upcoming events in a sidebar.
 */
class Simple_Events_Widget extends WP_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		parent::__construct(
			'simple_events_widget',
			__( 'Upcoming Events', 'simple-events' ),
			array(
				'description' => __( 'Displays a list of upcoming events.', 'simple-events' ),
			)
		);
	}

	/**
	 * Front-end display of the widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved widget settings.
	 */
	public function widget( $args, $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Upcoming Events', 'simple-events' );
		$title = apply_filters( 'widget_title', $title, $instance, $this->id_base );
		$limit = ! empty( $instance['limit'] ) ? absint( $instance['limit'] ) : 5;

		$query = Simple_Events_Query::get_events(
			array(
				'limit' => $limit,
				'scope' => 'upcoming',
			)
		);

		echo wp_kses_post( $args['before_widget'] );

		if ( $title ) {
			echo wp_kses_post( $args['before_title'] . esc_html( $title ) . $args['after_title'] );
		}

		if ( $query->have_posts() ) {
			echo '<ul class="simple-events-widget-list">';
			while ( $query->have_posts() ) {
				$query->the_post();
				$datetime = Simple_Events_Query::format_datetime( get_the_ID() );
				?>
				<li>
					<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					<?php if ( $datetime ) : ?>
						<span class="simple-events-widget-date"><?php echo wp_kses_post( $datetime ); ?></span>
					<?php endif; ?>
				</li>
				<?php
			}
			echo '</ul>';
			wp_reset_postdata();
		} else {
			echo '<p>' . esc_html__( 'No upcoming events.', 'simple-events' ) . '</p>';
		}

		echo wp_kses_post( $args['after_widget'] );
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Saved settings.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : __( 'Upcoming Events', 'simple-events' );
		$limit = ! empty( $instance['limit'] ) ? absint( $instance['limit'] ) : 5;
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'simple-events' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>"><?php esc_html_e( 'Number of events to show:', 'simple-events' ); ?></label>
			<input class="tiny-text" id="<?php echo esc_attr( $this->get_field_id( 'limit' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'limit' ) ); ?>" type="number" step="1" min="1" value="<?php echo esc_attr( $limit ); ?>" size="3" />
		</p>
		<?php
	}

	/**
	 * Sanitize widget form values on save.
	 *
	 * @param array $new_instance New settings.
	 * @param array $old_instance Old settings.
	 * @return array Sanitized settings.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance          = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? sanitize_text_field( $new_instance['title'] ) : '';
		$instance['limit'] = ( ! empty( $new_instance['limit'] ) ) ? absint( $new_instance['limit'] ) : 5;
		return $instance;
	}
}
