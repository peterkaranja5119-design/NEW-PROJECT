# Simple Events — WordPress Plugin

A lightweight WordPress plugin for creating and displaying events.

## What it does

- Registers an **Event** custom post type (with a calendar dashboard icon) and an **Event Categories** taxonomy.
- Adds an **Event Details** meta box: start/end date & time, all-day flag, venue, address, cost, and an external URL.
- Automatically shows an event details box on single event pages, including an
  **"Add to Calendar" (.ics)** download and a **Google Maps** embed of the venue.
- Provides a `[simple_events]` **shortcode** to list events anywhere.
- Ships an **Events List block** for the block editor (Gutenberg).
- Ships an **Upcoming Events** widget.
- Adds a sortable **Event Date** column to the admin events list.
- Orders the event archive by start date (soonest first).

## Installation

Copy the `simple-events/` directory into your site's `wp-content/plugins/`
directory, then activate **Simple Events** from the Plugins screen.

## Shortcode

```
[simple_events limit="5" scope="upcoming" category="" show_excerpt="yes"]
```

| Attribute      | Default    | Description                                  |
| -------------- | ---------- | -------------------------------------------- |
| `limit`        | `5`        | Number of events to display.                 |
| `scope`        | `upcoming` | `upcoming`, `past`, or `all`.                |
| `category`     | *(none)*   | Event category slug to filter by.            |
| `show_excerpt` | `yes`      | Whether to show the event excerpt.           |

## Project structure

```
simple-events/
├── simple-events.php                       # Plugin bootstrap, constants, activation hooks
├── readme.txt                              # WordPress.org-style readme
├── assets/css/simple-events.css            # Front-end styles
├── blocks/events-list/                     # "Events List" block editor block
│   ├── block.json                          # Block metadata + attributes
│   ├── index.js                            # Editor script (no build step)
│   └── index.asset.php                     # Editor script dependencies
├── templates/single-event.php              # Fallback single-event template (Elementor-compatible)
└── includes/
    ├── class-simple-events-plugin.php      # Wires everything together; admin columns; single-page details
    ├── class-simple-events-cpt.php         # Event post type + taxonomy
    ├── class-simple-events-meta.php        # Event details meta box + saving
    ├── class-simple-events-query.php       # Reusable event queries + date formatting
    ├── class-simple-events-shortcode.php   # [simple_events] shortcode
    ├── class-simple-events-widget.php      # Upcoming Events widget
    ├── class-simple-events-ics.php         # iCalendar (.ics) export
    └── class-simple-events-block.php       # Events List block registration
```

## Add to Calendar & Maps

On single event pages the plugin adds:

- An **"Add to Calendar"** link that downloads an RFC 5545 `.ics` file
  (`/events/my-event/?ics=1`), compatible with Google Calendar, Apple Calendar,
  and Outlook.
- A **Google Maps** embed of the event location (when an address is set). No API
  key is required. Disable it per-event with the `simple_events_show_map` filter.

## Page builder (Elementor) compatibility

Some themes don't call `the_content()` in their template for custom post types,
which makes Elementor show *"you must call the the_content function in the
current template."* To fix this, the plugin ships a fallback single-event
template (`templates/single-event.php`) that calls `the_content()`, so
**Edit with Elementor** works on event pages out of the box.

Overriding is fully supported:

- A theme's own `single-event.php` (or `single-{post_type}.php`) takes priority.
- A theme override at `yourtheme/simple-events/single-event.php` is used next.
- Disable the bundled template entirely with:
  `add_filter( 'simple_events_use_single_template', '__return_false' );`

When an event is built with Elementor, the auto-injected event-details box is
skipped so details aren't duplicated on top of your Elementor layout.

## Requirements

- WordPress 5.6+
- PHP 7.2+
