=== AOSARS Events ===
Contributors: Karanja Maina
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 4.2.0
License: GPL-2.0-or-later

The full AOSARS events experience, faithful to the agreed mockup: a portal with a
calendar widget, today ticker, next-event counter, animated countdowns, timezone
bar, grid/list views, category and day filters, and a rich single-event view with
add-to-calendar. Events are edited like posts and can be opened with Elementor.

== Description ==

Place the portal with [aosars_events_portal] or the Elementor "AOSARS Events
Portal" widget. Show one event with [aosars_event id="123"]; each event also has
its own page. Manage events under "AOSARS Events" in the sidebar, exactly like
posts, with an Edit with Elementor option.

One guarded file: every hook is wrapped so a fault degrades that feature instead
of crashing the site. No database table, no REST routes, Elementor optional.

== Changelog ==

= 4.2.0 =
* Single-event prose is now editable, not hardwired: the facilitator name/bio, the "how to join" note, and the overview extra paragraph can be set per event, with site-wide defaults under Events > Settings (blank event fields fall back to the defaults).
* Single view is mode-aware: In-person and Hybrid events show their venue/address instead of always showing a Google Meet link; the Platform/Location label and attendance note follow the mode.
* A live site with no published events now shows a proper empty state instead of the five built-in demo events. Define AOSEV_DEMO or use the `aosev_use_sample_events` filter to preview samples.
* Shared event permalinks now emit Open Graph/Twitter preview tags (skipped automatically when a dedicated SEO plugin is active).
* Hardening: dynamic text (titles, venues, categories, editable prose) is HTML-escaped before injection, and the app degrades gracefully when there are no events or an unknown event id is requested.

= 4.1.0 =
* Backend now mirrors a standard Post: Event Tags taxonomy added alongside Categories; comments/discussion, trackbacks and revisions enabled; post-type capabilities; admin list columns for When and Mode (sortable).
* Native Elementor editing hardened: "Edit with Elementor" is ensured for events even if Elementor is installed later, and an event designed in Elementor is respected (the default layout steps aside).
* New setting to toggle the auto-appended event layout on single pages.

= 4.0.0 =
* Full-fidelity port of the agreed prototype: calendar widget (month/year, day
  dots, click-to-filter), today ticker, next-event counter, animated bar-fill
  countdown clock, per-card mini-countdowns, timezone bar (EAT/WAT/GMT/CAT/SAST),
  grid/list toggle, category and day filters, animated cards with date and mode
  badges, and a rich single view (hero, overview, how-to-join with copyable Meet
  link, what-you'll-cover checks, agenda, facilitator, sticky facts, related).
* Post-like CPT with custom fields and an automatic Edit with Elementor option,
  plus native Elementor widgets (Portal, Single Event).
* Prototype CSS scoped under .aosev-app so it cannot clash with the theme.
