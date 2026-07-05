<?php
/**
 * Event details meta box: start/end date & time, venue, address, cost, URL.
 *
 * @package SimpleEvents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles the event details meta box and saving of meta fields.
 */
class Simple_Events_Meta {

	const NONCE_ACTION = 'simple_events_save_meta';
	const NONCE_NAME   = 'simple_events_meta_nonce';

	/**
	 * Meta keys stored for each event.
	 *
	 * @var array
	 */
	private static $fields = array(
		'_event_start',   // Y-m-d\TH:i (datetime-local).
		'_event_end',
		'_event_all_day',
		'_event_venue',
		'_event_address',
		'_event_cost',
		'_event_url',
	);

	/**
	 * Register hooks.
	 */
	public function init() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_box' ) );
		add_action( 'save_post_' . Simple_Events_CPT::POST_TYPE, array( $this, 'save' ), 10, 2 );
	}

	/**
	 * Add the meta box to the event editor.
	 */
	public function add_meta_box() {
		add_meta_box(
			'simple_events_details',
			__( 'Event Details', 'simple-events' ),
			array( $this, 'render' ),
			Simple_Events_CPT::POST_TYPE,
			'normal',
			'high'
		);
	}

	/**
	 * Render the meta box fields.
	 *
	 * @param WP_Post $post Current post object.
	 */
	public function render( $post ) {
		wp_nonce_field( self::NONCE_ACTION, self::NONCE_NAME );

		$start   = get_post_meta( $post->ID, '_event_start', true );
		$end     = get_post_meta( $post->ID, '_event_end', true );
		$all_day = get_post_meta( $post->ID, '_event_all_day', true );
		$venue   = get_post_meta( $post->ID, '_event_venue', true );
		$address = get_post_meta( $post->ID, '_event_address', true );
		$cost    = get_post_meta( $post->ID, '_event_cost', true );
		$url     = get_post_meta( $post->ID, '_event_url', true );
		?>
		<div class="simple-events-meta">
			<p>
				<label for="event_start"><strong><?php esc_html_e( 'Start date &amp; time', 'simple-events' ); ?></strong></label><br />
				<input type="datetime-local" id="event_start" name="event_start" value="<?php echo esc_attr( $start ); ?>" />
			</p>
			<p>
				<label for="event_end"><strong><?php esc_html_e( 'End date &amp; time', 'simple-events' ); ?></strong></label><br />
				<input type="datetime-local" id="event_end" name="event_end" value="<?php echo esc_attr( $end ); ?>" />
			</p>
			<p>
				<label for="event_all_day">
					<input type="checkbox" id="event_all_day" name="event_all_day" value="1" <?php checked( $all_day, '1' ); ?> />
					<?php esc_html_e( 'All-day event', 'simple-events' ); ?>
				</label>
			</p>
			<p>
				<label for="event_venue"><strong><?php esc_html_e( 'Venue', 'simple-events' ); ?></strong></label><br />
				<input type="text" id="event_venue" name="event_venue" class="widefat" value="<?php echo esc_attr( $venue ); ?>" />
			</p>
			<p>
				<label for="event_address"><strong><?php esc_html_e( 'Address', 'simple-events' ); ?></strong></label><br />
				<textarea id="event_address" name="event_address" class="widefat" rows="2"><?php echo esc_textarea( $address ); ?></textarea>
			</p>
			<p>
				<label for="event_cost"><strong><?php esc_html_e( 'Cost', 'simple-events' ); ?></strong></label><br />
				<input type="text" id="event_cost" name="event_cost" value="<?php echo esc_attr( $cost ); ?>" placeholder="<?php esc_attr_e( 'e.g. Free, $10', 'simple-events' ); ?>" />
			</p>
			<p>
				<label for="event_url"><strong><?php esc_html_e( 'Event URL', 'simple-events' ); ?></strong></label><br />
				<input type="url" id="event_url" name="event_url" class="widefat" value="<?php echo esc_attr( $url ); ?>" placeholder="https://" />
			</p>
		</div>
		<?php
	}

	/**
	 * Save the meta box fields.
	 *
	 * @param int     $post_id Post ID.
	 * @param WP_Post $post    Post object.
	 */
	public function save( $post_id, $post ) {
		// Verify nonce.
		if ( ! isset( $_POST[ self::NONCE_NAME ] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[ self::NONCE_NAME ] ) ), self::NONCE_ACTION ) ) {
			return;
		}

		// Skip autosaves and revisions.
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( wp_is_post_revision( $post_id ) ) {
			return;
		}

		// Check permissions.
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		$start = isset( $_POST['event_start'] ) ? sanitize_text_field( wp_unslash( $_POST['event_start'] ) ) : '';
		$end   = isset( $_POST['event_end'] ) ? sanitize_text_field( wp_unslash( $_POST['event_end'] ) ) : '';

		update_post_meta( $post_id, '_event_start', $start );
		update_post_meta( $post_id, '_event_end', $end );

		// Store a sortable timestamp for ordering/querying by start date.
		if ( '' !== $start ) {
			$timestamp = strtotime( $start );
			update_post_meta( $post_id, '_event_start_ts', false !== $timestamp ? $timestamp : '' );
		} else {
			delete_post_meta( $post_id, '_event_start_ts' );
		}

		update_post_meta( $post_id, '_event_all_day', isset( $_POST['event_all_day'] ) ? '1' : '' );

		update_post_meta( $post_id, '_event_venue', isset( $_POST['event_venue'] ) ? sanitize_text_field( wp_unslash( $_POST['event_venue'] ) ) : '' );
		update_post_meta( $post_id, '_event_address', isset( $_POST['event_address'] ) ? sanitize_textarea_field( wp_unslash( $_POST['event_address'] ) ) : '' );
		update_post_meta( $post_id, '_event_cost', isset( $_POST['event_cost'] ) ? sanitize_text_field( wp_unslash( $_POST['event_cost'] ) ) : '' );
		update_post_meta( $post_id, '_event_url', isset( $_POST['event_url'] ) ? esc_url_raw( wp_unslash( $_POST['event_url'] ) ) : '' );
	}

	/**
	 * Return the list of managed meta keys.
	 *
	 * @return array
	 */
	public static function fields() {
		return self::$fields;
	}
}
