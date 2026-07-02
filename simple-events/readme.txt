=== Simple Events ===
Contributors: simpleevents
Tags: events, calendar, event management, custom post type, shortcode
Requires at least: 5.6
Tested up to: 6.5
Requires PHP: 7.2
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Manage and display events with a custom post type, event details, shortcodes, and a widget.

== Description ==

Simple Events is a lightweight WordPress plugin for publishing events. It adds
an "Events" post type with fields for start/end date and time, all-day flag,
venue, address, cost, and an external URL. Events can be organized into
categories and displayed anywhere using a shortcode or the bundled widget.

Features:

* Custom "Event" post type with a calendar dashboard icon.
* Event Categories taxonomy.
* Event details meta box (start/end datetime, all-day, venue, address, cost, URL).
* Front-end details box automatically shown on single event pages.
* "Add to Calendar" download (RFC 5545 .ics file) on single event pages.
* Google Maps embed of the event location (no API key required).
* Page builder compatible: bundled single-event template lets Elementor edit events.
* `[simple_events]` shortcode with attributes for limit, scope, and category.
* "Events List" block for the block editor (Gutenberg).
* "Upcoming Events" widget.
* Admin list table with a sortable "Event Date" column.
* Event archive automatically ordered by start date.
* Translation ready.

== Installation ==

1. Upload the `simple-events` folder to `/wp-content/plugins/`.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Add events via the new "Events" menu item.

== Usage ==

Shortcode:

`[simple_events limit="5" scope="upcoming" category="" show_excerpt="yes"]`

Attributes:

* `limit` — number of events to show (default 5).
* `scope` — `upcoming`, `past`, or `all` (default `upcoming`).
* `category` — an event category slug to filter by (default: none).
* `show_excerpt` — `yes` or `no` (default `yes`).

Examples:

`[simple_events limit="3"]`
`[simple_events scope="past" limit="10"]`
`[simple_events category="workshops"]`

== Frequently Asked Questions ==

= How are events ordered? =

Upcoming events are ordered by start date, soonest first. Past events are
ordered most-recent first.

= Can I filter events by category? =

Yes. Assign an Event Category to an event, then pass the category slug to the
shortcode's `category` attribute.

= Do visitors get an "Add to Calendar" option? =

Yes. Each single event page includes an "Add to Calendar" link that downloads a
standard .ics file, which works with Google Calendar, Apple Calendar, and
Outlook.

= Does the map require a Google Maps API key? =

No. The map uses Google's keyless embed. You can hide the map for a specific
event with the `simple_events_show_map` filter.

= Elementor says "you must call the the_content function." How do I fix it? =

This happens when your theme's template for custom post types does not call
the_content(). The plugin includes a fallback single-event template that does,
so "Edit with Elementor" works on events automatically. Your theme can override
it with single-event.php or simple-events/single-event.php, or you can disable
the bundled template with:
`add_filter( 'simple_events_use_single_template', '__return_false' );`

== Changelog ==

= 1.0.0 =
* Initial release.
* Event post type, categories, and details meta box.
* Shortcode, block, and widget for listing events.
* "Add to Calendar" (.ics) export and Google Maps embed on single events.
