=== AOSARS Commerce Skin ===
Contributors: karanjamaina
Tags: woocommerce, redesign, cards, mini-cart, africa
Requires at least: 5.6
Tested up to: 6.5
Requires PHP: 7.4
WC requires at least: 7.0
WC tested up to: 9.0
Stable tag: 1.0.3
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
* Light presentation skin for the single product, cart and checkout.
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

* WordPress: 5.6+ (verified on 6.5)
* PHP: 7.4+ (self-deactivates below 7.4)
* WooCommerce: 7.0 – 9.x
* Themes/plugins: non-interfering; test with your active theme and the currency
  plugin on staging first.

== Rollback note ==

To roll back or replace: deactivate and delete this plugin, then upload the
previous ZIP. WordPress refuses to overwrite an existing plugin folder on upload.
