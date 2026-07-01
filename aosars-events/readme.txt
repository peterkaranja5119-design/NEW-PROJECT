=== AOSARS Events ===
Contributors: Karanja Maina
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 5.0.0
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

= 5.0.0 =
* Single-event pages restored to the full branded prototype design: framed hero, live countdown, timezone bar, the styled sections (About, How to join, What you'll cover, Agenda, Facilitator), sticky facts sidebar and related events — driven by each event's data.
* Every section is shown ONLY when the event has data for it; nothing shows placeholder filler. The event's own editor/Elementor body renders inside "About this event", and HTML renders properly.
* New homepage component + shortcode [aosars_events_home] (and the "AOSARS Events Home" Elementor widget): a featured next-event card with a big live countdown, a swipeable "Coming up next" carousel, timezone chips and a subscribe button — for dropping the events section onto a normal page. Cards open the event permalink.
* Restored the per-event content fields (lead, What you'll cover, Agenda, facilitator name/bio) that populate the single-page sections.

= 4.6.0 =
* Portal cards (and the next-event / related links) now open the event's own permalink page, so a click lands on the full page you build in Elementor rather than the in-portal quick view.
* New opt-in setting "Hide theme title on events": hides the theme's own single-event title so it isn't shown twice alongside the branded hero. Best-effort across common themes; the selector list is filterable via `aosev_hide_title_selectors`.

= 4.5.0 =
* Single-event pages are now a blank canvas, not a fixed template. The placeholder sections (Overview, How to join, What you'll cover, Agenda, Your facilitator) have been removed. You author the page body in the WordPress editor or Elementor, and it renders inside the branded shell (hero, live countdown, timezone bar, facts sidebar, related events).
* Your authored HTML now renders on the portal single view too (not just the permalink): the event's content is passed through and shown in the main column.
* The Google Meet link and "Join the meeting" button appear only when a meeting code is set; no placeholder link is shown otherwise.
* Removed the now-unused per-event section fields and their site-wide defaults; the Event details box is trimmed to scheduling/venue/fee plus a short card blurb.

= 4.4.0 =
* The last two fixed lines on the single-event page are now editable: the facilitator heading ("Led by the AOSARS faculty") and the how-to-join intro sentence, each a per-event field with a site-wide default under Events > Settings.
* Inline HTML is now allowed in the "What you'll cover" and "Agenda" lists (e.g. links or bold inside a point), filtered through wp_kses_post and rendered safely.

= 4.3.0 =
* Rich HTML in event copy: the Lead paragraph, Overview extra, How-to-join note, and Facilitator bio now use a visual editor and accept formatting (bold, links, lists, headings). Input is filtered through wp_kses_post, so only safe HTML is stored, and it renders formatted on the single-event page. Event cards still show plain text (tags stripped) so card layout can't break.

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
