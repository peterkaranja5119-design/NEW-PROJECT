# Chain-of-Verification — reference vs plugin

Reference = aosars-woocommerce-templates.html (the agreed look + functionality).
Status legend: DONE (structure+skin), SKIN (CSS-only best-effort), PENDING (needs live markup / next iteration).

| # | Section (reference) | Look + functionality expected | Plugin status | Verification / gap |
|---|---|---|---|---|
| 1 | Shop card — WooCommerce (/e-books/) | Card frame, sale badge, category, rating, price, AJAX add; **cyan edge + 120% hover** | DONE | content-product.php override via `wc_get_template_part` + scoped CSS. Renders our markup + classes. |
| 2 | Course card — Tutor LMS (/postgraduate-research-e-courses/) | Same card look + **cyan edge + 120% hover** | SKIN | CSS now targets `.tutor-course-card` etc. + loads site-wide. **Needs live class names to confirm exact selectors.** |
| 3 | Toolbar / filter bar (news-style) | Category + Sort + Apply + count + pills | DONE (1.1.0, M6) | Full toolbar at woocommerce_before_shop_loop: category select, sort select, Apply, live "N items" count, category pills with active state. Stock result-count/ordering (WC core + Storefront re-hooks) removed while on, restored exactly when toggled off. Filter + sort round-trip browser-verified. |
| 4 | Single product (gallery, counter, look-inside, tabs, trust, sticky bar) | Full redesign | DONE (1.1.0, M2) | Framed gallery, pill tabs, quantity steppers (+/−, min/max-aware), trust strip under add-to-cart, sticky add-to-cart bar (IntersectionObserver reveal; button keeps ajax_add_to_cart so it feeds the drawer). "Look inside" still needs the dummy's spec — the one remaining sub-feature. |
| 5 | Related products carousel (3-up) | Carousel | DONE (1.1.0, M3) | woocommerce_output_related_products_args → 3 columns / up to 6 items; JS wraps the list in a scroll-snap rail with prev/next arrows. |
| 6 | Cart (card rows, live totals, remove) | Full redesign | DONE (1.1.0, M4) | Classic cart rows render as cards (verified computed border-radius) with styled totals card, round remove buttons and steppers; blocks cart gets the card treatment via CSS. Totals/remove behaviour stays WooCommerce's own. |
| 7 | Checkout (grouped steps, currency-driven payments, logos) | Full redesign | DONE (1.1.0, M5) | Numbered step headings (classic checkout hooks), card-grouped customer details / order review, accepted-payments strip (filterable acs_payment_strip_methods). Gateway availability/currency switching stays with the currency plugin + Flutterwave gateways by design. |
| 8 | Events cards + empty state | Cards | DONE (1.1.0, M8) | Card grid, hover, meta and empty-state styling for the AOSARS/Simple Events markup (simple-events-list/-item/-empty) shipped in this repo. Third-party events plugins would still need their markup. |
| 9 | Mini-cart drawer (Checkout / View cart / Continue) | Opens on add-to-cart | DONE | Footer drawer + JS on WooCommerce `added_to_cart`. **1.0.2 regression fixed in 1.0.3:** the card button dropped WooCommerce's `ajax_add_to_cart` class, so the event never fired; now verified in a real browser (click add → drawer opens with item + cart total). |
| 10 | Trust bar | Store-wide strip | DONE | `woocommerce_before_shop_loop` + `[acs_trust_bar]`. |
| 11 | Fatal-safety (CP1–CP9) | No white-screen | DONE | Every hook guarded; verified by phply parse + static audit + tests/harness.php. |

## To close the remaining loop (needs one input)
Structure and behaviour for sections 3–8 are now built and browser-verified
(1.1.0). Pixel-matching against the dummy still requires the dummy file
(aosars-woocommerce-templates.html) or the live rendered HTML of: one Tutor
course card (section 2, still selector-guess CSS) and the single product
"look inside" element. Provide either:
- Connect the Claude-in-Chrome extension so it can read the live DOM, or
- Paste the HTML of a `<li class="product">…</li>` (e-books) and a `.tutor-course-card` (courses) block.

