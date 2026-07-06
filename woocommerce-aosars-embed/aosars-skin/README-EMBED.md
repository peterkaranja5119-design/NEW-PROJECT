# AOSARS Commerce Skin — embedded in WooCommerce

This WooCommerce 10.7.0 copy carries the AOSARS Commerce Skin (v1.0.5) **inside**
the plugin, per the site owner's request. The standalone "AOSARS Commerce Skin"
plugin remains the source of truth for the skin code; everything in this folder
except `loader.php` is a byte-identical copy of that tested plugin.

## 1. Exact change inventory (only what was necessary)

Compared with pristine WooCommerce 10.7.0, exactly two things differ:

1. `woocommerce.php` — one guarded require block appended at the end of the
   file, between the `AOSARS-EMBED-BEGIN` / `AOSARS-EMBED-END` markers. The
   block is wrapped in `is_readable()` + `try/catch`, so a missing or broken
   skin folder can never take WooCommerce down.
2. `includes/aosars-skin/` — this folder (all new files; nothing of
   WooCommerce's own code lives here).

Verify at any time:

    grep -n "AOSARS-EMBED" woocommerce.php        # exactly 2 marker lines at EOF
    diff -qr <pristine-10.7.0> <this-copy>        # 1 changed file + this folder

No WooCommerce template, class, or asset was edited. The shop-card override
still rides the supported `wc_get_template_part` filter, so WooCommerce >
Status reports no template overrides and theme overrides keep their normal
semantics.

## 2. Update policy — read before updating WooCommerce

Any WooCommerce update (auto-update, one-click update, or host-initiated)
replaces the whole `woocommerce/` folder and **silently removes the skin**.
Decide one of:

- Recommended: on the Plugins screen, disable auto-updates for WooCommerce and
  apply updates manually, re-embedding afterwards (section 3). Trade-off: you
  take on applying WooCommerce security releases promptly yourself.
- Alternative: keep auto-updates on and accept that the skin disappears on
  every update until re-applied (the site stays healthy — it just loses the
  skin), or switch back to the standalone plugin (section 4).

## 3. Re-apply after a WooCommerce update

1. Copy this whole `includes/aosars-skin/` folder into the new
   `wp-content/plugins/woocommerce/includes/`.
2. Re-append the loader block to the new `woocommerce.php` — either apply
   `embed.patch` from this folder (made against 10.7.0; for a newer WooCommerce
   the patch may need `--fuzz` or a manual paste of the marker block at EOF):

       cd wp-content/plugins/woocommerce
       patch -p0 woocommerce.php < includes/aosars-skin/embed.patch

   or paste the block between the `AOSARS-EMBED-BEGIN`/`END` markers (see
   `embed.patch` for its exact text) at the very end of `woocommerce.php`.
3. Flush any PHP opcache (restart PHP-FPM, or your host's "flush cache"
   button). A stale opcache can serve the old `woocommerce.php` and make the
   re-apply look like it did nothing.
4. Confirm: shop page shows the skinned cards and `grep -n "AOSARS-EMBED"
   woocommerce.php` prints the two markers.

## 4. Rollback / recovery

- Instant rollback of the skin only: delete `includes/aosars-skin/` and remove
  the marker block from `woocommerce.php` (or reinstall stock WooCommerce).
  WooCommerce runs stock — verified.
- Standalone plugin spare: `aosars-commerce-skin-1.0.5-standalone.zip` (kept
  with the delivery) installs the identical skin as a normal plugin — the
  safer long-term setup if re-embedding after updates becomes a chore.

## 5. Coexistence with the standalone plugin

If the standalone AOSARS Commerce Skin plugin is active at the same time, the
standalone copy wins and this embedded copy stays dormant (no double cards,
bars, or drawers — verified). A dismissible admin notice reminds you the
standalone plugin can be deactivated. Settings are shared: both copies read
the same `acs_settings` option, so module toggles carry over either way.

## 6. What was tested (WordPress 7.0, PHP 8.4/floor 7.4, HPOS on)

Activation; shop/product/cart/checkout render; HPOS order create/read;
skin parity field-by-field vs the standalone plugin (cards, badges, chips,
ratings, trust bar, assets); AJAX add-to-cart mini-cart drawer open/close in a
real browser; all 7 admin module toggles round-trip; guard test (folder
removed → stock WooCommerce, no fatal); coexistence test; fault-injection
(a throwing skin callback degrades one module, page stays up, one log line);
wipe-and-recover drill using exactly the section-3 procedure. Evidence:
`test-evidence.txt` in the delivery package.
