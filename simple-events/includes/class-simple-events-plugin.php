<?php
/**
 * Main plugin bootstrap: wires up all components.
 *
 * @package SimpleEvents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Singleton that initializes every part of the plugin.
 */
class Simple_Events_Plugin {

	/**
	 * Single instance.
	 *
	 * @var Simple_Events_Plugin|null
	 */
	private static $instance = null;

	/**
	 * Get the singleton instance.
	 *
	 * @return Simple_Events_Plugin
	 */
	public static function instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor: instantiate components and register hooks.
	 */
	private function __construct() {
		( new Simple_Events_CPT() )->init();
		( new Simple_Events_Meta() )->init();
		( new Simple_Events_Query() )->init();
		( new Simple_Events_Shortcode() )->init();
		( new Simple_Events_ICS() )->init();
		( new Simple_Events_Block() )->init();

		add_action( 'plugins_loaded', array( $this, 'load_textdomain' ) );
		add_action( 'widgets_init', array( $this, 'register_widget' ) );
		add_action( 'init', array( $this, 'register_assets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );

		// Admin: sortable columns showing the event date.
		add_filter( 'manage_' . Simple_Events_CPT::POST_TYPE . '_posts_columns', array( $this, 'add_admin_columns' ) );
		add_action( 'manage_' . Simple_Events_CPT::POST_TYPE . '_posts_custom_column', array( $this, 'render_admin_column' ), 10, 2 );
		add_filter( 'manage_edit-' . Simple_Events_CPT::POST_TYPE . '_sortable_columns', array( $this, 'sortable_admin_columns' ) );
		add_action( 'pre_get_posts', array( $this, 'admin_column_orderby' ) );

		// Front-end: prepend event details to single event content.
		add_filter( 'the_content', array( $this, 'prepend_event_details' ) );
	}

	/**
	 * Load translations.
	 */
	public function load_textdomain() {
		load_plugin_textdomain( 'simple-events', false, dirname( plugin_basename( SIMPLE_EVENTS_FILE ) ) . '/languages' );
	}

	/**
	 * Register the widget.
	 */
	public function register_widget() {
		register_widget( 'Simple_Events_Widget' );
	}

	/**
	 * Register the stylesheet handle so both the front-end enqueue and the
	 * block's declared `style` can reference it.
	 */
	public function register_assets() {
		wp_register_style(
			'simple-events',
			SIMPLE_EVENTS_URL . 'assets/css/simple-events.css',
			array( 'dashicons' ),
			SIMPLE_EVENTS_VERSION
		);
	}

	/**
	 * Enqueue front-end styles.
	 */
	public function enqueue_assets() {
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style( 'simple-events' );
	}

	/**
	 * Add an "Event Date" column to the admin list table.
	 *
	 * @param array $columns Existing columns.
	 * @return array
	 */
	public function add_admin_columns( $columns ) {
		$new = array();
		foreach ( $columns as $key => $label ) {
			$new[ $key ] = $label;
			if ( 'title' === $key ) {
				$new['event_date'] = __( 'Event Date', 'simple-events' );
			}
		}
		return $new;
	}

	/**
	 * Render the custom admin column value.
	 *
	 * @param string $column  Column key.
	 * @param int    $post_id Post ID.
	 */
	public function render_admin_column( $column, $post_id ) {
		if ( 'event_date' === $column ) {
			$datetime = Simple_Events_Query::format_datetime( $post_id );
			echo $datetime ? wp_kses_post( $datetime ) : '&mdash;';
		}
	}

	/**
	 * Make the event date column sortable.
	 *
	 * @param array $columns Sortable columns.
	 * @return array
	 */
	public function sortable_admin_columns( $columns ) {
		$columns['event_date'] = 'event_date';
		return $columns;
	}

	/**
	 * Order the admin list by event start when sorting the date column.
	 *
	 * @param WP_Query $query Query object.
	 */
	public function admin_column_orderby( $query ) {
		if ( ! is_admin() || ! $query->is_main_query() ) {
			return;
		}
		if ( 'event_date' === $query->get( 'orderby' ) ) {
			$query->set( 'meta_key', '_event_start_ts' );
			$query->set( 'orderby', 'meta_value_num' );
		}
	}

	/**
	 * Prepend formatted event details above the content on single event pages.
	 *
	 * @param string $content Post content.
	 * @return string
	 */
	public function prepend_event_details( $content ) {
		if ( ! is_singular( Simple_Events_CPT::POST_TYPE ) || ! in_the_loop() || ! is_main_query() ) {
			return $content;
		}

		$post_id  = get_the_ID();
		$datetime = Simple_Events_Query::format_datetime( $post_id );
		$venue    = get_post_meta( $post_id, '_event_venue', true );
		$address  = get_post_meta( $post_id, '_event_address', true );
		$cost     = get_post_meta( $post_id, '_event_cost', true );
		$url      = get_post_meta( $post_id, '_event_url', true );

		if ( ! $datetime && ! $venue && ! $address && ! $cost && ! $url ) {
			return $content;
		}

		ob_start();
		echo '<div class="simple-events-details">';
		echo '<ul class="simple-events-details-list">';

		if ( $datetime ) {
			printf(
				'<li class="simple-events-detail-date"><strong>%s</strong> %s</li>',
				esc_html__( 'When:', 'simple-events' ),
				wp_kses_post( $datetime )
			);
		}
		if ( $venue ) {
			printf(
				'<li class="simple-events-detail-venue"><strong>%s</strong> %s</li>',
				esc_html__( 'Venue:', 'simple-events' ),
				esc_html( $venue )
			);
		}
		if ( $address ) {
			printf(
				'<li class="simple-events-detail-address"><strong>%s</strong> %s</li>',
				esc_html__( 'Address:', 'simple-events' ),
				nl2br( esc_html( $address ) )
			);
		}
		if ( $cost ) {
			printf(
				'<li class="simple-events-detail-cost"><strong>%s</strong> %s</li>',
				esc_html__( 'Cost:', 'simple-events' ),
				esc_html( $cost )
			);
		}
		if ( $url ) {
			printf(
				'<li class="simple-events-detail-url"><strong>%s</strong> <a href="%s" target="_blank" rel="noopener noreferrer">%s</a></li>',
				esc_html__( 'More info:', 'simple-events' ),
				esc_url( $url ),
				esc_html( $url )
			);
		}

		echo '</ul>';

		// "Add to Calendar" (.ics) download link.
		if ( $datetime ) {
			printf(
				'<p class="simple-events-add-to-calendar"><a class="simple-events-ics-link" href="%s"><span class="dashicons dashicons-calendar-alt"></span> %s</a></p>',
				esc_url( Simple_Events_ICS::get_download_url( $post_id ) ),
				esc_html__( 'Add to Calendar', 'simple-events' )
			);
		}

		// Google Maps embed based on the address.
		if ( $address && apply_filters( 'simple_events_show_map', true, $post_id ) ) {
			$query   = trim( $venue . ' ' . $address );
			$map_src = 'https://maps.google.com/maps?q=' . rawurlencode( $query ) . '&output=embed';
			printf(
				'<div class="simple-events-map"><iframe title="%1$s" src="%2$s" width="100%%" height="300" style="border:0;" loading="lazy" referrerpolicy="no-referrer-when-downgrade" allowfullscreen></iframe></div>',
				esc_attr__( 'Event location map', 'simple-events' ),
				esc_url( $map_src )
			);
		}

		echo '</div>';

		return ob_get_clean() . $content;
	}
}
