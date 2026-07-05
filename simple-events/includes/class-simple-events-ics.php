<?php
/**
 * iCalendar (.ics) export for single events.
 *
 * Adds a downloadable calendar file for each event via the `ics` query var,
 * e.g. /events/my-event/?ics=1, and an "Add to Calendar" link on single
 * event pages.
 *
 * @package SimpleEvents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Generates and serves .ics files for events.
 */
class Simple_Events_ICS {

	const QUERY_VAR = 'ics';

	/**
	 * Register hooks.
	 */
	public function init() {
		add_filter( 'query_vars', array( $this, 'register_query_var' ) );
		add_action( 'template_redirect', array( $this, 'maybe_output_ics' ) );
	}

	/**
	 * Register the `ics` query var.
	 *
	 * @param array $vars Existing query vars.
	 * @return array
	 */
	public function register_query_var( $vars ) {
		$vars[] = self::QUERY_VAR;
		return $vars;
	}

	/**
	 * Build the .ics download URL for an event.
	 *
	 * @param int $post_id Event post ID.
	 * @return string
	 */
	public static function get_download_url( $post_id ) {
		return add_query_arg( self::QUERY_VAR, '1', get_permalink( $post_id ) );
	}

	/**
	 * Serve the .ics file when the query var is present on a single event.
	 */
	public function maybe_output_ics() {
		if ( ! is_singular( Simple_Events_CPT::POST_TYPE ) ) {
			return;
		}

		if ( '' === get_query_var( self::QUERY_VAR, '' ) ) {
			return;
		}

		$post_id = get_queried_object_id();
		if ( ! $post_id ) {
			return;
		}

		$this->send_headers( get_post_field( 'post_name', $post_id ) );
		echo $this->build_ics( $post_id ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Plain-text calendar output, values escaped in build_ics().
		exit;
	}

	/**
	 * Send the HTTP headers for the calendar download.
	 *
	 * @param string $slug Event slug used for the filename.
	 */
	private function send_headers( $slug ) {
		$filename = sanitize_file_name( ( $slug ? $slug : 'event' ) . '.ics' );

		nocache_headers();
		header( 'Content-Type: text/calendar; charset=utf-8' );
		header( 'Content-Disposition: attachment; filename="' . $filename . '"' );
	}

	/**
	 * Build the iCalendar document for an event.
	 *
	 * @param int $post_id Event post ID.
	 * @return string
	 */
	public function build_ics( $post_id ) {
		$start_ts = get_post_meta( $post_id, '_event_start_ts', true );
		$all_day  = '1' === get_post_meta( $post_id, '_event_all_day', true );
		$end_raw  = get_post_meta( $post_id, '_event_end', true );
		$end_ts   = ( '' !== $end_raw ) ? strtotime( $end_raw ) : false;

		$title    = get_the_title( $post_id );
		$venue    = get_post_meta( $post_id, '_event_venue', true );
		$address  = get_post_meta( $post_id, '_event_address', true );
		$location = trim( $venue . ( $venue && $address ? ', ' : '' ) . str_replace( array( "\r\n", "\n", "\r" ), ', ', $address ) );

		$description = wp_strip_all_tags( get_the_excerpt( $post_id ) );

		// UID + timestamps. Use GMT for DTSTAMP/UTC values.
		$uid   = 'simple-event-' . $post_id . '@' . wp_parse_url( home_url(), PHP_URL_HOST );
		$stamp = gmdate( 'Ymd\THis\Z' );

		$lines   = array();
		$lines[] = 'BEGIN:VCALENDAR';
		$lines[] = 'VERSION:2.0';
		$lines[] = 'PRODID:-//Simple Events//EN';
		$lines[] = 'CALSCALE:GREGORIAN';
		$lines[] = 'METHOD:PUBLISH';
		$lines[] = 'BEGIN:VEVENT';
		$lines[] = 'UID:' . $uid;
		$lines[] = 'DTSTAMP:' . $stamp;

		if ( '' !== $start_ts ) {
			$start_ts = (int) $start_ts;
			if ( $all_day ) {
				$lines[] = 'DTSTART;VALUE=DATE:' . gmdate( 'Ymd', $start_ts );
				// For all-day events, DTEND is exclusive (next day).
				$end_day = $end_ts ? $end_ts : $start_ts;
				$lines[] = 'DTEND;VALUE=DATE:' . gmdate( 'Ymd', strtotime( '+1 day', $end_day ) );
			} else {
				$lines[] = 'DTSTART:' . self::to_utc( $start_ts );
				if ( $end_ts ) {
					$lines[] = 'DTEND:' . self::to_utc( $end_ts );
				}
			}
		}

		$lines[] = 'SUMMARY:' . self::escape( $title );
		if ( $location ) {
			$lines[] = 'LOCATION:' . self::escape( $location );
		}
		if ( $description ) {
			$lines[] = 'DESCRIPTION:' . self::escape( $description );
		}
		$lines[] = 'URL:' . self::escape( get_permalink( $post_id ) );
		$lines[] = 'END:VEVENT';
		$lines[] = 'END:VCALENDAR';

		// Fold long lines and join with CRLF per RFC 5545.
		$folded = array_map( array( __CLASS__, 'fold' ), $lines );

		return implode( "\r\n", $folded ) . "\r\n";
	}

	/**
	 * Convert a local timestamp to a UTC iCalendar datetime string.
	 *
	 * The stored timestamp represents the site's local time, so we offset by
	 * the site's GMT offset to produce a correct UTC value.
	 *
	 * @param int $timestamp Local timestamp.
	 * @return string
	 */
	private static function to_utc( $timestamp ) {
		$offset = (float) get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		return gmdate( 'Ymd\THis\Z', $timestamp - $offset );
	}

	/**
	 * Escape a value for an iCalendar text field.
	 *
	 * @param string $value Raw value.
	 * @return string
	 */
	private static function escape( $value ) {
		$value = str_replace( array( '\\', ';', ',' ), array( '\\\\', '\\;', '\\,' ), $value );
		$value = str_replace( array( "\r\n", "\n", "\r" ), '\\n', $value );
		return $value;
	}

	/**
	 * Fold a content line to 75 octets per RFC 5545.
	 *
	 * @param string $line Content line.
	 * @return string
	 */
	public static function fold( $line ) {
		if ( strlen( $line ) <= 75 ) {
			return $line;
		}

		$folded = '';
		$chunk  = 75;
		while ( strlen( $line ) > $chunk ) {
			$folded .= substr( $line, 0, $chunk ) . "\r\n ";
			$line    = substr( $line, $chunk );
			$chunk   = 74; // Subsequent lines start with a leading space.
		}
		$folded .= $line;

		return $folded;
	}
}
