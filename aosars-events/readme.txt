=== AOSARS Events ===
Contributors: Karanja Maina
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 4.1.1
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

= 4.1.1 =
* Fixed Elementor error "you must call the the_content function in the current
  template" when opening an event with Elementor. The plugin now ships a
  single-event template that calls the_content(), used only when the active
  theme does not provide its own template for events. Theme overrides
  (single-aosars_event.php or aosars-events/single-aosars_event.php) are
  respected, and the bundled template can be disabled with the
  aosev_use_single_template filter. This also ensures the event view renders on
  themes whose templates never call the_content() for custom post types.

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
