<?php
/**
 * Plugin Name:       AOSARS Doctor
 * Description:       Read-only diagnostics for AOSARS Events. Tools → AOSARS Doctor shows which plugin copies exist, which version is running, whether time conversion is correct, and the raw schedule data saved on each event — as one copy-paste report. Safe to install alongside anything; changes nothing.
 * Version:           1.1.0
 * Author:            Karanja Maina
 * License:           GPL-2.0-or-later
 * Update URI:        false
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

add_action( 'admin_menu', 'aosdoc_menu' );
function aosdoc_menu() {
	add_management_page( 'AOSARS Doctor', 'AOSARS Doctor', 'manage_options', 'aosars-doctor', 'aosdoc_page' );
}

function aosdoc_page() {
	$L   = array();
	$L[] = 'AOSARS DOCTOR REPORT — ' . gmdate( 'Y-m-d H:i' ) . ' UTC';
	$L[] = 'Site: ' . home_url() . ' | WordPress ' . get_bloginfo( 'version' ) . ' | PHP ' . PHP_VERSION;
	$L[] = '';

	$L[] = '== Running AOSARS Events code ==';
	$L[] = 'AOSEV_VER at runtime: ' . ( defined( 'AOSEV_VER' ) ? AOSEV_VER : 'NOT DEFINED — the plugin is not running at all' );
	if ( function_exists( 'aosev_ts' ) ) {
		$p   = aosev_ts( '2026-01-15T14:00', 'Africa/Nairobi' );
		$L[] = 'Time probe (15 Jan 2026 14:00 EAT): epoch ' . $p . ' → ' . ( 1768474800 === $p ? 'CORRECT' : 'WRONG (report this)' );
	} else {
		$L[] = 'Time probe: aosev_ts() missing — running version is older than 5.7.0';
	}
	$feat   = array(
		'aosev_el_doc_controls' => 'Elementor entry panel (6.0.0)',
		'aosev_integrity_notice' => 'integrity notices (5.9.0)',
		'aosev_box_script'      => 'live date preview (5.8.0)',
		'aosev_timezones'       => 'timezone entry (5.7.0)',
	);
	foreach ( $feat as $fn => $label ) { $L[] = 'Feature ' . $label . ': ' . ( function_exists( $fn ) ? 'present' : 'ABSENT' ); }
	$L[] = '';

	$L[] = '== Installed plugin copies matching "aosars" ==';
	if ( ! function_exists( 'get_plugins' ) ) { require_once ABSPATH . 'wp-admin/includes/plugin.php'; }
	$active = (array) get_option( 'active_plugins', array() );
	$found  = 0;
	foreach ( get_plugins() as $file => $d ) {
		if ( false === stripos( $file . ' ' . $d['Name'], 'aosars' ) ) { continue; }
		$found++;
		$L[] = sprintf( '%s | %s v%s | %s', $file, $d['Name'], $d['Version'], in_array( $file, $active, true ) ? 'ACTIVE' : 'inactive' );
	}
	if ( ! $found ) { $L[] = '(none found)'; }
	$L[] = '';

	$L[] = '== Last 10 events — RAW saved schedule data ==';
	$events = get_posts( array( 'post_type' => 'aosars_event', 'post_status' => 'any', 'numberposts' => 10 ) );
	if ( ! $events ) { $L[] = '(no events found)'; }
	foreach ( $events as $ev ) {
		$g   = function ( $k ) use ( $ev ) { return (string) get_post_meta( $ev->ID, '_aosev_' . $k, true ); };
		$L[] = sprintf(
			'#%d "%s" [%s] | start=%s | end=%s | tz=%s | format=%s | platform=%s | join_url=%s | venue=%s',
			$ev->ID, $ev->post_title, $ev->post_status,
			$g( 'start' ) !== '' ? $g( 'start' ) : '(EMPTY — this is why no date/countdown shows)',
			$g( 'end' ) !== '' ? $g( 'end' ) : '(empty)',
			$g( 'tzone' ) !== '' ? $g( 'tzone' ) : '(default EAT)',
			$g( 'mode' ) !== '' ? $g( 'mode' ) : '(default Online)',
			$g( 'platform' ) !== '' ? $g( 'platform' ) : '(default Google Meet)',
			$g( 'join_url' ) !== '' ? $g( 'join_url' ) : '(EMPTY — no Join button will show)',
			$g( 'venue' ) !== '' ? $g( 'venue' ) : '(empty)'
		);
		// What Elementor's own settings store holds for this event (where a value hides
		// when the save-hook sync misses it; v6.1.0 reads + backfills from here).
		$ps = get_post_meta( $ev->ID, '_elementor_page_settings', true );
		if ( is_array( $ps ) ) {
			$sub = array();
			foreach ( $ps as $k => $v ) {
				if ( 0 === strpos( (string) $k, 'aosev_' ) && ! is_array( $v ) ) { $sub[] = $k . '=' . $v; }
			}
			$L[] = '     elementor_page_settings: ' . ( $sub ? implode( ' | ', $sub ) : '(no aosev_* keys)' ) . ' [total keys: ' . count( $ps ) . ']';
		} else {
			$L[] = '     elementor_page_settings: (none)';
		}
	}
	$L[] = '';
	$L[] = 'END OF REPORT';

	$txt = implode( "\n", $L );
	echo '<div class="wrap"><h1>AOSARS Doctor</h1>';
	echo '<p><strong>' . esc_html__( 'Copy everything in the box below and send it for analysis. This page changes nothing on the site.', 'aosars-doctor' ) . '</strong></p>';
	echo '<textarea readonly style="width:100%;height:460px;font-family:ui-monospace,Menlo,Consolas,monospace;font-size:12.5px" onclick="this.select()">' . esc_textarea( $txt ) . '</textarea>';
	echo '</div>';
}
