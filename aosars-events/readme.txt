=== AOSARS Events ===
Contributors: Karanja Maina
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
Stable tag: 6.3.0
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

= 6.3.0 =
* Event details are now written as one HTML content field instead of the separate Lead paragraph / What you'll cover / Agenda / Facilitator fields. The 📝 Event details box leads with a large "Event content" code area where you write or paste HTML — headings, lists, tables, images, even an embedded video — and it renders as the event's "About this event" section with the AOSARS typography. Full control, one place.
* The sidebar boxes (Date & time, How to attend, Register & cost) are unchanged, so the date, countdown and Join button keep working exactly as before.
* Backward compatible: events created earlier that still hold Lead/Covers/Agenda/Facilitator values keep displaying them — the page reads those directly. New events use the single HTML content field.

= 6.2.0 =
* Data entry rebuilt, benchmarked against Modern Events Calendar. The old two-box screen is replaced by a cleaner four-box layout ("Sidebar schedule + main details", the approved mockup #4): three compact cards pinned in the sidebar — 📅 Date & time, 📍 How to attend, 🎟 Register & cost — plus a roomy 📝 Event details box in the main column. The two things that used to go missing (the date and the join link) are now pinned in the sidebar where they can't be overlooked.
* Start date is now marked required (*) with a live red "no start date yet" banner in the Date & time box, so a blank date can no longer slip through silently.
* Same fields, same save, same data bridge and the same Elementor self-heal — no data-model change, so existing events keep working. Verified end-to-end: entered date, timezone, platform and join link all reach the single event page (date, countdown and Join button).
* One plugin only: the separate AOSARS Doctor diagnostics plugin has been removed. Its checks already live under Events → Settings → Diagnostics (running version/folder, time-conversion self-test, and a list of events missing a start date).

= 6.1.0 =
* Fixes the Doctor-report finding that "end/timezone/platform saved but start stayed EMPTY" when entered via the Elementor panel. Two-layer fix: (1) the save-hook sync now prefers the FRESH settings payload of the save (and Elementor's stored _elementor_page_settings) instead of the document object's possibly-stale settings; (2) a render-time safety net — if a field is empty in the event meta but Elementor stored a value for it, the page uses it AND backfills the meta (self-heal). Whatever you set in the ⚙ panel now reaches the page even if the save hook misfires.
* The Start/End pickers in the Elementor panel now also accept direct typing (allowInput).
* AOSARS Doctor 1.1.0: the report now prints each event's Elementor-side stored values (elementor_page_settings aosev_* keys), so a value stuck on the Elementor side is visible at a glance.

= 6.0.0 =
* THE WORKFLOW RELEASE — grounded in direct inspection of the live site. Forensics on aosars.com showed the plugin's features ARE live and rendering, but every one of the 15 events had start=0 and an empty join link: the data was never being SAVED, because events are created inside the Elementor editor, where WordPress meta boxes (the schedule/details boxes) do not exist.
* Event details can now be entered INSIDE Elementor: open the event with Elementor, click the ⚙ Settings icon (bottom-left), open "📅 AOSARS Event details" — Start & End date-time pickers, Timezone, Format, Online platform (Google Meet / Zoom / Microsoft Teams / Webex / YouTube Live / Other), Join link, Venue and Fee. Click UPDATE and they save to the event and drive the date, countdown and Join button on the page.
* The Elementor panel and the wp-admin boxes write the same fields; blanks in Elementor never wipe values entered in wp-admin.
* Added "Update URI: false" to the plugin header so wordpress.org can never accidentally replace this plugin with a same-named one.
* New companion diagnostic plugin "AOSARS Doctor" (separate zip): Tools → AOSARS Doctor produces a copy-paste report — plugin copies/versions, running version, time-conversion probe, and the raw saved schedule data of the last 10 events.

= 5.9.0 =
* Deployment-integrity release. Diagnosis: the entry fields and time pipeline are verified correct in this code, yet the site keeps behaving like an old version — which means the running copy is not the uploaded copy (typically a duplicate plugin folder, e.g. aosars-events-2, left by repeated zip uploads; the older copy loads first and silently blocks the newer one).
* The silent double-load guard now SHOUTS: if a second copy is blocked, a red admin notice names both versions and folders and says exactly what to delete.
* Activating this plugin automatically deactivates any other active copy of aosars-events.php (duplicate folders can no longer hijack loading).
* The events list and settings screens show which version and folder is actually running; Site Health reports it too and flags duplicates.
* Front-end pages carry an HTML comment stamp (<!-- aosars-events v5.9.0 -->) so view-source proves which version served the page (and exposes stale page caches).
* New Diagnostics panel under Events → Settings: running version/folder/PHP, a live time-conversion self-test ("15 Jan 2026 14:00 EAT → epoch … CORRECT"), and a count/list of published events missing a start date.

= 5.8.0 =
* Data-entry clarity (the platform/link/date fields already existed and work — this makes them unmissable and gives feedback as you type):
* Live "Shows on the event page as: Fri 14 Aug 2026, 14:00 EAT" preview appears under the start date and updates as you type, so you can see the date/time is captured before saving.
* The Join-link field now shows a platform-specific placeholder (choose Zoom → "https://zoom.us/j/…", Teams → Teams URL, etc.), and a live hint switches between online-platform vs in-person-venue guidance based on the Format.
* An admin warning appears on any saved event that has no start date, pointing to the 📅 Event schedule box.
* A version stamp is shown in the schedule box ("AOSARS Events v5.8.0 · set details here, not inside Elementor") so you can confirm the running version and know these fields live in the standard editor (they are hidden inside the Elementor editor).
* Note: if you edit events inside Elementor you will NOT see these fields — open the event in the normal WordPress editor to set the date, timezone, platform and join link.

= 5.7.0 =
* Timezone-aware scheduling: a Timezone selector in the 📅 Event schedule box (EAT, WAT, CAT, SAST, GMT, UTC — default EAT). Times you type are now interpreted in that zone; previously they were read as UTC, so every event displayed 3 hours late. Events saved before this update are reinterpreted as EAT, which corrects them.
* Online platform & join link: choose Google Meet / Zoom / Microsoft Teams / Webex / YouTube Live / Other and paste the full join URL (a Google Meet code still auto-builds the link). The page renders "Join on Zoom" (etc.) dynamically. A per-event "hide the join link" option shows "The joining link is sent on registration" instead of the URL.
* Entry hardening: the save nonce is printed in both meta boxes (saving no longer depends on one box being visible), and an inline non-blocking warning appears when End is not after Start.
* Single page: the "← All events / Events › …" strip is removed from the top — the page opens straight onto the hero; a compact "← All events" link now lives in the Event details panel header and points at the configured events page.
* "More events" now uses compact line rows (small thumbnail · title · date · arrow) instead of full cards — about 60% less space.
* Spacing fix: empty paragraphs produced by auto-formatting around pasted HTML are stripped (server-side and CSS), and the first heading inside authored content no longer adds a large gap under "About this event".

= 5.6.0 =
* Data entry overhauled for discoverability. A new "📅 Event schedule" box sits in the side column right next to Publish (start, end, format) — impossible to miss — and the main "Event details" box is organised into labelled groups (Venue & joining, Tickets & organiser, Event card, Single-page sections, Display) in a two-column layout instead of one flat 19-field list.
* New Organiser field: the Event details panel's Organiser line is now editable per event (blank = AOSARS) instead of hardcoded.
* Events without a start date no longer render the Unix epoch ("Thu 1 January 1970"): cards show a TBA badge and "Date to be announced", the single page shows "Date & time to be announced" with no countdown, and undated events sort after dated ones everywhere (portal, home, calendar).
* Portal filter bar, matching the news page: a Search box ("Search events, for example methodology"), Topic dropdown, Month dropdown (built from the months that actually have events) and Sort by (Soonest first / Newest first / Title A–Z). Searching filters live without losing focus; Clear filters resets everything.

= 5.5.0 =
* Fixes "you must call the_content function for Elementor to work" when opening an event with Edit with Elementor. The plugin now detects the Elementor editor/preview context and stands aside there (it does not take over the page or replace the content), so Elementor finds the content area. Normal front-end event pages are unchanged — they still render the AOSARS design.

= 5.4.0 =
* New per-event "Custom HTML" field: paste raw HTML in the Event details box and it renders as-is at the top of the event body, inside the AOSARS design. Stored like WordPress's own Custom HTML block — kept verbatim for users who can post unfiltered HTML (admins), otherwise filtered with wp_kses_post.

= 5.3.0 =
* Fixes inconsistent single pages: newly-created events rendered the theme's plain page while older ones showed the AOSARS design. Cause — the plugin auto-skipped any event that had ever been opened in Elementor (its `_elementor_edit_mode` was "builder"). Now EVERY event uses the AOSARS design by default; a page only uses Elementor/the theme when you explicitly tick the new per-event "Design THIS event page in Elementor / the theme instead" box.
* Hardened the events query so it can never silently exclude an event that is missing the start-date meta (it no longer INNER-JOINs on that meta key).

= 5.2.0 =
* Fixes the "everything shown twice" problem on event pages. The plugin now renders the whole single-event page itself (site header + AOSARS design + site footer) and bypasses the theme's own post title, featured image and content — so the title/image/content are no longer displayed by both the theme and the plugin. New "Use the AOSARS single-event page" setting (on by default); turn it off to keep the theme's own single template. Block themes and Elementor-built events automatically fall back to the previous behaviour.

= 5.1.0 =
* Single event permalinks now render edge-to-edge (like the prototype) instead of being squeezed into the theme's narrow content column — new "Full-width single pages" setting (on by default). The app's inner content still centres at 1180px. Pair with "Hide theme title on events" to avoid a duplicate title.

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