With exact classes, the CSS/template overrides can be locked and verified in a tight loop.

## 1.0.3/1.0.4 — real-install verification (replaces stub-only claims)

The 1.0.2 stub harness passed while the plugin failed on the live site. The
live site runs WordPress 7.0, so activation was not the blocker there; the
skin appeared dead because the 1.0.2 card button dropped WooCommerce's
ajax_add_to_cart class, so add-to-cart clicks reloaded the page and the
mini-cart drawer never opened (fixed in 1.0.3). Every check below was run in
two real installs — WordPress 6.5.5 + WooCommerce 9.0.2 and, matching the
live site, WordPress 7.0 + WooCommerce 10.9.3 (Storefront, real browser):

| Check | Result |
|---|---|
| Activation on WordPress 6.5 and 7.0 | PASS (on 6.5, 1.0.2 was blocked: headers required WP 7.0) |
| Shop card override (`acs-pcard`) renders for every product | PASS |
| Sale badge (-44% computed), Featured badge, category chip, stars | PASS |
| Trust bar before shop loop | PASS |
| CSS/JS enqueued at the current version, `body.acs-skin` present | PASS |
| AJAX add-to-cart -> drawer opens with item + cart total (real browser) | PASS (broken in 1.0.2 — see row 9) |
| Continue shopping closes drawer | PASS |
| Admin settings page, each module toggle off/on round-trip | PASS |
| Single product + cart pages render, no PHP errors in debug.log | PASS |

## 1.0.5 — WooCommerce feature-compatibility verification

WooCommerce classifies plugins per feature (compatible / incompatible /
uncertain); "uncertain" plugins are flagged as incompatible with enabled
features such as High-Performance Order Storage. Checked live via WooCommerce's
FeaturesController on WordPress 7.0 + WooCommerce 10.9.3:

| Check | 1.0.4 | 1.0.5 |
|---|---|---|
| custom_order_tables (HPOS) classification | uncertain (flagged) | compatible |
| cart_checkout_blocks classification | uncertain (flagged) | compatible |
| Order create/read with HPOS enabled, skin active | — | PASS |
| Full front-end suite re-run with HPOS enabled | — | PASS |

## 1.1.0 — full-module verification (fresh WordPress + WooCommerce latest, Storefront, SQLite, real browser)

47-check Playwright suite, all green:

| Area | Checks |
|---|---|
| Shop toolbar (M6) | present; category+sort selects; Apply; live count; pills; stock ordering/result-count removed; filter to e-books returns exactly its 3 products; price-asc order verified; active pill on term archive |
| Single product (M2) | trust strip; steppers +/− change qty; sticky bar hidden until the buy box scrolls out, then opens; sticky add fires AJAX and opens the drawer naming the product and cart total |
| Related rail (M3) | acs-rail applied; 2 arrows; cards present |
| Cart (M4) | classic rows card-styled (computed 16px radius); totals card; steppers; round remove |
| Checkout (M5) | step 1/2 headings; payment strip with 5 badges; details card styled; place-order button |
| Drawer (M9) regression | archive add opens drawer; Continue closes |
| Admin toggles | 9 module rows; m6 off → toolbar gone, stock ordering restored; m2 off → sticky/steppers/trust gone; both back on restore |
| Blocks safety | wp:woocommerce/cart and wp:woocommerce/checkout pages mount with zero JS errors while the skin is active |
| Fatal safety | tests/harness.php PASS (load, lifecycle, fault injection); debug.log free of plugin errors |

Fixed during this loop: wp_localize_script stringifies numbers, so disabled
modules' "0" flags were truthy in JS and the steppers survived toggling M2
off. All module flags are now compared strictly ('1' === String(flag)).
