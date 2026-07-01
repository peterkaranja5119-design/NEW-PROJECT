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
* `[simple_events]` shortcode with attributes for limit, scope, and category.
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

== Changelog ==

= 1.0.0 =
* Initial release.
