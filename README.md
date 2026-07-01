# Simple Events — WordPress Plugin

A lightweight WordPress plugin for creating and displaying events.

## What it does

- Registers an **Event** custom post type (with a calendar dashboard icon) and an **Event Categories** taxonomy.
- Adds an **Event Details** meta box: start/end date & time, all-day flag, venue, address, cost, and an external URL.
- Automatically shows an event details box on single event pages.
- Provides a `[simple_events]` **shortcode** to list events anywhere.
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
└── includes/
    ├── class-simple-events-plugin.php      # Wires everything together; admin columns; single-page details
    ├── class-simple-events-cpt.php         # Event post type + taxonomy
    ├── class-simple-events-meta.php        # Event details meta box + saving
    ├── class-simple-events-query.php       # Reusable event queries + date formatting
    ├── class-simple-events-shortcode.php   # [simple_events] shortcode
    └── class-simple-events-widget.php      # Upcoming Events widget
```

## Requirements

- WordPress 5.6+
- PHP 7.2+
