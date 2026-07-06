# Chain-of-Verification — reference vs plugin

Reference = aosars-woocommerce-templates.html (the agreed look + functionality).
Status legend: DONE (structure+skin), SKIN (CSS-only best-effort), PENDING (needs live markup / next iteration).

| # | Section (reference) | Look + functionality expected | Plugin status | Verification / gap |
|---|---|---|---|---|
| 1 | Shop card — WooCommerce (/e-books/) | Card frame, sale badge, category, rating, price, AJAX add; **cyan edge + 120% hover** | DONE | content-product.php override via `wc_get_template_part` + scoped CSS. Renders our markup + classes. |
| 2 | Course card — Tutor LMS (/postgraduate-research-e-courses/) | Same card look + **cyan edge + 120% hover** | SKIN | CSS now targets `.tutor-course-card` etc. + loads site-wide. **Needs live class names to confirm exact selectors.** |
| 3 | Toolbar / filter bar (news-style) | Category + Sort + Apply + count + pills | SKIN | Plugin styles WooCommerce's own result-count/ordering; the full filter bar is PENDING. |
| 4 | Single product (gallery, counter, look-inside, tabs, trust, sticky bar) | Full redesign | SKIN | Price/button/tabs skinned via CSS; gallery/tabs/trust structure PENDING (needs single-product template override + live markup). |
| 5 | Related products carousel (3-up) | Carousel | PENDING | Not yet in plugin. |
| 6 | Cart (card rows, live totals, remove) | Full redesign | SKIN | Buttons skinned; card-row cart PENDING (cart.php override). |
| 7 | Checkout (grouped steps, currency-driven payments, logos) | Full redesign | PENDING | Buttons skinned; payment logic depends on the currency plugin + Flutterwave gateways. |
| 8 | Events cards + empty state | Cards | PENDING | Events source/plugin unknown; needs the events page markup. |
| 9 | Mini-cart drawer (Checkout / View cart / Continue) | Opens on add-to-cart | DONE | Footer drawer + JS on WooCommerce `added_to_cart`. **1.0.2 regression fixed in 1.0.3:** the card button dropped WooCommerce's `ajax_add_to_cart` class, so the event never fired; now verified in a real browser (click add → drawer opens with item + cart total). |
| 10 | Trust bar | Store-wide strip | DONE | `woocommerce_before_shop_loop` + `[acs_trust_bar]`. |
| 11 | Fatal-safety (CP1–CP9) | No white-screen | DONE | Every hook guarded; verified by phply parse + static audit + tests/harness.php. |

## To close the remaining loop (needs one input)
Pixel-matching sections 2–8 requires the **live rendered HTML** of: one shop card, one Tutor course card, the single-product page, the cart and checkout. Provide either:
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
