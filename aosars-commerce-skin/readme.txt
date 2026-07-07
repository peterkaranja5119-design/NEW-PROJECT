=== AOSARS Commerce Skin ===
Contributors: karanjamaina
Tags: woocommerce, redesign, cards, mini-cart, africa
Requires at least: 7.0
Tested up to: 7.0
Requires PHP: 7.4
WC requires at least: 7.0
WC tested up to: 10.9
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

A non-interfering redesign skin for WooCommerce on AOSARS EDGE: redesigned shop
cards, a mini-cart drawer, a trust bar and toolbar polish, with per-module
toggles. It never edits WooCommerce, Tutor LMS, the theme or the currency plugin.

== Description ==

AOSARS Commerce Skin restyles the WooCommerce storefront to the AOSARS EDGE design
system (Montserrat; cyan #00AEFE and indigo #393464) using only the supported
WooCommerce template-override filter and action/filter hooks, plus a scoped
stylesheet. Every hook callback is wrapped in a fatal firewall, so a fault
degrades one feature instead of white-screening the site.

Features:

* Redesigned shop / archive product card (media frame, sale badge, category chip,
  rating, price, AJAX add-to-cart) with a benchmark hover: a cyan edge line and a
  120% image zoom.
* Mini-cart drawer that opens on WooCommerce's own add-to-cart event, offering
  Checkout / View cart / Continue shopping with an instant-download reassurance.
* Store-wide trust bar (also available as the [acs_trust_bar] shortcode).
* A [acs_products] shortcode that renders a skinned product grid.
* Full shop toolbar / filter bar: category select, sort select, Apply button,
  live result count and category pills (replaces the stock ordering row).
* Single product redesign: framed gallery, pill tabs, quantity steppers, a
  trust strip under add-to-cart and a sticky add-to-cart bar that appears when
  the buy box scrolls away.
* Related products as a 3-up rail with scroll-snap and previous/next arrows.
* Cart as card rows with a styled totals card (classic and blocks carts).
* Checkout grouped into numbered step cards with an accepted-payments strip
  (filterable via acs_payment_strip_methods; gateway availability stays with
  your payment/currency plugins).
* Events-card skin for the AOSARS/Simple Events list markup, incl. empty state.
* A self-launching admin menu with an on/off toggle for every module.

== Installation ==

1. If replacing an earlier copy, first deactivate and DELETE the old plugin
   (WordPress will not overwrite an existing plugin folder).
2. Upload the ZIP via Plugins > Add New > Upload Plugin, or extract into
   wp-content/plugins/.
3. Activate. Open "AOSARS Skin" in the admin menu to toggle modules.

== Frequently Asked Questions ==

= Does it change WooCommerce files? =
No. It uses the woocommerce_locate_template filter and WooCommerce hooks, and a
scoped stylesheet. Deactivating restores the default store with no residue.

= Will a bug in the skin break my site? =
No. Every callback is wrapped in a fatal firewall; a fault logs under WP_DEBUG and
disables one feature, never the page.

== Changelog ==

= 1.1.0 =
* Shop toolbar / filter bar (M6, new): category + sort selects with an Apply
  button, live "N items" count and category pills. Replaces the stock
  result-count/ordering row (WooCommerce core and Storefront re-hooks) and
  restores it exactly when the module is toggled off.
* Single product (M2, now structural): quantity steppers, framed gallery and
  pill tabs, a trust strip under add-to-cart, and a sticky add-to-cart bar
  that reveals when the buy box leaves the viewport — its button keeps
  WooCommerce's ajax_add_to_cart classes, so it feeds the mini-cart drawer.
* Related products carousel (M3, new): 3-up scroll-snap rail with
  previous/next arrows via woocommerce_output_related_products_args + JS.
* Cart skin (M4, now structural): classic cart rows become cards with a
  styled totals card; blocks cart gets the same card treatment via CSS.
* Checkout skin (M5, now structural): numbered step headings (classic
  checkout hooks), card-grouped fields/order review, and an accepted-payments
  strip (M-Pesa, Airtel Money, Visa, Mastercard, Flutterwave — filterable via
  acs_payment_strip_methods).
* Events cards skin (M8, new): AOSARS/Simple Events list, item, meta and
  empty-state styling.
* Fix: module flags passed to JS via wp_localize_script are strings, so a
  disabled module's "0" was truthy and the quantity steppers survived
  toggling the single-product module off. Flags are now compared strictly.
* Verified in a real install (WordPress + WooCommerce latest, Storefront,
  real browser): 47-check suite covering every module, filter round-trips,
  drawer regressions, admin toggle off/on round-trips, and blocks
  cart/checkout mounting with no JS errors.

= 1.0.5 =
* Fix the WooCommerce rejection: declared compatibility with High-Performance
  Order Storage (custom_order_tables) and Cart/Checkout Blocks via
  FeaturesUtil on before_woocommerce_init. WooCommerce previously classified
  the plugin as "uncertain" and flagged it as incompatible with those enabled
  features. Both declarations are true: the skin is presentation-only, never
  reads or writes orders, and never replaces the cart/checkout templates.
  Verified: WooCommerce 10.9 now reports the plugin as compatible for both
  features, with HPOS enabled, and orders create/read correctly under HPOS
  with the skin active.
* Adopted the AOSARS Events 6.10 plugin conventions: Requires at least:
  WordPress 7.0, Requires PHP: 7.4, Tested up to: 7.0, Update URI: false, and
  a loud admin notice (instead of a silent return) when two copies of the
  plugin are installed at once, so a stale duplicate can no longer masquerade
  as a failed update.

= 1.0.4 =
* Re-verified the full plugin on WordPress 7.0 + WooCommerce 10.9 (the live
  site runs WordPress 7.0): activation, redesigned card override, badges,
  trust bar, AJAX add-to-cart opening the mini-cart drawer, and the admin
  module toggles. Headers now state Tested up to: WordPress 7.0 / WC 10.9.
* No code changes beyond 1.0.3. On WordPress 7.0 the 1.0.2 version header did
  not block activation; the bug that made the skin appear dead there was the
  1.0.2 add-to-cart button dropping WooCommerce's ajax_add_to_cart class
  (fixed in 1.0.3), so clicks reloaded the page and the drawer never opened.

= 1.0.3 =
* Fix: the plugin and readme headers required WordPress 7.0, which blocked
  installation and activation on WordPress 6.x sites ("does not meet minimum
  requirements"), so the skin never appeared at all. The minimum is now 5.6.
* Fix: the redesigned card's add-to-cart button passed a custom class that
  REPLACED WooCommerce's default button classes, dropping add_to_cart_button
  and ajax_add_to_cart. Clicking the button therefore did a full page reload,
  the added_to_cart event never fired, and the mini-cart drawer never opened.
  The button now keeps WooCommerce's classes, so AJAX add-to-cart and the
  drawer work as designed.
* Verified end-to-end in a real WordPress 6.5.5 + WooCommerce 9.0.2 install
  (Storefront theme, real browser): activation, redesigned card override with
  sale/featured badges, category chips and ratings, trust bar, AJAX
  add-to-cart opening the drawer with the item and cart total, Continue
  shopping closing it, and every admin module toggle (off removes the module,
  on restores it).

= 1.0.2 =
* Load the skin site-wide on the front end (was WooCommerce-only) so Tutor LMS course archives and page-builder grids are skinned too.
* Added a Tutor LMS course-card skin (cyan-edge + 120% hover parity with the shop card). Added VERIFICATION.md (chain-of-verification matrix).

= 1.0.1 =
* Fix: shop card override now hooks wc_get_template_part (the correct filter for content-product.php), so the redesigned card actually renders; added a fallback skin so the cyan-edge + 120% hover applies to default cards too.
* Added tests/harness.php (fail-safe verification: load, lifecycle, fault injection).

= 1.0.0 =
* Initial release: shop card redesign with benchmark hover, mini-cart drawer,
  trust bar, toolbar polish, shortcodes and per-module admin toggles.

== Compatibility matrix ==

* WordPress: 7.0 (headers per the AOSARS Events conventions; verified on 7.0)
* PHP: 7.4+ (self-deactivates below 7.4)
* WooCommerce: 7.0 – 10.x (verified on 9.0 and 10.9)
* Themes/plugins: non-interfering; test with your active theme and the currency
  plugin on staging first.

== Rollback note ==

To roll back or replace: deactivate and delete this plugin, then upload the
previous ZIP. WordPress refuses to overwrite an existing plugin folder on upload.
