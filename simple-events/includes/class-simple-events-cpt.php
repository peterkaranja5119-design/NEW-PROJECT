<?php
/**
 * Registers the "event" custom post type and its taxonomy.
 *
 * @package SimpleEvents
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Custom post type + taxonomy registration.
 */
class Simple_Events_CPT {

	const POST_TYPE = 'event';
	const TAXONOMY  = 'event_category';

	/**
	 * Hook registration into WordPress init.
	 */
	public function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
	}

	/**
	 * Register the post type and taxonomy.
	 */
	public static function register() {
		self::register_post_type();
		self::register_taxonomy();
	}

	/**
	 * Register the "event" post type.
	 */
	public static function register_post_type() {
		$labels = array(
			'name'                  => _x( 'Events', 'Post type general name', 'simple-events' ),
			'singular_name'         => _x( 'Event', 'Post type singular name', 'simple-events' ),
			'menu_name'             => _x( 'Events', 'Admin Menu text', 'simple-events' ),
			'name_admin_bar'        => _x( 'Event', 'Add New on Toolbar', 'simple-events' ),
			'add_new'               => __( 'Add New', 'simple-events' ),
			'add_new_item'          => __( 'Add New Event', 'simple-events' ),
			'new_item'              => __( 'New Event', 'simple-events' ),
			'edit_item'             => __( 'Edit Event', 'simple-events' ),
			'view_item'             => __( 'View Event', 'simple-events' ),
			'all_items'             => __( 'All Events', 'simple-events' ),
			'search_items'          => __( 'Search Events', 'simple-events' ),
			'not_found'             => __( 'No events found.', 'simple-events' ),
			'not_found_in_trash'    => __( 'No events found in Trash.', 'simple-events' ),
			'featured_image'        => __( 'Event Image', 'simple-events' ),
			'set_featured_image'    => __( 'Set event image', 'simple-events' ),
			'remove_featured_image' => __( 'Remove event image', 'simple-events' ),
			'archives'              => __( 'Event Archives', 'simple-events' ),
		);

		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'has_archive'        => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-calendar-alt',
			'menu_position'      => 20,
			'rewrite'            => array( 'slug' => 'events' ),
			'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
			'capability_type'    => 'post',
		);

		register_post_type( self::POST_TYPE, apply_filters( 'simple_events_post_type_args', $args ) );
	}

	/**
	 * Register the "event_category" taxonomy.
	 */
	public static function register_taxonomy() {
		$labels = array(
			'name'              => _x( 'Event Categories', 'taxonomy general name', 'simple-events' ),
			'singular_name'     => _x( 'Event Category', 'taxonomy singular name', 'simple-events' ),
			'search_items'      => __( 'Search Event Categories', 'simple-events' ),
			'all_items'         => __( 'All Event Categories', 'simple-events' ),
			'edit_item'         => __( 'Edit Event Category', 'simple-events' ),
			'update_item'       => __( 'Update Event Category', 'simple-events' ),
			'add_new_item'      => __( 'Add New Event Category', 'simple-events' ),
			'new_item_name'     => __( 'New Event Category Name', 'simple-events' ),
			'menu_name'         => __( 'Categories', 'simple-events' ),
		);

		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_in_rest'      => true,
			'show_admin_column' => true,
			'rewrite'           => array( 'slug' => 'event-category' ),
		);

		register_taxonomy( self::TAXONOMY, array( self::POST_TYPE ), apply_filters( 'simple_events_taxonomy_args', $args ) );
	}
}
