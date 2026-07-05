<?php
/**
 * Query helpers and template formatting for events.
 *
 * @package SimpleEvents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Provides reusable event queries and display helpers.
 */
class Simple_Events_Query {

	/**
	 * Register hooks that affect the main event queries.
	 */
	public function init() {
		add_action( 'pre_get_posts', array( $this, 'order_event_archive' ) );
	}

	/**
	 * Order the event archive by start date ascending (soonest first).
	 *
	 * @param WP_Query $query The query object.
	 */
	public function order_event_archive( $query ) {
		if ( is_admin() || ! $query->is_main_query() ) {
			return;
		}

		if ( $query->is_post_type_archive( Simple_Events_CPT::POST_TYPE ) || $query->is_tax( Simple_Events_CPT::TAXONOMY ) ) {
			$query->set( 'meta_key', '_event_start_ts' );
			$query->set( 'orderby', 'meta_value_num' );
			$query->set( 'order', 'ASC' );
		}
	}

	/**
	 * Retrieve events.
	 *
	 * @param array $args {
	 *     Optional. Query arguments.
	 *
	 *     @type int    $limit    Number of events. Default 5.
	 *     @type string $scope    'upcoming', 'past', or 'all'. Default 'upcoming'.
	 *     @type string $order    'ASC' or 'DESC'. Default depends on scope.
	 *     @type string $category Event category slug. Default ''.
	 * }
	 * @return WP_Query
	 */
	public static function get_events( $args = array() ) {
		$defaults = array(
			'limit'    => 5,
			'scope'    => 'upcoming',
			'order'    => '',
			'category' => '',
		);
		$args = wp_parse_args( $args, $defaults );

		$order = $args['order'];
		if ( '' === $order ) {
			$order = ( 'past' === $args['scope'] ) ? 'DESC' : 'ASC';
		}

		$query_args = array(
			'post_type'      => Simple_Events_CPT::POST_TYPE,
			'post_status'    => 'publish',
			'posts_per_page' => (int) $args['limit'],
			'meta_key'       => '_event_start_ts',
			'orderby'        => 'meta_value_num',
			'order'          => $order,
			'no_found_rows'  => true,
		);

		// Filter by upcoming/past using the stored timestamp.
		if ( 'all' !== $args['scope'] ) {
			$now        = current_time( 'timestamp' );
			$comparison = ( 'past' === $args['scope'] ) ? '<' : '>=';

			$query_args['meta_query'] = array(
				array(
					'key'     => '_event_start_ts',
					'value'   => $now,
					'compare' => $comparison,
					'type'    => 'NUMERIC',
				),
			);
		}

		if ( ! empty( $args['category'] ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => Simple_Events_CPT::TAXONOMY,
					'field'    => 'slug',
					'terms'    => sanitize_title( $args['category'] ),
				),
			);
		}

		return new WP_Query( $query_args );
	}

	/**
	 * Format an event's date/time range for display.
	 *
	 * @param int $post_id Event post ID.
	 * @return string Human-readable date range, or empty string when no start set.
	 */
	public static function format_datetime( $post_id ) {
		$start_ts = get_post_meta( $post_id, '_event_start_ts', true );
		if ( '' === $start_ts ) {
			return '';
		}

		$all_day     = '1' === get_post_meta( $post_id, '_event_all_day', true );
		$end_raw     = get_post_meta( $post_id, '_event_end', true );
		$end_ts      = ( '' !== $end_raw ) ? strtotime( $end_raw ) : false;
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		$start_ts = (int) $start_ts;

		if ( $all_day ) {
			$start_str = wp_date( $date_format, $start_ts );
			if ( $end_ts && wp_date( 'Y-m-d', $end_ts ) !== wp_date( 'Y-m-d', $start_ts ) ) {
				return $start_str . ' &ndash; ' . wp_date( $date_format, $end_ts );
			}
			return $start_str;
		}

		$start_str = wp_date( $date_format . ' ' . $time_format, $start_ts );

		if ( ! $end_ts ) {
			return $start_str;
		}

		// Same day: show date once, then time range.
		if ( wp_date( 'Y-m-d', $end_ts ) === wp_date( 'Y-m-d', $start_ts ) ) {
			return $start_str . ' &ndash; ' . wp_date( $time_format, $end_ts );
		}

		return $start_str . ' &ndash; ' . wp_date( $date_format . ' ' . $time_format, $end_ts );
	}
}
