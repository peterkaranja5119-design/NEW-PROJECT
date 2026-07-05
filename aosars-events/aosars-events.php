<?php
/**
 * Plugin Name:       AOSARS Events
 * Description:       The full AOSARS events experience, faithful to the agreed mockup: portal with calendar widget, ticker, next-event counter, animated countdowns, timezone bar, grid/list, category and day filters, and a rich single-event view with add-to-calendar. Post-like CPT that is Elementor-editable, with native Elementor widgets. One guarded file, fail-safe by design; Elementor optional; no database table, no REST.
 * Version:           6.8.0
 * Author:            Karanja Maina
 * License:           GPL-2.0-or-later
 * Text Domain:       aosars-events
 * Update URI:        false
 * Requires at least: 7.0
 * Requires PHP:      7.4
 * Tested up to:      7.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( defined( 'AOSEV_VER' ) ) {
	// Another copy of this plugin loaded first (duplicate install, e.g. a folder like
	// aosars-events-2 left over from repeated zip uploads). Previously this returned
	// SILENTLY — which made every update look like it did nothing. Now it shouts.
	if ( function_exists( 'add_action' ) ) {
		$aosev_dup_dir = basename( dirname( __FILE__ ) );
		add_action( 'admin_notices', function () use ( $aosev_dup_dir ) {
			echo '<div class="notice notice-error"><p><strong>AOSARS Events:</strong> two copies of the plugin are active. The copy in <code>wp-content/plugins/' . esc_html( $aosev_dup_dir ) . '</code> (v6.8.0) is <em>NOT running</em> because an older copy (v' . esc_html( AOSEV_VER ) . ') loaded first. Open the Plugins screen, keep ONE “AOSARS Events”, delete the rest, then reactivate the one you kept.</p></div>';
		} );
	}
	return;
}
define( 'AOSEV_VER', '6.8.0' );
define( 'AOSEV_OPTION', 'aosev_settings' );

/* ---- embedded assets ---- */
define( 'AOSEV_CSS', <<<'AOSEV_CSS_END'
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
.aosev-app{font-family:'Montserrat',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:1.05rem;line-height:1.6;color:#000;background:#f5f5f8;padding:18px 14px 8px;border-radius:16px;}
.aosev-app{
    --indigo:#393464; --indigo-deep:#26215c; --cyan:#00AEFE;
    --indigo-tint:#eceaf6; --tint:#e6f6ff; --rule:#e6e6ec;
    --ink:#000000; --ink-soft:#000000; --ink-faint:#393464; --ink-faded:#393464; --white:#fff; --page:#f5f5f8;
    --rule-soft:rgba(57,52,100,.14); --cyan-deep:#00AEFE; --shadow-sm:0 4px 16px rgba(57,52,100,.08); --shadow-md:0 14px 38px rgba(57,52,100,.14);
  }
.aosev-app *{box-sizing:border-box;}
.aosev-app html{-webkit-text-size-adjust:100%;}
.aosev-app body{margin:0;background:var(--page);color:var(--ink);
    font-family:'Montserrat',-apple-system,BlinkMacSystemFont,sans-serif;font-size:1.05rem;line-height:1.6;}
.aosev-app a{color:inherit;text-decoration:none;}
.aosev-app h1:focus, .aosev-app h2:focus{outline:none;}
.aosev-app .topbar{background:#fff;color:var(--ink);border-bottom:1px solid var(--rule);}
.aosev-app .topbar__in{max-width:1180px;margin:0 auto;padding:14px 20px;display:flex;align-items:center;gap:18px;}
.aosev-app .brand{font-weight:800;letter-spacing:.5px;font-size:18px;cursor:pointer;color:var(--indigo);}
.aosev-app .brand b{color:var(--cyan);}
.aosev-app .topnav{margin-left:auto;display:flex;gap:18px;font-size:14px;color:var(--ink-soft);}
.aosev-app .topnav a.active{color:var(--indigo);font-weight:700;box-shadow:inset 0 -2px 0 var(--cyan);}
@media(max-width:640px){.aosev-app .topnav{display:none;}}
.aosev-app .wrap{max-width:1180px;margin:0 auto;padding:0 20px 56px;}
.aosev-app .tzbar{display:flex;align-items:center;gap:8px;flex-wrap:wrap;margin:16px 0 0;}
.aosev-app .tzbar .lbl{font-size:12.5px;color:var(--ink-faint);margin-right:2px;}
.aosev-app .tz{font-size:13px;font-weight:700;padding:7px 15px;border-radius:20px;background:var(--indigo-tint);color:var(--ink-faded);border:0;cursor:pointer;transition:.15s;}
.aosev-app .tz:hover{background:var(--tint);}
.aosev-app .tz.on{background:var(--cyan);color:var(--indigo);}
.aosev-app /* PORTAL */
  .ticker{margin:18px 0 0;background:var(--tint);color:var(--indigo);border:1px solid #c9ecff;border-radius:14px;display:flex;align-items:center;gap:14px;padding:12px 16px;overflow:hidden;}
.aosev-app .ticker__tag{background:var(--cyan);color:var(--indigo);font-weight:800;font-size:11.5px;letter-spacing:.4px;padding:4px 12px;border-radius:20px;flex:none;}
.aosev-app .ticker__item{flex:1;min-width:0;font-size:15px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
.aosev-app .ticker__item span{color:var(--ink-soft);}
.aosev-app .ticker__x{flex:none;border:0;background:transparent;color:var(--ink-faint);font-size:20px;cursor:pointer;}
.aosev-app .head{display:flex;align-items:flex-end;justify-content:space-between;gap:16px;flex-wrap:wrap;margin:26px 0 0;}
.aosev-app .head h1{margin:0;font-size:clamp(28px,3.6vw,44px);font-weight:800;color:var(--indigo);letter-spacing:-.02em;line-height:1.1;}
.aosev-app .head .stats{margin-top:4px;font-size:14px;color:var(--ink-faint);}
.aosev-app .head .stats b{color:var(--indigo);font-weight:700;}
.aosev-app .view{display:inline-flex;border:1px solid var(--rule);border-radius:11px;overflow:hidden;background:#fff;}
.aosev-app .view button{width:46px;height:46px;display:grid;place-items:center;background:#fff;border:0;cursor:pointer;color:var(--ink-faint);font-size:18px;transition:.15s;border-left:1px solid var(--rule);}
.aosev-app .view button:first-child{border-left:0;}
.aosev-app .view button:hover{color:var(--indigo);background:var(--indigo-tint);}
.aosev-app .view button.on{background:var(--cyan);color:var(--indigo);}
.aosev-app .portalwrap{display:grid;grid-template-columns:2fr 1fr;gap:30px;align-items:start;margin:22px 0 0;}
@media(max-width:880px){.aosev-app .portalwrap{grid-template-columns:1fr;}}
.aosev-app .fbar{display:flex;align-items:center;justify-content:space-between;gap:12px;flex-wrap:wrap;margin:0 0 16px;}
.aosev-app .fbar .lab{font-size:16px;font-weight:800;color:var(--indigo);}
.aosev-app .fbar .lab span{color:var(--ink-faint);font-weight:600;font-size:13px;margin-left:8px;}
.aosev-app .clear{font-size:12.5px;font-weight:700;color:#a32d2d;background:#fdeeee;border:0;border-radius:20px;padding:7px 14px;cursor:pointer;}
.aosev-app .clear:hover{background:#fbe0e0;}
.aosev-app .egrid{display:grid;grid-template-columns:repeat(auto-fill,minmax(258px,1fr));gap:20px;}
.aosev-app .egrid.is-list{grid-template-columns:1fr;gap:14px;}
.aosev-app .card{border:1.5px solid var(--rule-soft);border-radius:16px;overflow:hidden;background:#fff;display:flex;flex-direction:column;height:100%;cursor:pointer;box-shadow:var(--shadow-sm);
    opacity:1;animation:cardIn .55s cubic-bezier(.2,.75,.25,1) backwards;
    transition:transform .26s cubic-bezier(.2,.7,.3,1),box-shadow .26s ease,border-color .26s ease;}
@keyframes cardIn{from{opacity:0;transform:translateY(20px) scale(.98);}to{opacity:1;transform:translateY(0) scale(1);}}
.aosev-app .card:hover, .aosev-app .card:focus-within{transform:translateY(-8px);box-shadow:var(--shadow-md);border-color:var(--cyan);}
.aosev-app .card__media{position:relative;aspect-ratio:16/9;overflow:hidden;background:linear-gradient(135deg,var(--indigo-tint),var(--tint));}
.aosev-app .card__media .ico{position:absolute;inset:0;display:grid;place-items:center;font-size:42px;color:var(--indigo);opacity:.5;z-index:1;}
.aosev-app .card__media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:2;transition:transform .45s ease;}
.aosev-app .card:hover .card__media img{transform:scale(1.06);}
.aosev-app .card__media .scrim{position:absolute;inset:0;z-index:3;background:linear-gradient(180deg,rgba(38,33,92,.30),rgba(38,33,92,0) 42%);}
.aosev-app .date{position:absolute;left:12px;top:12px;z-index:4;background:#fff;color:var(--indigo);font-size:10.5px;font-weight:800;letter-spacing:.4px;padding:6px 9px;border-radius:9px;line-height:1.05;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.14);}
.aosev-app .date .d{font-size:16px;display:block;letter-spacing:0;}
.aosev-app .mode{position:absolute;right:12px;top:12px;z-index:4;font-size:11.5px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:5px 11px;border-radius:20px;box-shadow:0 2px 8px rgba(0,0,0,.14);}
.aosev-app .m-person{background:#fff;color:var(--indigo);}
.aosev-app .m-virtual{background:#e7e3fa;color:#393464;}
.aosev-app .m-hybrid{background:var(--cyan);color:var(--indigo);}
.aosev-app .card__body{padding:20px 20px 20px;display:flex;flex-direction:column;gap:9px;flex:1;}
.aosev-app .card__body h3{font-size:1.05rem;font-weight:800;color:var(--indigo);line-height:1.3;letter-spacing:-.005em;margin:0;}
.aosev-app .row{display:flex;align-items:center;gap:7px;font-size:13.5px;color:var(--ink-soft);}
.aosev-app .row .g{width:16px;text-align:center;color:var(--ink-faint);flex:none;}
.aosev-app .time{font-weight:700;color:var(--ink);font-variant-numeric:tabular-nums;}
.aosev-app .cdmini{display:inline-flex;align-items:center;gap:6px;font-size:12.5px;font-weight:800;color:var(--indigo);background:rgba(0,174,254,.10);padding:6px 11px;border-radius:8px;align-self:flex-start;font-variant-numeric:tabular-nums;}
.aosev-app .cdmini i{width:6px;height:6px;border-radius:50%;background:var(--indigo);display:inline-block;}
.aosev-app .cdmini.soon{background:var(--cyan);color:var(--indigo);}
.aosev-app .cdmini.soon i{background:var(--indigo);animation:nupulse 1.6s infinite;}
.aosev-app .foot{margin-top:auto;display:flex;align-items:center;justify-content:space-between;gap:10px;padding-top:6px;}
.aosev-app .pill{background:var(--tint);color:var(--indigo);padding:4px 11px;border-radius:999px;font-size:11.5px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;}
.aosev-app .more{display:inline-flex;align-items:center;gap:6px;font-size:13.5px;font-weight:800;color:var(--indigo);}
.aosev-app .more i{font-style:normal;color:var(--cyan);font-size:16px;transition:transform .2s;}
.aosev-app .card:hover .more i{transform:translateX(6px);}
.aosev-app .soldout{color:#a32d2d;font-weight:700;}
.aosev-app .card.full .more, .aosev-app .card.full .pill{opacity:.55;}
.aosev-app .egrid.is-list .card{display:grid;grid-template-columns:230px 1fr;align-items:stretch;}
.aosev-app .egrid.is-list .card__media{aspect-ratio:auto;height:100%;min-height:170px;}
.aosev-app .egrid.is-list .card__body{padding:18px 20px;}
@media(max-width:560px){.aosev-app .egrid.is-list .card{grid-template-columns:1fr;}
.aosev-app .egrid.is-list .card__media{aspect-ratio:16/9;height:auto;min-height:0;}}
.aosev-app .empty-state{border:1px dashed var(--rule);border-radius:14px;padding:34px;text-align:center;color:var(--ink-faint);font-size:14px;grid-column:1/-1;}
.aosev-app .pager{display:flex;align-items:center;justify-content:center;gap:8px;margin:28px 0 0;}
.aosev-app .pg{min-width:40px;height:40px;border-radius:10px;border:1px solid var(--rule);background:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:14px;font-weight:700;color:var(--indigo);cursor:pointer;}
.aosev-app .pg.on{background:var(--cyan);color:var(--indigo);border-color:var(--cyan);}
.aosev-app /* SIDEBAR RAIL */
  .side{position:sticky;top:18px;display:grid;gap:16px;}
@media(max-width:880px){.aosev-app .side{position:static;}}
.aosev-app .spanel{border:1px solid var(--rule);border-radius:16px;background:#fff;padding:16px;}
.aosev-app .spanel h3{font-size:12px;text-transform:uppercase;letter-spacing:.6px;color:var(--ink-faint);font-weight:800;margin:0 0 12px;}
.aosev-app .cal-head{display:flex;align-items:center;justify-content:space-between;margin-bottom:11px;gap:8px;}
.aosev-app .cal-nav{display:flex;gap:4px;}
.aosev-app .cal-nav button, .aosev-app .cal-mode{border:1px solid var(--rule);background:#fff;border-radius:8px;height:30px;cursor:pointer;color:var(--indigo);font-size:13px;display:grid;place-items:center;transition:.15s;}
.aosev-app .cal-nav button{width:30px;}
.aosev-app .cal-mode{padding:0 11px;font-size:11px;font-weight:800;letter-spacing:.3px;}
.aosev-app .cal-nav button:hover, .aosev-app .cal-mode:hover{border-color:var(--cyan);background:var(--tint);}
.aosev-app .cal-title{font-size:14.5px;font-weight:800;color:var(--indigo);}
.aosev-app .cal-grid{display:grid;grid-template-columns:repeat(7,1fr);gap:3px;}
.aosev-app .cal-wd{font-size:10.5px;color:var(--ink-faint);text-align:center;font-weight:700;padding:2px 0;}
.aosev-app .cal-day{position:relative;aspect-ratio:1;display:grid;place-items:center;font-size:12.5px;color:var(--ink);border-radius:8px;}
.aosev-app .cal-day.empty{color:transparent;}
.aosev-app .cal-day.has{cursor:pointer;font-weight:700;}
.aosev-app .cal-day.has:hover{background:var(--tint);}
.aosev-app .cal-day.today{box-shadow:inset 0 0 0 1.6px var(--cyan);}
.aosev-app .cal-day.sel{background:var(--cyan);color:var(--indigo);}
.aosev-app .cal-dot{position:absolute;bottom:5px;left:50%;transform:translateX(-50%);width:5px;height:5px;border-radius:50%;background:var(--cyan);}
.aosev-app .cal-day.sel .cal-dot{background:var(--indigo);}
.aosev-app .cal-cap{font-size:11.5px;color:var(--ink-faint);margin-top:10px;display:flex;align-items:center;gap:6px;}
.aosev-app .cal-cap i{width:6px;height:6px;border-radius:50%;background:var(--cyan);display:inline-block;}
.aosev-app .cal-year{display:grid;grid-template-columns:repeat(3,1fr);gap:7px;}
.aosev-app .cal-mchip{border:1px solid var(--rule);border-radius:10px;padding:10px 6px;text-align:center;cursor:pointer;transition:.15s;}
.aosev-app .cal-mchip:hover{border-color:var(--cyan);background:var(--tint);}
.aosev-app .cal-mchip.cur{border-color:var(--cyan);background:var(--tint);}
.aosev-app .cal-mchip .mn{font-size:12.5px;font-weight:800;color:var(--indigo);}
.aosev-app .cal-mchip .mc{font-size:10.5px;color:var(--ink-faint);margin-top:3px;}
.aosev-app .cal-mchip .mc b{color:var(--indigo);}
.aosev-app /* redesigned NEXT EVENT counter */
  .nextup{position:relative;overflow:hidden;border:1px solid var(--rule);border-radius:16px;padding:18px;color:var(--indigo);background:#fff;}
.aosev-app .nu-top{display:flex;align-items:center;justify-content:space-between;gap:8px;margin-bottom:10px;}
.aosev-app .nu-live{display:inline-flex;align-items:center;gap:7px;font-size:10.5px;letter-spacing:1.2px;text-transform:uppercase;color:var(--indigo);font-weight:800;}
.aosev-app .nu-live i{width:7px;height:7px;border-radius:50%;background:var(--indigo);animation:nupulse 1.8s infinite;}
@keyframes nupulse{0%{box-shadow:0 0 0 0 rgba(57,52,100,.5);}70%{box-shadow:0 0 0 8px rgba(57,52,100,0);}100%{box-shadow:0 0 0 0 rgba(57,52,100,0);}}
.aosev-app .nu-mode{font-size:10.5px;font-weight:800;padding:4px 10px;border-radius:20px;background:rgba(57,52,100,.12);color:var(--indigo);border:1px solid rgba(57,52,100,.18);}
.aosev-app .nextup h4{margin:0;font-size:17px;font-weight:800;line-height:1.2;cursor:pointer;letter-spacing:-.2px;}
.aosev-app .nextup h4:hover{text-decoration:underline;text-underline-offset:2px;}
.aosev-app .nu-date{font-size:12px;color:#393464;font-weight:600;margin:5px 0 13px;}
.aosev-app /* shared revamped timer */
  .lvclk{display:flex;gap:8px;margin:6px 0 4px;}
.aosev-app .lvt{position:relative;overflow:hidden;background:var(--indigo);border-radius:11px;flex:1;min-width:0;padding:13px 0 9px;text-align:center;}
.aosev-app .lvt .bar{position:absolute;left:0;bottom:0;width:5px;height:0;background:var(--cyan);transition:height .7s ease;}
.aosev-app .lvt b{display:block;font-size:27px;font-weight:800;color:#fff;font-variant-numeric:tabular-nums;line-height:1;letter-spacing:-.5px;}
.aosev-app .lvt i{display:block;margin-top:6px;font-size:8.5px;letter-spacing:.5px;text-transform:uppercase;color:rgba(255,255,255,.62);font-style:normal;font-weight:800;}
.aosev-app .lvclk.lg .lvt{padding:18px 0 14px;min-width:72px;}
.aosev-app .lvclk.lg .lvt b{font-size:40px;}
.aosev-app .lvclk.lg .lvt .bar{width:6px;}
.aosev-app /* blog-style hero frame */
  .bframe{position:relative;max-width:900px;margin:34px auto 8px;padding:18px 16px 26px;}
.aosev-app .bframe::before{content:"";position:absolute;left:0;top:0;width:68%;height:74%;background:var(--cyan);border-radius:24px;z-index:0;}
.aosev-app .bframe .bphoto{position:relative;z-index:1;width:100%;height:auto;max-height:420px;aspect-ratio:16/9;object-fit:cover;border-radius:20px;display:block;box-shadow:0 30px 64px -30px rgba(57,52,100,.55);background:var(--indigo-tint);}
.aosev-app .shead{margin-top:14px;text-align:center;padding-bottom:6px;}
.aosev-app .shead .ab-eyebrow{display:block;margin-bottom:10px;color:var(--cyan);font-size:13px;font-weight:800;letter-spacing:.2em;text-transform:uppercase;}
.aosev-app .shead .ab-post-meta{display:flex;gap:14px;flex-wrap:wrap;align-items:center;justify-content:center;font-size:13px;font-weight:600;color:var(--ink-soft);}
.aosev-app .shead .ab-post-meta .ab-dot{width:4px;height:4px;border-radius:50%;background:var(--ink-faint);}
.aosev-app .shead h1{margin:12px 0 0;font-size:clamp(28px,3.6vw,44px);font-weight:800;letter-spacing:-.02em;line-height:1.1;color:var(--indigo);}
.aosev-app .shead .lead{margin:10px 0 0;font-size:15.5px;color:var(--ink-soft);max-width:680px;}
.aosev-app .nu-foot{display:flex;align-items:center;justify-content:space-between;gap:10px;}
.aosev-app .nu-foot .when{font-size:12px;color:#393464;font-weight:600;font-variant-numeric:tabular-nums;}
.aosev-app .nu-foot a{font-size:12px;color:var(--indigo);font-weight:800;cursor:pointer;display:inline-flex;align-items:center;gap:4px;text-decoration:underline;text-underline-offset:2px;}
.aosev-app .nu-foot a:hover{gap:7px;}
.aosev-app /* category dropdown */
  .selectwrap{position:relative;}
.aosev-app .selectwrap select{width:100%;height:44px;appearance:none;-webkit-appearance:none;border:1px solid var(--rule);border-radius:10px;
    padding:0 38px 0 13px;font-size:14px;font-family:inherit;font-weight:600;color:var(--ink);background:#fff;cursor:pointer;outline:none;transition:.15s;}
.aosev-app .selectwrap select:hover{border-color:var(--rule-soft);}
.aosev-app .selectwrap select:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(0,174,254,.16);}
.aosev-app .selectwrap .chev{position:absolute;right:13px;top:50%;transform:translateY(-50%);pointer-events:none;color:var(--ink-faint);font-size:12px;}
.aosev-app .selhint{font-size:11.5px;color:var(--ink-faint);margin-top:9px;}
.aosev-app .sublink{margin-top:12px;padding-top:12px;border-top:1px solid var(--rule);font-size:12.5px;color:var(--indigo);font-weight:700;cursor:pointer;display:inline-flex;align-items:center;gap:6px;}
.aosev-app /* SINGLE */
  .topline{display:flex;align-items:center;justify-content:space-between;gap:12px;margin:16px 0 0;flex-wrap:wrap;}
.aosev-app .back{display:inline-flex;align-items:center;gap:7px;font-size:13.5px;font-weight:700;color:var(--indigo);border:1px solid var(--rule);background:#fff;border-radius:999px;padding:9px 17px;cursor:pointer;transition:.15s;}
.aosev-app .back:hover{border-color:var(--cyan);background:var(--tint);}
.aosev-app .crumb{font-size:13px;color:var(--ink-faint);}
.aosev-app .crumb a{cursor:pointer;}
.aosev-app .crumb a:hover{color:var(--cyan);}
.aosev-app .crumb b{color:var(--indigo);font-weight:600;}
.aosev-app .shero{position:relative;margin:14px 0 0;border-radius:18px;overflow:hidden;min-height:300px;background:linear-gradient(135deg,var(--indigo-tint),var(--tint));display:flex;align-items:flex-end;}
.aosev-app .shero img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:1;}
.aosev-app .shero .scrim{position:absolute;inset:0;z-index:2;background:linear-gradient(180deg,rgba(38,33,92,.10),rgba(38,33,92,.86));}
.aosev-app .shero__in{position:relative;z-index:3;color:#fff;padding:26px 28px;}
.aosev-app .shero h1{margin:0;font-size:clamp(28px,3.6vw,44px);font-weight:800;letter-spacing:-.02em;line-height:1.1;max-width:760px;}
.aosev-app .shero .lead{margin:10px 0 0;font-size:15.5px;color:#dcd9ef;max-width:680px;}
@media(max-width:560px){.aosev-app .shero h1{font-size:26px;}}
.aosev-app .cdband{position:relative;overflow:hidden;margin:14px 0 0;border:1px solid var(--rule);border-radius:16px;padding:24px 22px;color:var(--indigo);background:#fff;text-align:center;}
.aosev-app .cdband .lvclk{max-width:540px;margin:16px auto 0;}
.aosev-app .cdband .lbl{display:inline-flex;align-items:center;gap:8px;font-size:11.5px;letter-spacing:1.1px;text-transform:uppercase;color:var(--indigo);font-weight:800;}
.aosev-app .cdband .lbl i{width:7px;height:7px;border-radius:50%;background:var(--indigo);animation:nupulse 1.8s infinite;}
.aosev-app .cdband .when{font-size:15px;color:#393464;font-weight:600;margin-top:6px;}
.aosev-app .layout{display:grid;grid-template-columns:minmax(0,1fr) 350px;gap:30px;margin:24px 0 0;align-items:start;}
@media(max-width:860px){.aosev-app .layout{grid-template-columns:1fr;}}
.aosev-app .sec{margin:0 0 26px;}
.aosev-app .sec h2{font-size:clamp(20px,2vw,26px);font-weight:800;color:var(--indigo);margin:0 0 12px;}
.aosev-app .sec p{font-size:15px;color:var(--ink-soft);margin:0 0 12px;}
.aosev-app .checks{list-style:none;padding:0;margin:0;display:grid;gap:9px;}
.aosev-app .checks li{display:flex;gap:10px;align-items:flex-start;font-size:14.5px;}
.aosev-app .checks li .ck{flex:none;width:22px;height:22px;border-radius:50%;background:var(--tint);color:var(--indigo);display:grid;place-items:center;font-size:13px;font-weight:800;}
.aosev-app .agenda{border:1px solid var(--rule);border-radius:14px;overflow:hidden;}
.aosev-app .agenda .arow{display:grid;grid-template-columns:90px 1fr;gap:14px;padding:13px 16px;border-top:1px solid var(--rule);font-size:14.5px;}
.aosev-app .agenda .arow:first-child{border-top:0;}
.aosev-app .agenda .at{font-weight:800;color:var(--indigo);font-variant-numeric:tabular-nums;}
.aosev-app .sticky{position:sticky;top:18px;display:grid;gap:18px;}
@media(max-width:860px){.aosev-app .sticky{position:static;}}
.aosev-app .panel{border:1px solid var(--rule);border-radius:16px;background:#fff;padding:18px;}
.aosev-app .panel h3{font-size:13px;text-transform:uppercase;letter-spacing:.6px;color:var(--ink-faint);font-weight:800;margin:0 0 12px;display:flex;align-items:center;justify-content:space-between;}
.aosev-app .panel h3 a{font-size:12px;color:var(--cyan);text-transform:none;letter-spacing:0;cursor:pointer;font-weight:700;}
.aosev-app .facts{display:grid;gap:12px;}
.aosev-app .fact{display:flex;gap:11px;align-items:flex-start;}
.aosev-app .fact .fi{flex:none;width:34px;height:34px;border-radius:10px;background:var(--indigo-tint);color:var(--indigo);display:grid;place-items:center;font-size:16px;}
.aosev-app .fact .fk{font-size:12px;color:var(--ink-faint);}
.aosev-app .fact .fv{font-size:14.5px;font-weight:700;color:var(--ink);}
.aosev-app .cap{margin:4px 0 0;}
.aosev-app .cap .bar{height:8px;border-radius:6px;background:var(--indigo-tint);overflow:hidden;}
.aosev-app .cap .fill{height:100%;background:var(--cyan);border-radius:6px;transition:width .4s;}
.aosev-app .cap .lab{font-size:12.5px;color:var(--ink-soft);margin-top:6px;}
.aosev-app .cap .lab b{color:var(--indigo);}
.aosev-app .calbtns{display:grid;gap:8px;margin-top:4px;}
.aosev-app .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:13px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;border-radius:999px;padding:9px 22px;cursor:pointer;border:1px solid var(--rule);background:#fff;color:var(--indigo);transition:.15s;}
.aosev-app .btn:hover{border-color:var(--cyan);background:var(--tint);}
.aosev-app .form{display:grid;gap:11px;}
.aosev-app .field label{display:block;font-size:12.5px;color:var(--ink-soft);margin:0 0 5px;font-weight:600;}
.aosev-app .field input{width:100%;height:44px;border:1px solid var(--rule);border-radius:10px;padding:0 13px;font-size:14.5px;font-family:inherit;color:var(--ink);outline:none;transition:.15s;background:#fff;}
.aosev-app .field input:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(0,174,254,.16);}
.aosev-app .consent{display:flex;gap:9px;align-items:flex-start;font-size:13px;color:var(--ink-soft);}
.aosev-app .consent input{margin-top:3px;}
.aosev-app .rsvp-sub{font-size:12.5px;color:var(--ink-soft);margin:-3px 0 13px;}
.aosev-app .attend-note{font-size:12.5px;color:var(--ink-soft);margin:13px 2px 0;line-height:1.5;}
.aosev-app .btn.primary{background:var(--cyan);border-color:var(--cyan);color:var(--indigo);font-weight:800;}
.aosev-app .rsvp-alt{font-size:12px;color:var(--ink-faint);margin:12px 0 0;text-align:center;}
.aosev-app .rsvp-alt a{color:var(--indigo);font-weight:700;cursor:pointer;text-decoration:underline;text-underline-offset:2px;}
.aosev-app .hp{position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden;}
.aosev-app .submit{height:46px;border:0;border-radius:10px;background:var(--cyan);color:var(--indigo);font-weight:800;font-size:15px;cursor:pointer;font-family:inherit;transition:.15s;}
.aosev-app .submit:hover{filter:brightness(1.05);box-shadow:0 8px 20px rgba(0,174,254,.35);}
.aosev-app .err{color:#a32d2d;font-size:12.5px;min-height:16px;}
.aosev-app .msg{display:none;text-align:center;padding:8px 0;}
.aosev-app .msg .ok{width:48px;height:48px;border-radius:50%;background:var(--tint);color:var(--indigo);display:grid;place-items:center;font-size:24px;margin:4px auto 10px;}
.aosev-app .msg h4{margin:0 0 4px;font-size:16px;color:var(--indigo);font-weight:800;}
.aosev-app .msg p{margin:0;font-size:13.5px;color:var(--ink-soft);}
@media(prefers-reduced-motion:reduce){.aosev-app *{transition:none!important;animation:none!important;}}
.aosev-app /* --- richer single-page design (coaching-home system) --- */
  .sec{margin:0 0 18px;background:#fff;border:1px solid var(--rule-soft);border-radius:16px;padding:24px;box-shadow:var(--shadow-sm);}
.aosev-app .sec h2{font-size:clamp(20px,2vw,26px);font-weight:800;color:var(--indigo);margin:0 0 12px;}
.aosev-app .sec-eyebrow{display:inline-block;font-size:13px;font-weight:800;letter-spacing:.2em;text-transform:uppercase;color:var(--cyan);margin-bottom:7px;}
.aosev-app .checks li .ck{background:rgba(0,174,254,.14);color:var(--cyan-deep);}
.aosev-app .agenda{border:0;border-radius:0;}
.aosev-app .agenda .arow{grid-template-columns:84px 1fr;padding:12px 0;border-top:1px solid var(--rule-soft);}
.aosev-app .agenda .arow:first-child{padding-top:0;}
.aosev-app .agenda .at{color:var(--cyan-deep);}
.aosev-app .panel{border:1px solid var(--rule-soft);box-shadow:var(--shadow-sm);}
.aosev-app .meet-sub{font-size:14px;color:var(--ink-soft);margin:0 0 14px;line-height:1.55;}
.aosev-app .meet-link{display:flex;align-items:center;gap:11px;background:var(--tint);border:1px solid var(--rule-soft);border-radius:10px;padding:11px 14px;}
.aosev-app .meet-link .meet-ic{font-size:20px;line-height:1;flex:none;}
.aosev-app .meet-url{font-family:ui-monospace,Menlo,Consolas,monospace;font-size:14px;font-weight:700;color:var(--indigo);flex:1;min-width:0;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;}
.aosev-app .meet-copy{border:1px solid var(--rule);background:#fff;color:var(--indigo);font-weight:800;font-size:12.5px;padding:7px 13px;border-radius:8px;cursor:pointer;flex:none;transition:.15s;font-family:inherit;}
.aosev-app .meet-copy:hover{border-color:var(--cyan);}
.aosev-app .meet-actions{margin-top:14px;}
.aosev-app .meet-actions .btn{width:100%;}
.aosev-app .meet-note{font-size:12.5px;color:var(--ink-soft);line-height:1.55;margin:13px 0 0;}
.aosev-app .facil{display:flex;gap:16px;align-items:flex-start;}
.aosev-app .facil-av{flex:none;width:56px;height:56px;border-radius:14px;background:var(--indigo);color:#fff;font-weight:800;font-size:12px;display:grid;place-items:center;letter-spacing:.04em;}
.aosev-app .facil-n{font-size:15.5px;font-weight:800;color:var(--indigo);margin-bottom:4px;}
.aosev-app .facil-d{font-size:14px;color:var(--ink-soft);line-height:1.6;}
.aosev-app .card__body h3{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.aosev-app .cdesc{font-size:.9rem;font-weight:500;color:var(--ink-soft);line-height:1.5;margin:2px 0 2px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.aosev-app .moreevents{display:grid;gap:16px;}
.aosev-app .moreevents-h{font-size:13px;text-transform:uppercase;letter-spacing:.6px;color:var(--ink-faint);font-weight:800;display:flex;align-items:center;justify-content:space-between;}
.aosev-app .moreevents-h a{font-size:12px;color:var(--cyan);text-transform:none;letter-spacing:0;cursor:pointer;font-weight:700;}
.aosev-app /* rich HTML from event fields */
  .rich{font-size:15px;color:var(--ink-soft);line-height:1.6;}
.aosev-app .rich>*:first-child{margin-top:0;}
.aosev-app .rich>*:last-child{margin-bottom:0;}
.aosev-app .rich p{margin:0 0 12px;}
.aosev-app .rich a{color:var(--cyan-deep);text-decoration:underline;text-underline-offset:2px;}
.aosev-app .rich a:hover{color:var(--indigo);}
.aosev-app .rich strong,.aosev-app .rich b{color:var(--ink);font-weight:800;}
.aosev-app .rich em,.aosev-app .rich i{font-style:italic;}
.aosev-app .rich ul,.aosev-app .rich ol{margin:0 0 12px;padding-left:20px;}
.aosev-app .rich li{margin:0 0 5px;}
.aosev-app .rich h3,.aosev-app .rich h4{color:var(--indigo);font-weight:800;margin:0 0 8px;}
.aosev-app .rich blockquote{margin:0 0 12px;padding:8px 14px;border-left:3px solid var(--cyan);background:var(--tint);border-radius:0 8px 8px 0;}
.aosev-app .facil-d.rich{font-size:14px;}
.aosev-app .meet-note.rich{font-size:12.5px;color:var(--ink-soft);}
.aosev-app .meet-note.rich p{margin:0 0 6px;}
.aosev-app /* author-written single-page body + chrome-only fallback */
  .layout.nobody{grid-template-columns:1fr;}
.aosev-app .layout.nobody .sticky{position:static;}
.aosev-app .sbody{font-size:16px;color:var(--ink);}
.aosev-app .sbody>*:first-child{margin-top:0;}
.aosev-app .sbody img{max-width:100%;height:auto;border-radius:12px;}
.aosev-app .sbody iframe,.aosev-app .sbody video{max-width:100%;}
.aosev-app .sbody h2{font-size:clamp(20px,2vw,26px);color:var(--indigo);font-weight:800;margin:22px 0 10px;}
.aosev-app .sbody h3{font-size:18px;color:var(--indigo);font-weight:800;margin:18px 0 8px;}
.aosev-app /* raw custom-HTML block: let the author's own markup control its look */
  .aosev-chtml{margin:0 0 18px;}
.aosev-app .aosev-chtml img{max-width:100%;height:auto;}
.aosev-app .aosev-chtml iframe{max-width:100%;}
.aosev-app /* search + topic + month + sort filter bar (news-page style) */
  .filters{display:grid;grid-template-columns:2fr 1fr 1fr 1fr;gap:12px;margin:0 0 16px;padding:14px;background:#fff;border:1px solid var(--rule);border-radius:14px;}
@media(max-width:880px){.aosev-app .filters{grid-template-columns:1fr 1fr;}}
@media(max-width:560px){.aosev-app .filters{grid-template-columns:1fr;}}
.aosev-app .filters .f label{display:block;font-size:11.5px;font-weight:800;letter-spacing:.4px;text-transform:uppercase;color:var(--ink-faint);margin:0 0 5px;}
.aosev-app .filters input[type="search"]{width:100%;height:44px;border:1px solid var(--rule);border-radius:10px;padding:0 13px;font-size:14px;font-family:inherit;font-weight:600;color:var(--ink);background:#fff;outline:none;transition:.15s;-webkit-appearance:none;}
.aosev-app .filters input[type="search"]:focus{border-color:var(--cyan);box-shadow:0 0 0 3px rgba(0,174,254,.16);}
.aosev-app /* compact "More events" rows */
  .mrow{display:flex;gap:12px;align-items:center;background:#fff;border:1px solid var(--rule-soft);border-radius:12px;padding:9px 12px;min-height:48px;cursor:pointer;transition:.15s;}
.aosev-app .mrow:hover,.aosev-app .mrow:focus-within{border-color:var(--cyan);box-shadow:var(--shadow-sm);}
.aosev-app .mrow-img{width:56px;height:42px;object-fit:cover;border-radius:8px;flex:none;background:linear-gradient(135deg,var(--indigo-tint),var(--tint));display:block;}
.aosev-app .mrow-b{min-width:0;flex:1;}
.aosev-app .mrow-b b{display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;font-size:13.5px;font-weight:800;color:var(--indigo);line-height:1.25;}
.aosev-app .mrow-d{display:block;font-size:12px;color:var(--ink-faint);margin-top:2px;font-variant-numeric:tabular-nums;}
.aosev-app .mrow i{margin-left:auto;flex:none;font-style:normal;color:var(--cyan);font-size:16px;font-weight:800;transition:transform .2s;}
.aosev-app .mrow:hover i{transform:translateX(4px);}
.aosev-app /* authored-HTML spacing: kill blank paragraphs and first-heading gaps */
  .rich p:empty,.aosev-app .aosev-chtml p:empty{display:none;}
.aosev-app .rich>h1:first-child,.aosev-app .rich>h2:first-child,.aosev-app .rich>h3:first-child,.aosev-app .rich>h4:first-child{margin-top:0;}
.aosev-app .aosev-chtml>*:first-child{margin-top:0;}
.aosev-app .rich h1,.aosev-app .rich h2,.aosev-app .rich h3,.aosev-app .rich h4{margin-top:14px;margin-bottom:8px;color:var(--indigo);}
AOSEV_CSS_END
);
define( 'AOSEV_JS', <<<'AOSEV_JS_END'
(function(){
  var __root=document.getElementById("AOSEV_ROOT");
  if(!__root){return;}
  try{

  var now=Date.now(), H=3600e3, D=86400e3, U="https://aosars.com/wp-content/uploads/";
  var EVENTS=(window.AOSEV_DATA&&window.AOSEV_DATA.events)||[];
  var byId={}; EVENTS.forEach(function(e){byId[e.id]=e;});
  var MEETS=(window.AOSEV_DATA&&window.AOSEV_DATA.meets)||{};
  var ALLURL=(window.AOSEV_DATA&&window.AOSEV_DATA.allUrl)||"";
  var MONTHS=["January","February","March","April","May","June","July","August","September","October","November","December"];
  var MON3=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
  var WD=["Mo","Tu","We","Th","Fr","Sa","Su"];

  var tz="Africa/Nairobi", tzLabel="EAT", gridMode="grid";
  var state=(window.AOSEV_DATA&&window.AOSEV_DATA.state&&window.AOSEV_DATA.state.view==="single")?{view:"single",id:window.AOSEV_DATA.state.id,append:!!window.AOSEV_DATA.state.append}:{view:"portal",id:null};
  var _t=new Date(), calY=_t.getFullYear(), calM=_t.getMonth(), calMode="month", selDay=null, selCat=null;
  var q="", selMonth="", sortBy="soonest";

  function pad(n){return (n<10?"0":"")+n;}
  var ESCMAP={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"};
  function esc(s){return String(s==null?"":s).replace(/[&<>"']/g,function(c){return ESCMAP[c];});}
  function stripTags(s){return String(s==null?"":s)
    .replace(/<\/(p|div|li|h[1-6]|blockquote|tr)>/gi," ")
    .replace(/<br\s*\/?>/gi," ")
    .replace(/<[^>]*>/g,"")
    .replace(/\s+/g," ").trim();}
  function initials(s){var w=String(s||"").trim().split(/\s+/).filter(Boolean);
    if(!w.length)return "AOSARS";
    return ((w[0][0]||"")+(w.length>1?(w[w.length-1][0]||""):"")).toUpperCase()||"AOSARS";}
  function dated(e){return e&&e.start>0;}
  function timeOnly(ms){if(!(ms>0))return "Time TBA";return new Date(ms).toLocaleTimeString("en-GB",{hour:"2-digit",minute:"2-digit",hour12:false,timeZone:tz})+" "+tzLabel;}
  function dateBadge(ms){if(!(ms>0))return '<span class="d">TBA</span>';var d=new Date(ms),td=new Date(now);
    var ds=d.toLocaleDateString("en-GB",{day:"numeric",month:"short",timeZone:tz});
    var ts=td.toLocaleDateString("en-GB",{day:"numeric",month:"short",timeZone:tz});
    if(ds===ts)return '<span class="d">Today</span>';var p=ds.split(" ");return p[1].toUpperCase()+'<span class="d">'+p[0]+'</span>';}
  function fullDate(e){if(!dated(e))return "Date & time to be announced";var d=new Date(e.start);
    var ds=d.toLocaleDateString("en-GB",{weekday:"short",day:"numeric",month:"long",timeZone:tz});
    var ts=d.toLocaleTimeString("en-GB",{hour:"2-digit",minute:"2-digit",hour12:false,timeZone:tz});
    var te=new Date(e.start+e.durH*H).toLocaleTimeString("en-GB",{hour:"2-digit",minute:"2-digit",hour12:false,timeZone:tz});
    return ds+" \u00b7 "+ts+"\u2013"+te+" "+tzLabel;}
  function parts(ms){var s=Math.max(0,Math.floor((ms-Date.now())/1000));return{d:Math.floor(s/86400),h:Math.floor(s/3600)%24,m:Math.floor(s/60)%60,s:s%60};}
  function mini(ms){if(!(ms>0))return "Date to be announced";var p=parts(ms);if(p.d>0)return "Starts in "+p.d+"d "+p.h+"h";if(p.h>0)return "Starts in "+p.h+"h "+p.m+"m";if(p.m>0)return "Starts in "+pad(p.m)+":"+pad(p.s);return (ms-Date.now()>0)?"Starts in "+p.s+"s":"Happening now";}
  function clockHTML(pfx,big){
    var u=[["D","days"],["H","hrs"],["M","min"],["S","sec"]];
    return '<div class="lvclk'+(big?' lg':'')+'">'+u.map(function(x){
      return '<div class="lvt"><span class="bar" id="'+pfx+x[0]+'b"></span><b id="'+pfx+x[0]+'">00</b><i>'+x[1]+'</i></div>';}).join("")+'</div>';}
  function updateClock(pfx,ms){var p=parts(ms),v={D:p.d,H:p.h,M:p.m,S:p.s},fr={D:Math.min(p.d/30,1),H:p.h/24,M:p.m/60,S:p.s/60};
    ["D","H","M","S"].forEach(function(k){set(pfx+k,pad(v[k]));var b=document.getElementById(pfx+k+"b");if(b)b.style.height=(fr[k]*100).toFixed(1)+"%";});}
  function utc(ms){return new Date(ms).toISOString().replace(/[-:]/g,"").split(".")[0]+"Z";}
  function soonest(){return EVENTS.slice().sort(function(a,b){
    if(dated(a)!==dated(b))return dated(a)?-1:1; // events without a date go last
    return a.start-b.start;});}
  function sorted(list){
    var l=list.slice();
    if(sortBy==="newest"){l.sort(function(a,b){return (b.pub||0)-(a.pub||0);});}
    else if(sortBy==="az"){l.sort(function(a,b){return String(a.t).localeCompare(String(b.t));});}
    else{l.sort(function(a,b){if(dated(a)!==dated(b))return dated(a)?-1:1;return a.start-b.start;});}
    return l;}
  function dayKey(ms){var d=new Date(ms);return d.getFullYear()+"-"+pad(d.getMonth()+1)+"-"+pad(d.getDate());}
  function cellKey(y,m,d){return y+"-"+pad(m+1)+"-"+pad(d);}
  function eventsByDay(){var x={};EVENTS.forEach(function(e){if(!dated(e))return;var k=dayKey(e.start);(x[k]=x[k]||[]).push(e);});return x;}
  function eventsByMonth(){var x={};EVENTS.forEach(function(e){if(!dated(e))return;var d=new Date(e.start);var k=d.getFullYear()+"-"+d.getMonth();x[k]=(x[k]||0)+1;});return x;}
  function categories(){var x={};EVENTS.forEach(function(e){x[e.cat]=(x[e.cat]||0)+1;});return Object.keys(x).map(function(k){return [k,x[k]];});}
  function monthOptions(){
    var x={};EVENTS.forEach(function(e){if(!dated(e))return;var d=new Date(e.start);var k=d.getFullYear()+"-"+d.getMonth();x[k]=(x[k]||0)+1;});
    return Object.keys(x).sort(function(a,b){var pa=a.split("-"),pb=b.split("-");return (pa[0]-pb[0])||(pa[1]-pb[1]);})
      .map(function(k){var p=k.split("-");return {key:k,label:MONTHS[+p[1]]+" "+p[0],count:x[k]};});}
  function haystack(e){return (e.t+" "+stripTags(e.blurb||e.lead||"")+" "+e.cat+" "+e.venue).toLowerCase();}
  function visibleEvents(){
    var needle=q.trim().toLowerCase();
    return sorted(EVENTS.filter(function(e){
      if(needle&&haystack(e).indexOf(needle)===-1)return false;
      if(selCat&&e.cat!==selCat)return false;
      if(selMonth){if(!dated(e))return false;var d=new Date(e.start);if((d.getFullYear()+"-"+d.getMonth())!==selMonth)return false;}
      if(selDay&&(!dated(e)||dayKey(e.start)!==selDay))return false;
      return true;}));}
  function filterLabel(){
    if(q.trim())return 'Results for “'+esc(q.trim())+'”';
    if(selDay){var d=new Date(selDay+"T12:00:00");return "Events on "+d.toLocaleDateString("en-GB",{weekday:"short",day:"numeric",month:"short"});}
    if(selMonth){var p=selMonth.split("-");return MONTHS[+p[1]]+" "+p[0];}
    if(selCat)return selCat; return "All upcoming events";}

  function tzbarHTML(){
    var zs=[["Africa/Nairobi","EAT"],["Africa/Lagos","WAT"],["UTC","GMT"],["Africa/Maputo","CAT"],["Africa/Johannesburg","SAST"]];
    return '<div class="tzbar"><span class="lbl">Show times in:</span>'+zs.map(function(z){
      return '<button class="tz'+(tz===z[0]?' on':'')+'" data-act="tz" data-tz="'+z[0]+'" data-label="'+z[1]+'">'+z[1]+'</button>';}).join("")+'</div>';
  }

  function calendarHTML(){
    var byDay=eventsByDay(), byMon=eventsByMonth(), todayK=dayKey(now), out="";
    if(calMode==="month"){
      var first=new Date(calY,calM,1), off=(first.getDay()+6)%7, dim=new Date(calY,calM+1,0).getDate();
      out+='<div class="cal-head"><div class="cal-nav"><button data-act="cal-prev" aria-label="Previous">&#8249;</button><button data-act="cal-next" aria-label="Next">&#8250;</button></div>'+
        '<div class="cal-title">'+MONTHS[calM]+' '+calY+'</div><button class="cal-mode" data-act="cal-mode">YEAR</button></div>';
      out+='<div class="cal-grid">'+WD.map(function(w){return '<div class="cal-wd">'+w+'</div>';}).join("");
      for(var i=0;i<off;i++)out+='<div class="cal-day empty"></div>';
      for(var d=1;d<=dim;d++){var k=cellKey(calY,calM,d),has=!!byDay[k],isT=k===todayK,isS=selDay===k;
        out+='<div class="cal-day'+(has?' has':'')+(isT?' today':'')+(isS?' sel':'')+'"'+(has?' data-act="cal-day" data-key="'+k+'" title="'+byDay[k].length+' event(s)" tabindex="0"':'')+'>'+d+(has?'<span class="cal-dot"></span>':'')+'</div>';}
      out+='</div><div class="cal-cap"><i></i> day with events \u00b7 click to filter</div>';
    }else{
      out+='<div class="cal-head"><div class="cal-nav"><button data-act="cal-prev" aria-label="Previous year">&#8249;</button><button data-act="cal-next" aria-label="Next year">&#8250;</button></div>'+
        '<div class="cal-title">'+calY+'</div><button class="cal-mode" data-act="cal-mode">MONTH</button></div>';
      out+='<div class="cal-year">';
      for(var mm=0;mm<12;mm++){var c=byMon[calY+"-"+mm]||0,cur=(mm===calM);
        out+='<div class="cal-mchip'+(cur?' cur':'')+'" data-act="cal-month" data-ym="'+calY+'-'+mm+'" tabindex="0"><div class="mn">'+MON3[mm]+'</div><div class="mc">'+(c?'<b>'+c+'</b> event'+(c>1?'s':''):'\u2014')+'</div></div>';}
      out+='</div>';
    }
    return '<div class="spanel"><h3>Calendar</h3>'+out+'</div>';
  }

  function sidebarHTML(){
    var nx=soonest()[0];
    var nextup="";
    if(nx){
      var nxDate=new Date(nx.start).toLocaleDateString("en-GB",{weekday:"short",day:"numeric",month:"short",timeZone:tz});
      nextup='<div class="nextup">'+
        '<div class="nu-top"><span class="nu-live"><i></i> Next event</span><span class="nu-mode">'+esc(nx.mode)+'</span></div>'+
        '<h4 data-act="view-event" data-id="'+nx.id+'">'+esc(nx.t)+'</h4>'+
        '<div class="nu-date">'+nxDate+' \u00b7 '+timeOnly(nx.start)+'</div>'+
        clockHTML("nu")+
        '<div class="nu-foot"><span class="when" id="nuWhen">'+mini(nx.start)+'</span><a data-act="view-event" data-id="'+nx.id+'">View details &#8594;</a></div></div>';
    }
    var opts='<option value="">All categories</option>'+categories().map(function(c){
      return '<option value="'+c[0]+'"'+(selCat===c[0]?' selected':'')+'>'+c[0]+' ('+c[1]+')</option>';}).join("");
    var cats='<div class="spanel"><h3>Filter by category</h3>'+
      '<div class="selectwrap"><select id="catSelect" aria-label="Filter by category">'+opts+'</select><span class="chev">&#9662;</span></div>'+
      '<div class="selhint">'+categories().length+' categories \u00b7 '+EVENTS.length+' events</div>'+
      '<div class="sublink" data-act="sub-all">&#11015; Subscribe (.ics feed)</div></div>';
    return '<aside class="side">'+calendarHTML()+nextup+cats+'</aside>';
  }

  function cardHTML(e){
    var full=e.cap&&e.taken>=e.cap;
    return '<article class="card'+(full?' full':'')+'" data-act="view-event" data-id="'+e.id+'" tabindex="0">'+
      '<div class="card__media"><span class="ico">'+e.icon+'</span><img src="'+e.img+'" alt="" loading="lazy" onerror="this.style.display=\'none\'"><span class="scrim"></span>'+
      '<span class="date">'+dateBadge(e.start)+'</span><span class="mode '+e.m+'">'+e.mode+'</span></div>'+
      '<div class="card__body"><h3>'+esc(e.t)+'</h3><p class="cdesc">'+esc(stripTags(e.lead))+'</p>'+
      '<div class="row"><span class="g">&#128337;</span><span class="time">'+timeOnly(e.start)+'</span></div>'+
      '<span class="cdmini'+((e.start-now)<D?' soon':'')+'"><i></i><span data-cd="'+e.id+'">'+mini(e.start)+'</span></span>'+
      '<div class="row"><span class="g">&#128249;</span>'+esc(e.venue)+(full?' &middot; <span class="soldout">Sold out</span>':'')+'</div>'+
      '<div class="foot"><span class="pill">'+esc(e.cat)+'</span><span class="more">'+(full?'Waitlist':'View event')+' <i>&#8594;</i></span></div></div></article>';
  }

  function filtersHTML(){
    var topics='<option value="">All topics</option>'+categories().map(function(c){
      return '<option value="'+esc(c[0])+'"'+(selCat===c[0]?' selected':'')+'>'+esc(c[0])+' ('+c[1]+')</option>';}).join("");
    var months='<option value="">All months</option>'+monthOptions().map(function(m){
      return '<option value="'+m.key+'"'+(selMonth===m.key?' selected':'')+'>'+m.label+' ('+m.count+')</option>';}).join("");
    var sorts=[["soonest","Soonest first"],["newest","Newest first"],["az","Title A\u2013Z"]].map(function(s){
      return '<option value="'+s[0]+'"'+(sortBy===s[0]?' selected':'')+'>'+s[1]+'</option>';}).join("");
    return '<div class="filters">'+
      '<div class="f"><label for="evSearch">Search events</label><input type="search" id="evSearch" value="'+esc(q)+'" placeholder="Search events, for example methodology" autocomplete="off"></div>'+
      '<div class="f"><label for="topicSelect">Topic</label><div class="selectwrap"><select id="topicSelect">'+topics+'</select><span class="chev">&#9662;</span></div></div>'+
      '<div class="f"><label for="monthSelect">Month</label><div class="selectwrap"><select id="monthSelect">'+months+'</select><span class="chev">&#9662;</span></div></div>'+
      '<div class="f"><label for="sortSelect">Sort by</label><div class="selectwrap"><select id="sortSelect">'+sorts+'</select><span class="chev">&#9662;</span></div></div>'+
    '</div>';
  }
  function gridInner(){
    var vis=visibleEvents();
    return vis.length?vis.map(cardHTML).join(""):(EVENTS.length?'<div class="empty-state">No events match this filter. <b data-act="clear-filter" style="color:var(--indigo);cursor:pointer">Clear filters</b></div>':'<div class="empty-state">No upcoming events are scheduled yet. Please check back soon.</div>');
  }
  function fbarInner(){
    var n=visibleEvents().length, filtered=selDay||selCat||selMonth||q.trim();
    return '<div class="lab">'+filterLabel()+'<span>'+n+' event'+(n===1?'':'s')+'</span></div>'+(filtered?'<button class="clear" data-act="clear-filter">&times; Clear filters</button>':'');
  }
  /* Refresh only the results (keeps focus in the search box while typing). */
  function refreshList(){
    var g=document.getElementById("evGrid"), f=document.getElementById("evFbar");
    if(g)g.innerHTML=gridInner(); if(f)f.innerHTML=fbarInner();
    tick();
  }
  function portalHTML(){
    var todays=EVENTS.filter(function(e){return e.today;});
    var ticker=todays.length?'<aside class="ticker"><span class="ticker__tag">TODAY</span><div class="ticker__item"><strong>'+esc(todays[0].t)+'</strong> <span>&middot; '+timeOnly(todays[0].start)+' &middot; '+esc(todays[0].venue)+'</span></div><span class="ticker__x" data-act="dismiss">&times;</span></aside>':'';
    return ''+ticker+
      '<div class="head"><div><h1 tabindex="-1" id="focusH">Upcoming events</h1><div class="stats">'+EVENTS.length+' upcoming \u00b7 <b>'+todays.length+' today</b></div></div>'+
        '<div class="view" role="group" aria-label="Layout"><button data-act="grid" class="'+(gridMode==="grid"?"on":"")+'" title="Grid">&#9707;</button><button data-act="list" class="'+(gridMode==="list"?"on":"")+'" title="List">&#9776;</button></div></div>'+
      tzbarHTML()+
      '<div class="portalwrap"><div class="pmain">'+filtersHTML()+
        '<div class="fbar" id="evFbar">'+fbarInner()+'</div>'+
        '<div class="egrid '+(gridMode==="list"?"is-list":"")+'" id="evGrid">'+gridInner()+'</div>'+
      '</div>'+sidebarHTML()+'</div>';
  }

  /* ---------- SINGLE ---------- */
  function singleHTML(id){
    var e=byId[id];
    if(!e){return '<div class="empty-state">This event could not be found. <b data-act="all-events" style="color:var(--indigo);cursor:pointer">Back to all events</b></div>';}
    var others=soonest().filter(function(x){return x.id!==id;}).slice(0,3);
    var mode=e.mode||"Online";
    var isPerson=(mode==="In-person"), isHybrid=(mode==="Hybrid"), isOnline=!isPerson&&!isHybrid;
    var platform=e.platform||"Google Meet";
    var joinUrl=(e.joinUrl||"")&&(isOnline||isHybrid)?e.joinUrl:"";
    var hasJoin=!!joinUrl&&!e.linkPrivate;
    var platformLabel=isPerson?"Location":"Platform";
    var rel=others.map(function(o){
      var when=dated(o)?(new Date(o.start).toLocaleDateString("en-GB",{day:"numeric",month:"short",timeZone:tz})+" · "+timeOnly(o.start)):"Date to be announced";
      return '<a class="mrow" data-act="view-event" data-id="'+o.id+'" tabindex="0">'+
        (o.img?'<img class="mrow-img" src="'+o.img+'" alt="" loading="lazy" onerror="this.style.display=\'none\'">':'<span class="mrow-img"></span>')+
        '<span class="mrow-b"><b>'+esc(o.t)+'</b><span class="mrow-d">'+when+'</span></span><i>&#8594;</i></a>';
    }).join("");

    /* ----- main-column sections; each is OMITTED when the event has no data for it ----- */
    var secs="";
    /* v6.3.0: the event body is one HTML content field. It renders inside the styled
       "About this event" section (custom_html first, then any legacy lead + the WP/
       Elementor authored body), so authored HTML gets the AOSARS typography. */
    var about=(e.customHtml||"")+(e.leadH||"")+(e.body||"");
    if(!about&&e.lead){ about='<p>'+esc(e.lead)+'</p>'; }
    if(about){ secs+='<div class="sec"><span class="sec-eyebrow">Overview</span><h2>About this event</h2><div class="rich">'+about+'</div></div>'; }

    var joinInner="";
    var joinDisplay=joinUrl.replace(/^https?:\/\//,"");
    if(hasJoin){
      joinInner+='<p class="meet-sub">This session runs live online on '+esc(platform)+'. The joining link is posted right here, so you can save it now.</p>'+
        '<div class="meet-link"><span class="meet-ic">&#128249;</span><span class="meet-url">'+esc(joinDisplay)+'</span><button class="meet-copy" data-act="copy-meet" data-link="'+esc(joinUrl)+'">Copy link</button></div>'+
        '<div class="meet-actions"><a class="btn primary" href="'+esc(joinUrl)+'" target="_blank" rel="noopener">&#128249; Join on '+esc(platform)+'</a></div>';
    }else if((isOnline||isHybrid)&&e.linkPrivate){
      joinInner+='<p class="meet-sub">This session runs live online on '+esc(platform)+'. The joining link is sent on registration.</p>';
    }
    if(isPerson||isHybrid){
      var loc=esc(e.venue||"")+(e.addr?((e.venue?' &middot; ':'')+esc(e.addr)):"");
      if(loc){ joinInner+='<div class="meet-link"'+(joinInner?' style="margin-top:12px"':'')+'><span class="meet-ic">&#128205;</span><span class="meet-url" style="white-space:normal">'+loc+'</span></div>'; }
    }
    if(joinInner){
      var joinHead=isPerson?"Where to find us":(isHybrid?"How to join":"Join on "+platform);
      secs+='<div class="sec"><span class="sec-eyebrow">How to join</span><h2>'+esc(joinHead)+'</h2>'+joinInner+'</div>';
    }

    if(e.covers&&e.covers.length){
      secs+='<div class="sec"><span class="sec-eyebrow">What you\'ll learn</span><h2>What you\'ll cover</h2><ul class="checks">'+e.covers.map(function(c){return '<li><span class="ck">&#10003;</span> <span class="rich">'+c+'</span></li>';}).join("")+'</ul></div>';
    }
    if(e.agenda&&e.agenda.length){
      secs+='<div class="sec"><span class="sec-eyebrow">Run of show</span><h2>Agenda</h2><div class="agenda">'+e.agenda.map(function(r){return '<div class="arow"><span class="at">'+esc(r[0])+'</span><span class="rich">'+r[1]+'</span></div>';}).join("")+'</div></div>';
    }
    if(e.facilName||e.facilBio){
      var av=e.facilName?initials(e.facilName):"AOSARS";
      secs+='<div class="sec"><span class="sec-eyebrow">Your facilitator</span><h2>'+esc(e.facilName?("Led by "+e.facilName):"Your facilitator")+'</h2><div class="facil"><div class="facil-av">'+esc(av)+'</div><div class="facil-b">'+(e.facilName?'<div class="facil-n">'+esc(e.facilName)+'</div>':'')+'<div class="facil-d rich">'+(e.facilBio||"")+'</div></div></div></div>';
    }

    var joinBtn=hasJoin?'<a class="btn primary" href="'+esc(joinUrl)+'" target="_blank" rel="noopener">&#128249; Join on '+esc(platform)+'</a>':'';
    var facts='<div class="panel"><h3>Event details <a data-act="all-events">&#8592; All events</a></h3><div class="facts">'+
      '<div class="fact"><span class="fi">&#128197;</span><div><div class="fk">Date &amp; time</div><div class="fv" id="sFacts">'+fullDate(e)+'</div></div></div>'+
      '<div class="fact"><span class="fi">&#128421;</span><div><div class="fk">Format</div><div class="fv">'+esc(e.mode)+'</div></div></div>'+
      '<div class="fact"><span class="fi">&#128249;</span><div><div class="fk">'+platformLabel+'</div><div class="fv">'+esc(isPerson?e.venue:(e.venue&&e.venue!=="Google Meet"?e.venue:platform))+(e.addr&&isPerson?'<br><span style="font-weight:400">'+esc(e.addr)+'</span>':'')+'</div></div></div>'+
      (hasJoin?'<div class="fact"><span class="fi">&#128279;</span><div><div class="fk">Join link</div><div class="fv"><a href="'+esc(joinUrl)+'" target="_blank" rel="noopener">'+esc(joinDisplay)+'</a></div></div></div>':'')+
      '<div class="fact"><span class="fi">&#127891;</span><div><div class="fk">Organiser</div><div class="fv">'+esc(e.org||"AOSARS")+'</div></div></div>'+
      '<div class="fact"><span class="fi">&#128176;</span><div><div class="fk">Fee</div><div class="fv">'+esc(e.fee)+'</div></div></div></div>'+
      '<div class="calbtns">'+joinBtn+'<a class="btn'+(hasJoin?'':' primary')+'" data-act="ics" data-id="'+e.id+'">&#11015; Add to calendar (.ics)</a><a class="btn" id="gcal" target="_blank" rel="noopener">&#128197; Google Calendar</a></div></div>';
    var relBlock='<div class="moreevents"><div class="moreevents-h">More events <a data-act="all-events">View all &#8594;</a></div>'+rel+'</div>';
    var chromeTop=''+
      '<header class="shead"><div class="ab-post-meta-block"><span class="ab-eyebrow">'+esc(e.cat)+'</span>'+
      '<div class="ab-post-meta"><span>'+esc(e.mode)+'</span><span class="ab-dot"></span>'+
      '<span>'+(dated(e)?new Date(e.start).toLocaleDateString("en-GB",{weekday:"short",day:"numeric",month:"long",timeZone:tz}):"Date TBA")+'</span><span class="ab-dot"></span>'+
      '<span>'+e.durH+' hour'+(e.durH>1?'s':'')+'</span></div></div>'+
      '<h1 tabindex="-1" id="focusH">'+esc(e.t)+'</h1></header>'+
      (e.img?'<div class="bframe"><img class="bphoto" src="'+e.img+'" alt="" loading="lazy" onerror="this.style.display=\'none\'"></div>':'')+
      (dated(e)?'<section class="cdband"><div class="lbl"><i></i> Starts in</div><div class="when" id="sWhen">'+fullDate(e)+'</div>'+clockHTML("sh",true)+'</section>':'<section class="cdband"><div class="when">Date &amp; time to be announced</div></section>')+
      tzbarHTML();
    var layout=secs
      ? '<div class="layout"><div class="main">'+secs+'</div><aside class="sticky">'+facts+relBlock+'</aside></div>'
      : '<div class="layout nobody"><div class="main">'+facts+'</div><aside class="sticky">'+relBlock+'</aside></div>';
    return chromeTop+layout;
  }

  /* ---------- router + ticking ---------- */
  function renderApp(){
    document.getElementById("AOSEV_ROOT").innerHTML = state.view==="portal" ? portalHTML() : singleHTML(state.id);
    if(state.view==="single" && byId[state.id]){ buildGcal(byId[state.id]); }
    tick();
    var h=document.getElementById("focusH"); if(h){window.scrollTo(0,0); try{h.focus();}catch(x){}}
  }
  function buildGcal(e){var a=document.getElementById("gcal"); if(!a)return;
    a.href="https://www.google.com/calendar/render?action=TEMPLATE&text="+encodeURIComponent(e.t)+"&dates="+utc(e.start)+"/"+utc(e.start+e.durH*H)+"&details="+encodeURIComponent("Hosted by AOSARS")+"&location="+encodeURIComponent(e.venue);}
  function set(id,v){var el=document.getElementById(id);if(el)el.textContent=v;}
  function tick(){
    if(state.view==="portal"){
      var nx=soonest()[0];
      if(nx&&dated(nx)){updateClock("nu",nx.start);set("nuWhen",mini(nx.start));}
      EVENTS.forEach(function(e){var c=document.querySelector('[data-cd="'+e.id+'"]');if(c)c.textContent=mini(e.start);});
    }else{
      var e=byId[state.id];
      if(e&&dated(e)){updateClock("sh",e.start);}
    }
  }
  function go(view,id){state.view=view;state.id=id||null;location.hash=view==="portal"?"events":"event/"+id;renderApp();}
  function fromHash(){var h=location.hash.replace(/^#/,"");
    if(h.indexOf("event/")===0){var id=parseInt(h.split("/")[1],10);if(byId[id]){state={view:"single",id:id};return;}}
    state={view:"portal",id:null};}
  window.addEventListener("hashchange",function(){fromHash();renderApp();});

  document.addEventListener("click",function(ev){
    var el=ev.target.closest("[data-act]"); if(!el||!el.closest(".aosev-app"))return;
    var a=el.dataset.act;
    if(a==="view-event"){var _id=parseInt(el.dataset.id,10),_e=byId[_id];
      if(_e&&_e.permalink){ev.preventDefault();location.href=_e.permalink;}else{go("single",_id);}}
    else if(a==="all-events"){if(state.view==="single"&&ALLURL){location.href=ALLURL;}else{go("portal");}}
    else if(a==="dismiss"){var t=el.closest(".ticker");if(t)t.style.display="none";}
    else if(a==="grid"){gridMode="grid";renderApp();}
    else if(a==="list"){gridMode="list";renderApp();}
    else if(a==="tz"){tz=el.dataset.tz;tzLabel=el.dataset.label;renderApp();}
    else if(a==="ics"){ev.preventDefault();downloadICS(byId[parseInt(el.dataset.id,10)]);}
    else if(a==="cal-prev"||a==="cal-next"){var dir=a==="cal-next"?1:-1;
      if(calMode==="year"){calY+=dir;}else{calM+=dir;if(calM<0){calM=11;calY--;}if(calM>11){calM=0;calY++;}}renderApp();}
    else if(a==="cal-mode"){calMode=calMode==="month"?"year":"month";renderApp();}
    else if(a==="cal-day"){var k=el.dataset.key;selDay=(selDay===k?null:k);renderApp();}
    else if(a==="cal-month"){var ym=el.dataset.ym.split("-");calY=parseInt(ym[0],10);calM=parseInt(ym[1],10);calMode="month";renderApp();}
    else if(a==="cat"){var c=el.dataset.cat;selCat=(selCat===c?null:c);renderApp();}
    else if(a==="clear-filter"){selDay=null;selCat=null;selMonth="";q="";renderApp();}
    else if(a==="sub-all"){ev.preventDefault();downloadAllICS();}
    else if(a==="copy-meet"){ev.preventDefault();var lk=el.dataset.link;if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(lk);}el.textContent="Copied";setTimeout(function(){el.textContent="Copy link";},1600);}
  });
  document.addEventListener("change",function(ev){
    if(!ev.target.closest(".aosev-app"))return;
    if(ev.target.id==="catSelect"){selCat=ev.target.value||null;renderApp();}
    else if(ev.target.id==="topicSelect"){selCat=ev.target.value||null;renderApp();}
    else if(ev.target.id==="monthSelect"){selMonth=ev.target.value||"";renderApp();}
    else if(ev.target.id==="sortSelect"){sortBy=ev.target.value||"soonest";renderApp();}
  });
  document.addEventListener("input",function(ev){
    if(ev.target.id==="evSearch"&&ev.target.closest(".aosev-app")){q=ev.target.value||"";refreshList();}
  });
  document.addEventListener("keydown",function(ev){
    if(ev.key==="Enter"||ev.key===" "){var el=ev.target.closest('[data-act="view-event"],[data-act="cal-day"],[data-act="cat"],[data-act="cal-month"]');
      if(el){ev.preventDefault();el.click();}}
  });
  function vevent(e){return "BEGIN:VEVENT\r\nUID:"+e.id+"@aosars\r\nDTSTAMP:"+utc(now)+"\r\nDTSTART:"+utc(e.start)+"\r\nDTEND:"+utc(e.start+e.durH*H)+"\r\nSUMMARY:"+e.t+"\r\nLOCATION:"+e.venue+"\r\nEND:VEVENT";}
  function downloadBlob(text,name){var b=new Blob([text],{type:"text/calendar"}),a=document.createElement("a");a.href=URL.createObjectURL(b);a.download=name;a.click();}
  function downloadICS(e){downloadBlob("BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//AOSARS Events//EN\r\n"+vevent(e)+"\r\nEND:VCALENDAR",e.t.toLowerCase().replace(/[^a-z0-9]+/g,"-")+".ics");}
  function downloadAllICS(){downloadBlob("BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//AOSARS Events//EN\r\n"+EVENTS.map(vevent).join("\r\n")+"\r\nEND:VCALENDAR","aosars-events.ics");}

  if(!(window.AOSEV_DATA&&window.AOSEV_DATA.state&&window.AOSEV_DATA.state.view==="single")){fromHash();}renderApp();setInterval(tick,1000);

  }catch(__e){ if(window.console){console.warn("[AOSARS Events]",__e);} }
})();
AOSEV_JS_END
);

/* ---- embedded HOME component assets (prototype: featured next event + carousel) ---- */
define( 'AOSEV_HOME_CSS', <<<'AOSEV_HOME_CSS_END'
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800;900&display=swap');
.aosev-home{--indigo:#393464;--cyan:#00AEFE;--ink:#000;--ink-soft:#000;--ink-faint:#393464;--ink-faded:#393464;--rule:#e6e6ec;--page:#f5f5f8;--tint:#e6f6ff;--indigo-tint:#eceaf6;--rule-soft:rgba(57,52,100,.14);--cyan-deep:#00AEFE;--shadow-sm:0 4px 16px rgba(57,52,100,.08);--shadow-md:0 14px 38px rgba(57,52,100,.14);font-family:'Montserrat',-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,sans-serif;color:var(--ink);line-height:1.6;}
.aosev-home *{box-sizing:border-box;}
.aosev-home a{color:inherit;text-decoration:none;}
.aosev-home .evt{max-width:1180px;margin:0 auto;padding:8px 0;}
.aosev-home .evt-head{display:flex;align-items:flex-end;justify-content:space-between;gap:18px;flex-wrap:wrap;margin-bottom:24px;}
.aosev-home .eyebrow{display:inline-flex;align-items:center;gap:9px;font-size:13px;letter-spacing:.2em;text-transform:uppercase;color:var(--indigo);font-weight:800;}
.aosev-home .eyebrow b{width:18px;height:4px;border-radius:2px;background:var(--cyan);display:inline-block;}
.aosev-home .evt-head h2{margin:9px 0 0;font-size:32px;font-weight:800;color:var(--indigo);letter-spacing:-.5px;}
.aosev-home .head-r{display:flex;flex-direction:column;align-items:flex-end;gap:11px;}
.aosev-home .viewall{font-size:14px;font-weight:800;color:var(--indigo);display:inline-flex;align-items:center;gap:7px;cursor:pointer;}
.aosev-home .viewall i{font-style:normal;color:var(--cyan);}
.aosev-home .tzwrap{display:flex;align-items:center;gap:8px;}
.aosev-home .tzwrap .lbl{font-size:11px;font-weight:700;color:var(--ink-faint);text-transform:uppercase;letter-spacing:.5px;}
.aosev-home .tz{display:inline-flex;background:var(--indigo-tint);border-radius:9px;padding:3px;gap:2px;}
.aosev-home .tz button{border:0;background:none;font-size:11.5px;font-weight:800;color:var(--ink-soft);padding:5px 9px;border-radius:7px;cursor:pointer;font-family:inherit;}
.aosev-home .tz button.on{background:#fff;color:var(--indigo);box-shadow:0 1px 4px rgba(57,52,100,.16);}
.aosev-home .btn{display:inline-flex;align-items:center;justify-content:center;gap:8px;font-size:13px;font-weight:700;letter-spacing:.05em;text-transform:uppercase;border-radius:999px;padding:9px 22px;cursor:pointer;border:1px solid var(--rule);background:#fff;color:var(--indigo);font-family:inherit;}
.aosev-home .btn.primary{background:var(--cyan);border-color:var(--cyan);color:var(--indigo);font-weight:800;}
.aosev-home .lvclk{display:flex;gap:9px;}
.aosev-home .lvt{position:relative;overflow:hidden;background:var(--indigo);border-radius:12px;flex:1;min-width:0;padding:15px 0 11px;text-align:center;}
.aosev-home .lvt .bar{position:absolute;left:0;bottom:0;width:6px;height:0;background:var(--cyan);transition:height .8s ease;}
.aosev-home .lvt b{display:block;font-size:34px;font-weight:800;color:#fff;font-variant-numeric:tabular-nums;line-height:1;letter-spacing:-1px;}
.aosev-home .lvt i{display:block;margin-top:8px;font-size:9px;letter-spacing:.6px;text-transform:uppercase;color:rgba(255,255,255,.62);font-style:normal;font-weight:800;}
.aosev-home .up-h{font-size:13px;text-transform:uppercase;letter-spacing:.8px;color:var(--ink-faint);font-weight:800;margin:36px 0 16px;}
.aosev-home .caro{position:relative;}
.aosev-home .caro-track{display:flex;gap:20px;overflow-x:auto;scroll-snap-type:x mandatory;scroll-behavior:smooth;padding:4px 2px 14px;scrollbar-width:none;}
.aosev-home .caro-track::-webkit-scrollbar{display:none;}
.aosev-home .card{border:1.5px solid var(--rule-soft);border-radius:16px;overflow:hidden;background:#fff;display:flex;flex-direction:column;height:100%;cursor:pointer;box-shadow:var(--shadow-sm);transition:transform .26s cubic-bezier(.2,.7,.3,1),box-shadow .26s ease,border-color .26s ease;flex:0 0 calc((100% - 40px)/3);scroll-snap-align:start;}
@media(max-width:819px){.aosev-home .card{flex:0 0 calc((100% - 20px)/2);}}
@media(max-width:559px){.aosev-home .card{flex:0 0 100%;}}
.aosev-home .card:hover,.aosev-home .card:focus-within{transform:translateY(-8px);box-shadow:var(--shadow-md);border-color:var(--cyan);}
.aosev-home .card__media{position:relative;aspect-ratio:16/9;overflow:hidden;background:linear-gradient(135deg,var(--indigo-tint),var(--tint));}
.aosev-home .card__media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:2;transition:transform .45s ease;}
.aosev-home .card:hover .card__media img{transform:scale(1.06);}
.aosev-home .card__media .scrim{position:absolute;inset:0;z-index:3;background:linear-gradient(180deg,rgba(38,33,92,.30),rgba(38,33,92,0) 42%);}
.aosev-home .date{position:absolute;left:12px;top:12px;z-index:4;background:#fff;color:var(--indigo);font-size:10.5px;font-weight:800;letter-spacing:.4px;padding:6px 9px;border-radius:9px;line-height:1.05;text-align:center;box-shadow:0 2px 8px rgba(0,0,0,.14);}
.aosev-home .date .d{font-size:16px;display:block;letter-spacing:0;}
.aosev-home .mode{position:absolute;right:12px;top:12px;z-index:4;font-size:11.5px;font-weight:800;letter-spacing:.06em;text-transform:uppercase;padding:5px 11px;border-radius:20px;box-shadow:0 2px 8px rgba(0,0,0,.14);}
.aosev-home .m-person{background:#fff;color:var(--indigo);}
.aosev-home .m-virtual{background:#e7e3fa;color:#393464;}
.aosev-home .m-hybrid{background:var(--cyan);color:var(--indigo);}
.aosev-home .card__body{padding:20px;display:flex;flex-direction:column;gap:9px;flex:1;}
.aosev-home .card__body h3{font-size:1.05rem;font-weight:800;color:var(--indigo);line-height:1.3;margin:0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.aosev-home .cdesc{font-size:.9rem;font-weight:500;color:var(--ink-soft);line-height:1.5;margin:2px 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
.aosev-home .row{display:flex;align-items:center;gap:7px;font-size:13.5px;color:var(--ink-soft);}
.aosev-home .row .g{width:16px;text-align:center;color:var(--ink-faint);flex:none;}
.aosev-home .time{font-weight:700;color:var(--ink);font-variant-numeric:tabular-nums;}
.aosev-home .cdmini{display:inline-flex;align-items:center;gap:6px;font-size:12.5px;font-weight:800;color:var(--indigo);background:rgba(0,174,254,.10);padding:6px 11px;border-radius:8px;align-self:flex-start;font-variant-numeric:tabular-nums;}
.aosev-home .cdmini i{width:6px;height:6px;border-radius:50%;background:var(--indigo);display:inline-block;}
.aosev-home .cdmini.soon{background:var(--cyan);color:var(--indigo);}
.aosev-home .foot{margin-top:auto;display:flex;align-items:center;justify-content:space-between;gap:10px;padding-top:6px;}
.aosev-home .pill{background:var(--tint);color:var(--indigo);padding:4px 11px;border-radius:999px;font-size:11.5px;font-weight:800;letter-spacing:.04em;text-transform:uppercase;}
.aosev-home .more{display:inline-flex;align-items:center;gap:6px;font-size:13.5px;font-weight:800;color:var(--indigo);}
.aosev-home .more i{font-style:normal;color:var(--cyan);font-size:16px;}
.aosev-home .feature.card{display:grid;grid-template-columns:1.1fr 1fr;flex:initial;width:auto;height:auto;}
@media(max-width:760px){.aosev-home .feature.card{grid-template-columns:1fr;}}
.aosev-home .feat-media{position:relative;min-height:340px;overflow:hidden;background:linear-gradient(135deg,var(--indigo-tint),var(--tint));}
@media(max-width:760px){.aosev-home .feat-media{min-height:0;aspect-ratio:16/9;}}
.aosev-home .feat-media img{position:absolute;inset:0;width:100%;height:100%;object-fit:cover;z-index:2;transition:transform .45s ease;}
.aosev-home .feature.card:hover .feat-media img{transform:scale(1.04);}
.aosev-home .feat-body{padding:24px;display:flex;flex-direction:column;gap:12px;}
.aosev-home .nu-live{display:inline-flex;align-items:center;gap:7px;font-size:10.5px;letter-spacing:1.2px;text-transform:uppercase;color:var(--indigo);font-weight:800;}
.aosev-home .nu-live i{width:7px;height:7px;border-radius:50%;background:var(--indigo);animation:aosevhpulse 1.8s infinite;}
@keyframes aosevhpulse{0%{box-shadow:0 0 0 0 rgba(57,52,100,.5);}70%{box-shadow:0 0 0 8px rgba(57,52,100,0);}100%{box-shadow:0 0 0 0 rgba(57,52,100,0);}}
.aosev-home .feat-body h3{margin:0;font-size:clamp(20px,2vw,26px);font-weight:800;color:var(--indigo);line-height:1.2;}
.aosev-home .feat-when{font-size:13px;color:#393464;font-weight:600;margin:-2px 0 4px;}
.aosev-home .feat-body .cdesc{-webkit-line-clamp:3;}
.aosev-home .caro-nav{display:flex;align-items:center;justify-content:center;gap:16px;margin-top:20px;}
.aosev-home .cbtn{width:44px;height:44px;border-radius:50%;border:1px solid var(--rule);background:#fff;color:var(--indigo);font-size:18px;font-weight:800;cursor:pointer;display:inline-flex;align-items:center;justify-content:center;font-family:inherit;}
.aosev-home .cbtn:hover{background:var(--indigo);color:#fff;border-color:var(--indigo);}
.aosev-home .dots{display:flex;gap:8px;}
.aosev-home .dot{width:8px;height:8px;border-radius:50%;border:0;background:var(--rule);cursor:pointer;padding:0;transition:.16s;}
.aosev-home .dot.on{background:var(--cyan);width:22px;border-radius:999px;}
.aosev-home .evt-foot{display:flex;align-items:center;justify-content:center;gap:18px;flex-wrap:wrap;margin-top:34px;padding-top:24px;border-top:1px solid var(--rule);}
@media(prefers-reduced-motion:reduce){.aosev-home *{transition:none!important;animation:none!important;}.aosev-home .caro-track{scroll-behavior:auto;}}
AOSEV_HOME_CSS_END
);
define( 'AOSEV_HOME_JS', <<<'AOSEV_HOME_JS_END'
(function(){
  var root=document.getElementById("AOSEV_HOME");
  if(!root){return;}
  try{
  var now=Date.now(), H=3600e3, D=86400e3;
  var DATA=window.AOSEV_HDATA||{};
  var EVENTS=((DATA.events)||[]).slice().sort(function(a,b){return a.start-b.start;});
  var ALL=DATA.allUrl||"";
  var TZ="Africa/Nairobi", TZLAB="EAT";
  var ZONES=[["EAT","Africa/Nairobi"],["WAT","Africa/Lagos"],["GMT","Africa/Accra"],["CAT","Africa/Harare"],["SAST","Africa/Johannesburg"]];
  var EM={"&":"&amp;","<":"&lt;",">":"&gt;",'"':"&quot;","'":"&#39;"};
  function pad(n){return (n<10?"0":"")+n;}
  function esc(s){return String(s==null?"":s).replace(/[&<>"']/g,function(c){return EM[c];});}
  function tOnly(ms){return new Date(ms).toLocaleTimeString("en-GB",{hour:"2-digit",minute:"2-digit",hour12:false,timeZone:TZ});}
  function timeOnly(ms){return tOnly(ms)+" "+TZLAB;}
  function fullWhen(ms){return new Date(ms).toLocaleDateString("en-GB",{weekday:"short",day:"numeric",month:"long",timeZone:TZ})+" · "+tOnly(ms)+" "+TZLAB;}
  function parts(ms){var s=Math.max(0,Math.floor((ms-Date.now())/1000));return{d:Math.floor(s/86400),h:Math.floor(s/3600)%24,m:Math.floor(s/60)%60,s:s%60};}
  function mini(ms){if(!(ms>0))return "Date to be announced";var p=parts(ms);if(p.d>0)return "Starts in "+p.d+"d "+p.h+"h";if(p.h>0)return "Starts in "+p.h+"h "+p.m+"m";if(p.m>0)return "Starts in "+pad(p.m)+":"+pad(p.s);return (ms-Date.now()>0)?"Starts in "+p.s+"s":"Happening now";}
  function dateBadge(ms){if(!(ms>0))return '<span class="d">TBA</span>';var ds=new Date(ms).toLocaleDateString("en-GB",{day:"numeric",month:"short",timeZone:TZ});var ts=new Date(now).toLocaleDateString("en-GB",{day:"numeric",month:"short",timeZone:TZ});if(ds===ts)return '<span class="d">Today</span>';var p=ds.split(" ");return p[1].toUpperCase()+'<span class="d">'+p[0]+'</span>';}
  function blurbOf(e){return e.blurb||e.lead||"";}
  // Feature the soonest DATED event; undated ones sort to the end of the carousel.
  EVENTS.sort(function(a,b){var da=a.start>0,db=b.start>0;if(da!==db)return da?-1:1;return a.start-b.start;});
  var FEAT=EVENTS[0], CARO=EVENTS.slice(1);
  function cardHTML(e){
    return '<article class="card" data-perma="'+esc(e.permalink)+'" tabindex="0" role="link" aria-label="Open '+esc(e.t)+'">'+
      '<div class="card__media"><img src="'+esc(e.img)+'" alt="" loading="lazy" onerror="this.style.display=\'none\'"><span class="scrim"></span>'+
      '<span class="date">'+dateBadge(e.start)+'</span><span class="mode '+esc(e.m)+'">'+esc(e.mode)+'</span></div>'+
      '<div class="card__body"><h3>'+esc(e.t)+'</h3><p class="cdesc">'+esc(blurbOf(e))+'</p>'+
      '<div class="row"><span class="g">&#128337;</span><span class="time" data-t="'+e.start+'">'+timeOnly(e.start)+'</span></div>'+
      '<span class="cdmini'+((e.start-now)<D?' soon':'')+'" data-cd="'+e.start+'"><i></i><span>'+mini(e.start)+'</span></span>'+
      '<div class="row"><span class="g">&#128249;</span>'+esc(e.venue)+'</div>'+
      '<div class="foot"><span class="pill">'+esc(e.cat)+'</span><span class="more">View event <i>&#8594;</i></span></div></div></article>';
  }
  function tzChips(){return ZONES.map(function(z){return '<button data-tz="'+z[1]+'" data-lab="'+z[0]+'"'+(z[0]===TZLAB?' class="on"':'')+'>'+z[0]+'</button>';}).join("");}
  function featHTML(){
    return '<article class="feature card" data-perma="'+esc(FEAT.permalink)+'" tabindex="0" role="link" aria-label="Open '+esc(FEAT.t)+'">'+
      '<div class="feat-media"><img src="'+esc(FEAT.img)+'" alt="" onerror="this.style.display=\'none\'"><span class="scrim"></span>'+
        '<span class="date">'+dateBadge(FEAT.start)+'</span><span class="mode '+esc(FEAT.m)+'">'+esc(FEAT.mode)+'</span></div>'+
      '<div class="feat-body"><span class="nu-live"><i></i> Next event'+(FEAT.start>0?' &middot; starts in':'')+'</span>'+
        '<h3>'+esc(FEAT.t)+'</h3><div class="feat-when">'+(FEAT.start>0?fullWhen(FEAT.start):'Date &amp; time to be announced')+'</div>'+
        '<p class="cdesc">'+esc(blurbOf(FEAT))+'</p>'+
        (FEAT.start>0?'<div class="lvclk">'+["D","H","M","S"].map(function(k,i){return '<div class="lvt"><span class="bar" data-bar="'+k+'"></span><b data-clk="'+k+'">00</b><i>'+["days","hrs","min","sec"][i]+'</i></div>';}).join("")+'</div>':'')+
        '<div class="foot"><span class="pill">'+esc(FEAT.cat)+'</span><span class="more">View event <i>&#8594;</i></span></div></div></article>';
  }
  function render(){
    if(!EVENTS.length){root.innerHTML='<div class="evt"><div class="evt-head"><div><div class="eyebrow"><b></b> What\'s on</div><h2>Upcoming events</h2></div></div><p style="color:var(--ink-faint)">No upcoming events are scheduled yet. Please check back soon.</p></div>';return;}
    root.innerHTML='<div class="evt">'+
      '<div class="evt-head"><div><div class="eyebrow"><b></b> What\'s on</div><h2>Upcoming events</h2></div>'+
        '<div class="head-r">'+(ALL?'<a class="viewall" href="'+esc(ALL)+'">View all events <i>&#8594;</i></a>':'')+
          '<div class="tzwrap"><span class="lbl">Times in</span><div class="tz" data-tzwrap>'+tzChips()+'</div></div></div></div>'+
      featHTML()+
      (CARO.length?('<div class="up-h">Coming up next</div><div class="caro"><div class="caro-track" data-track>'+CARO.map(cardHTML).join("")+'</div>'+
        '<div class="caro-nav"><button class="cbtn" data-prev aria-label="Previous">&#8249;</button><div class="dots" data-dots></div><button class="cbtn" data-next aria-label="Next">&#8250;</button></div></div>'):'')+
      '<div class="evt-foot">'+(ALL?'<a class="btn primary" href="'+esc(ALL)+'">Browse the full calendar <span>&#8594;</span></a>':'')+'<a class="btn" data-sub href="#">&#11015; Subscribe (.ics feed)</a></div>'+
    '</div>';
    buildCaro();
    tick();
  }
  function tick(){
    if(!FEAT||!(FEAT.start>0))return;
    var p=parts(FEAT.start), fr={D:Math.min(p.d/30,1),H:p.h/24,M:p.m/60,S:p.s/60}, v={D:p.d,H:p.h,M:p.m,S:p.s};
    ["D","H","M","S"].forEach(function(k){var b=root.querySelector('[data-clk="'+k+'"]');if(b)b.textContent=pad(v[k]);var bar=root.querySelector('[data-bar="'+k+'"]');if(bar)bar.style.height=(fr[k]*100).toFixed(1)+"%";});
    var ms=root.querySelectorAll('[data-cd]');for(var i=0;i<ms.length;i++){var t=parseInt(ms[i].getAttribute('data-cd'),10);var sp=ms[i].querySelector('span');if(sp)sp.textContent=mini(t);if((t-Date.now())<D)ms[i].classList.add('soon');}
  }
  var autoTimer=null;
  function buildCaro(){
    var track=root.querySelector('[data-track]'); if(!track)return;
    var dotsEl=root.querySelector('[data-dots]'), prev=root.querySelector('[data-prev]'), next=root.querySelector('[data-next]');
    var reduce=window.matchMedia&&window.matchMedia("(prefers-reduced-motion: reduce)").matches, beh=reduce?"auto":"smooth";
    function step(){var c=track.children[0];if(!c)return 1;var g=parseFloat(getComputedStyle(track).gap)||20;return c.getBoundingClientRect().width+g;}
    function maxS(){return track.scrollWidth-track.clientWidth;}
    function cur(){return Math.round(track.scrollLeft/step());}
    function sync(){var i=cur();var ds=dotsEl.querySelectorAll('.dot');for(var k=0;k<ds.length;k++){ds[k].classList.toggle('on',k===i);}}
    dotsEl.innerHTML="";var n=track.children.length;for(var j=0;j<n;j++){(function(k){var b=document.createElement('button');b.className='dot';b.setAttribute('aria-label','Go to card '+(k+1));b.addEventListener('click',function(){track.scrollTo({left:Math.min(k*step(),maxS()),behavior:beh});});dotsEl.appendChild(b);})(j);}
    sync();
    if(prev)prev.onclick=function(){var t=track.scrollLeft-step();track.scrollTo({left:t<2?maxS():t,behavior:beh});};
    if(next)next.onclick=function(){var t=track.scrollLeft+step();track.scrollTo({left:t>maxS()-2?0:t,behavior:beh});};
    var raf;track.onscroll=function(){if(raf)cancelAnimationFrame(raf);raf=requestAnimationFrame(sync);};
    if(autoTimer){clearInterval(autoTimer);}
    var paused=false, caro=root.querySelector('.caro');
    if(caro){caro.onmouseenter=function(){paused=true;};caro.onmouseleave=function(){paused=false;};caro.addEventListener('focusin',function(){paused=true;});caro.addEventListener('focusout',function(){paused=false;});}
    autoTimer=setInterval(function(){if(paused)return;var t=track.scrollLeft+step();track.scrollTo({left:t>maxS()-2?0:t,behavior:beh});},4200);
  }
  function utc(ms){return new Date(ms).toISOString().replace(/[-:]/g,"").split(".")[0]+"Z";}
  function subscribe(){
    var body=EVENTS.map(function(e){return "BEGIN:VEVENT\r\nUID:"+e.id+"@aosars\r\nDTSTAMP:"+utc(now)+"\r\nDTSTART:"+utc(e.start)+"\r\nDTEND:"+utc(e.start+(e.durH||2)*H)+"\r\nSUMMARY:"+e.t+"\r\nLOCATION:"+e.venue+"\r\nEND:VEVENT";}).join("\r\n");
    var ics="BEGIN:VCALENDAR\r\nVERSION:2.0\r\nPRODID:-//AOSARS//Events//EN\r\nCALSCALE:GREGORIAN\r\n"+body+"\r\nEND:VCALENDAR";
    var b=new Blob([ics],{type:"text/calendar"}),a=document.createElement("a");a.href=URL.createObjectURL(b);a.download="aosars-events.ics";a.click();
  }
  root.addEventListener("click",function(ev){
    var chip=ev.target.closest("[data-tzwrap] button");
    if(chip){TZ=chip.getAttribute("data-tz");TZLAB=chip.getAttribute("data-lab");render();return;}
    if(ev.target.closest("[data-sub]")){ev.preventDefault();subscribe();return;}
    var card=ev.target.closest("[data-perma]");
    if(card){var u=card.getAttribute("data-perma");if(u){location.href=u;}}
  });
  root.addEventListener("keydown",function(ev){if(ev.key==="Enter"||ev.key===" "){var card=ev.target.closest("[data-perma]");if(card){ev.preventDefault();var u=card.getAttribute("data-perma");if(u){location.href=u;}}}});
  render();
  setInterval(tick,1000);
  }catch(__e){ if(window.console){console.warn("[AOSARS Home]",__e);} }
})();
AOSEV_HOME_JS_END
);

function aosev_guard( $cb ) {
	return function ( ...$args ) use ( $cb ) {
		try { return call_user_func_array( $cb, $args ); }
		catch ( \Throwable $e ) {
			// Log UNCONDITIONALLY (not only under WP_DEBUG): a silently-swallowed fault on a live
			// host (e.g. an Elementor version mismatch breaking the ⚙ panel) must at least leave a
			// trace in the PHP error log, or it is undiagnosable from outside.
			error_log( '[AOSARS Events] guarded hook skipped: ' . ( is_string( $cb ) ? $cb : 'closure' ) . ' — ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine() );
			return isset( $args[0] ) ? $args[0] : null;
		}
	};
}
function aosev_settings() {
	$d = array(
		'currency'       => 'KES',
		'all_url'        => 'https://aosars.com/events/',
		'auto_append'    => 1,
		'hide_title'     => 0,
		'full_single'    => 1,
		'own_template'   => 1,
	);
	$s = get_option( AOSEV_OPTION, array() );
	return wp_parse_args( is_array( $s ) ? $s : array(), $d );
}

/* ---- 1. POST TYPE (post-like) + TAXONOMY ---- */
add_action( 'init', aosev_guard( 'aosev_register_cpt' ) );
function aosev_register_cpt() {
	register_post_type( 'aosars_event', array(
		'labels' => array(
			'name' => __( 'AOSARS Events', 'aosars-events' ), 'singular_name' => __( 'Event', 'aosars-events' ),
			'menu_name' => __( 'AOSARS Events', 'aosars-events' ), 'add_new_item' => __( 'Add New Event', 'aosars-events' ),
			'edit_item' => __( 'Edit Event', 'aosars-events' ), 'new_item' => __( 'New Event', 'aosars-events' ),
			'view_item' => __( 'View Event', 'aosars-events' ), 'all_items' => __( 'All Events', 'aosars-events' ),
			'search_items' => __( 'Search Events', 'aosars-events' ),
		),
		'public' => true, 'has_archive' => true, 'show_in_rest' => true,
		'show_in_nav_menus' => true, 'exclude_from_search' => false,
		'map_meta_cap' => true,
		'menu_icon' => 'dashicons-calendar-alt', 'menu_position' => 26,
		// Deliberately lean: no custom-fields / comments / trackbacks boxes cluttering the
		// event editor — the four AOSARS boxes ARE the event's fields. Keep title (name),
		// editor (optional body / Elementor), thumbnail (cover), excerpt, author, revisions.
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'author', 'revisions' ),
		'rewrite' => array( 'slug' => 'aosars-event' ),
	) );
	register_taxonomy( 'aosars_event_cat', 'aosars_event', array(
		'labels' => array( 'name' => __( 'Event Categories', 'aosars-events' ), 'singular_name' => __( 'Event Category', 'aosars-events' ), 'menu_name' => __( 'Categories', 'aosars-events' ) ),
		'public' => true, 'hierarchical' => true, 'show_admin_column' => true, 'show_in_rest' => true,
		'rewrite' => array( 'slug' => 'event-category' ),
	) );
	register_taxonomy( 'aosars_event_tag', 'aosars_event', array(
		'labels' => array( 'name' => __( 'Event Tags', 'aosars-events' ), 'singular_name' => __( 'Event Tag', 'aosars-events' ), 'menu_name' => __( 'Tags', 'aosars-events' ) ),
		'public' => true, 'hierarchical' => false, 'show_admin_column' => true, 'show_in_rest' => true,
		'rewrite' => array( 'slug' => 'event-tag' ),
	) );
}
/* Use the CLASSIC editor for events so the four meta boxes render as the agreed design:
   the 📅 Date & time, 📍 How to attend and 🎟 Register & cost cards in the right sidebar, and
   the 📝 Event details box (with the HTML content field) prominent in the main column. The
   block editor (Gutenberg) instead crams side boxes into its narrow panel and hides "normal"
   boxes in a collapsed drawer at the very bottom, which does NOT match the mockup. */
add_filter( 'use_block_editor_for_post_type', aosev_guard( 'aosev_force_classic_editor' ), 10, 2 );
function aosev_force_classic_editor( $use, $post_type ) {
	return 'aosars_event' === $post_type ? false : $use;
}
/* Belt-and-suspenders for older WP / the Classic Editor plugin's filter name. */
add_filter( 'gutenberg_can_edit_post_type', aosev_guard( 'aosev_force_classic_editor' ), 10, 2 );

/* ---- 1b. Admin list columns, like a Post (When, Mode) ---- */
add_filter( 'manage_aosars_event_posts_columns', aosev_guard( 'aosev_admin_columns' ) );
function aosev_admin_columns( $cols ) {
	$out = array();
	foreach ( $cols as $k => $v ) {
		$out[ $k ] = $v;
		if ( 'title' === $k ) {
			$out['aosev_when'] = __( 'When', 'aosars-events' );
			$out['aosev_mode'] = __( 'Mode', 'aosars-events' );
		}
	}
	return $out;
}
add_action( 'manage_aosars_event_posts_custom_column', aosev_guard( 'aosev_admin_column_value' ), 10, 2 );
function aosev_admin_column_value( $col, $post_id ) {
	if ( 'aosev_when' === $col ) {
		$s = trim( (string) get_post_meta( $post_id, '_aosev_start', true ) );
		if ( '' === $s ) { echo '&mdash;'; return; }
		$tz = (string) get_post_meta( $post_id, '_aosev_tzone', true );
		$tz = '' !== $tz ? $tz : 'Africa/Nairobi'; // same legacy default as aosev_ts()
		$ts = aosev_ts( $s, $tz );
		if ( ! $ts ) { echo '&mdash;'; return; }
		try {
			$zone  = new DateTimeZone( $tz );
			$zones = aosev_timezones();
			$lab   = isset( $zones[ $tz ] ) ? trim( strtok( $zones[ $tz ], ' ' ) ) : '';
			// wp_date() renders the instant as wall-clock in the EVENT's own zone (WP 5.3+),
			// so the column agrees with the meta box and the front-end countdown.
			echo esc_html( trim( wp_date( 'D, j M Y H:i', $ts, $zone ) . ' ' . $lab ) );
		} catch ( \Throwable $e ) {
			$t = strtotime( $s );
			echo $t ? esc_html( date_i18n( 'D, j M Y H:i', $t ) ) : '&mdash;';
		}
	} elseif ( 'aosev_mode' === $col ) {
		$m = get_post_meta( $post_id, '_aosev_mode', true );
		echo $m ? esc_html( $m ) : '&mdash;';
	}
}
add_filter( 'manage_edit-aosars_event_sortable_columns', aosev_guard( 'aosev_sortable_columns' ) );
function aosev_sortable_columns( $c ) { $c['aosev_when'] = 'aosev_when'; return $c; }
add_action( 'pre_get_posts', aosev_guard( 'aosev_admin_orderby' ) );
function aosev_admin_orderby( $q ) {
	if ( ! is_admin() || ! $q->is_main_query() || 'aosev_when' !== $q->get( 'orderby' ) ) { return; }
	// A plain meta_key sort INNER-JOINs postmeta and silently drops every event with no
	// _aosev_start — the exact trap aosev_json_events() documents avoiding. EXISTS/NOT EXISTS
	// forces LEFT-JOIN semantics: undated events stay in the list, grouped together. The
	// stored 'Y-m-d\TH:i' strings sort lexicographically = chronologically.
	$q->set( 'meta_query', array(
		'relation'      => 'OR',
		'aosev_start'   => array( 'key' => '_aosev_start', 'compare' => 'EXISTS' ),
		'aosev_nostart' => array( 'key' => '_aosev_start', 'compare' => 'NOT EXISTS' ),
	) );
	$order = strtoupper( (string) $q->get( 'order' ) );
	$q->set( 'orderby', array( 'aosev_start' => ( 'ASC' === $order ? 'ASC' : 'DESC' ) ) );
}
register_activation_hook( __FILE__, 'aosev_activate' );
function aosev_activate() {
	if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( esc_html__( 'AOSARS Events requires PHP 7.4 or newer.', 'aosars-events' ), esc_html__( 'Plugin activation blocked', 'aosars-events' ), array( 'back_link' => true ) );
	}
	try {
		// Deactivate any OTHER active copy of this plugin (duplicate folders such as
		// aosars-events-2 from repeated zip uploads). A duplicate that loads first
		// silently blocks this copy and makes updates appear to change nothing.
		if ( function_exists( 'deactivate_plugins' ) && function_exists( 'plugin_basename' ) ) {
			$me = plugin_basename( __FILE__ );
			foreach ( (array) get_option( 'active_plugins', array() ) as $ap ) {
				if ( $ap !== $me && 'aosars-events.php' === basename( (string) $ap ) ) { deactivate_plugins( $ap, true ); }
			}
		}
		aosev_register_cpt();
		flush_rewrite_rules();
		// Make events editable with Elementor, like posts/pages.
		$cpt = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
		if ( is_array( $cpt ) && ! in_array( 'aosars_event', $cpt, true ) ) {
			$cpt[] = 'aosars_event';
			update_option( 'elementor_cpt_support', $cpt );
		}
	} catch ( \Throwable $e ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) { error_log( '[AOSARS Events] activate: ' . $e->getMessage() ); }
	}
}
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );

/* Ensure "Edit with Elementor" is available for events, like posts and pages,
   even if Elementor was installed after this plugin was activated. */
add_action( 'admin_init', aosev_guard( 'aosev_ensure_elementor_support' ) );
function aosev_ensure_elementor_support() {
	if ( ! class_exists( '\Elementor\Plugin' ) && ! did_action( 'elementor/loaded' ) ) { return; }
	$cpt = get_option( 'elementor_cpt_support', array( 'page', 'post' ) );
	if ( is_array( $cpt ) && ! in_array( 'aosars_event', $cpt, true ) ) {
		$cpt[] = 'aosars_event';
		update_option( 'elementor_cpt_support', $cpt );
	}
}

/* ---- 2. DETAILS META BOXES ---- */
/* Two boxes, so the essentials are impossible to miss:
   - "Event schedule" in the SIDE column (right next to Publish): start, end, mode.
   - "Event details" below the editor, organised into clearly-labelled groups. */
/* v6.2.0 backend redesign — data entry rebuilt against Modern Events Calendar's layout.
   Four boxes replace the old two: three compact cards pinned in the sidebar (the things
   that always matter — WHEN, HOW to attend, and REGISTER) plus one roomy Details box in
   the main column. Same fields, same save, same data bridge — only the arrangement is new.
   This is mockup "#4 · Sidebar schedule + main details" from the approved outlook set. */
add_action( 'add_meta_boxes', aosev_guard( 'aosev_add_box' ) );
function aosev_add_box() {
	add_meta_box( 'aosev_schedule', __( '📅 Date & time', 'aosars-events' ), 'aosev_schedule_box_html', 'aosars_event', 'side', 'high', array( '__block_editor_compatible_meta_box' => true ) );
	add_meta_box( 'aosev_attend', __( '📍 How to attend', 'aosars-events' ), 'aosev_attend_box_html', 'aosars_event', 'side', 'high', array( '__block_editor_compatible_meta_box' => true ) );
	add_meta_box( 'aosev_register', __( '🎟 Register & cost', 'aosars-events' ), 'aosev_register_box_html', 'aosars_event', 'side', 'high', array( '__block_editor_compatible_meta_box' => true ) );
	add_meta_box( 'aosev_details', __( '📝 Event details', 'aosars-events' ), 'aosev_box_html', 'aosars_event', 'normal', 'high', array( '__block_editor_compatible_meta_box' => true ) );
}
/* Strip WordPress's stock clutter boxes from the event screen so only the four AOSARS
   boxes (plus Publish / Categories / Tags / Featured image) remain — the agreed design.
   Done here as well as via unsupported features so the screen is clean regardless. */
add_action( 'add_meta_boxes_aosars_event', aosev_guard( 'aosev_strip_core_boxes' ), 100 );
function aosev_strip_core_boxes() {
	foreach ( array( 'postcustom', 'commentsdiv', 'commentstatusdiv', 'trackbacksdiv', 'postexcerpt', 'authordiv', 'slugdiv', 'revisionsdiv', 'formatdiv' ) as $box ) {
		foreach ( array( 'normal', 'advanced', 'side' ) as $ctx ) { remove_meta_box( $box, 'aosars_event', $ctx ); }
	}
}
/* Single source of truth for which field lives in which box (MEC-benchmarked grouping). */
function aosev_box_map() {
	return array(
		'schedule' => array( 'start', 'end', 'tzone', 'mode' ),
		'attend'   => array( 'platform', 'join_url', 'code', 'link_private', 'venue', 'address' ),
		'register' => array( 'fee', 'capacity', 'taken', 'url', 'organiser' ),
		'details'  => array( 'custom_html', 'summary', 'icon', 'use_builder' ),
	);
}
/* SAVE RECEIPT: after each save, tell the editor exactly what was received and stored — a
   failing entry flow becomes a readable message instead of a silently dateless event. */
add_action( 'admin_notices', aosev_guard( 'aosev_receipt_notice' ) );
function aosev_receipt_notice() {
	if ( ! function_exists( 'get_current_screen' ) || ! function_exists( 'get_transient' ) ) { return; }
	$sc = get_current_screen();
	if ( ! $sc || 'aosars_event' !== $sc->post_type ) { return; }
	$id = isset( $_GET['post'] ) ? (int) $_GET['post'] : 0; // phpcs:ignore WordPress.Security.NonceVerification
	if ( ! $id ) { return; }
	$r     = get_transient( 'aosev_receipt_' . $id );
	$stale = false;
	if ( ! is_array( $r ) || empty( $r['outcome'] ) ) {
		// No fresh receipt — fall back to the permanent flight-recorder copy, so the LAST
		// save's outcome stays visible on the edit screen no matter when you look.
		$r     = get_post_meta( $id, '_aosev_last_save', true );
		$stale = true;
		if ( ! is_array( $r ) || empty( $r['outcome'] ) ) { return; }
	} else {
		delete_transient( 'aosev_receipt_' . $id );
	}
	$ago = '';
	if ( $stale && ! empty( $r['t'] ) && function_exists( 'human_time_diff' ) ) {
		$ago = ' <em>(' . esc_html( sprintf( __( 'last save, %s ago', 'aosars-events' ), human_time_diff( (int) $r['t'], time() ) ) ) . ')</em>';
	}
	if ( 'ok' === $r['outcome'] ) {
		$bits = array();
		if ( isset( $r['stored']['start'] ) && '' !== $r['stored']['start'] ) {
			$tz  = isset( $r['stored']['tzone'] ) && $r['stored']['tzone'] ? $r['stored']['tzone'] : 'Africa/Nairobi';
			$ts  = aosev_ts( $r['stored']['start'], $tz );
			$zs  = aosev_timezones();
			$lab = isset( $zs[ $tz ] ) ? trim( strtok( $zs[ $tz ], ' ' ) ) : '';
			try { $when = wp_date( 'D j M Y, H:i', $ts, new DateTimeZone( $tz ) ) . ' ' . $lab; } catch ( \Throwable $e ) { $when = $r['stored']['start']; }
			$bits[] = sprintf( __( 'Date & time saved: %s', 'aosars-events' ), $when );
		} elseif ( array_key_exists( 'start', $r['stored'] ) ) {
			$bits[] = __( 'Start date cleared.', 'aosars-events' );
		}
		if ( ! empty( $r['stored']['join_url'] ) ) { $bits[] = __( 'Join link saved.', 'aosars-events' ); }
		if ( ! empty( $r['skipped']['start'] ) ) { $bits[] = sprintf( __( 'Start value “%s” was not understood — previous date kept.', 'aosars-events' ), $r['skipped']['start'] ); }
		if ( empty( $bits ) ) { $bits[] = __( 'Event fields received and saved.', 'aosars-events' ); }
		$extra = ! empty( $r['recovered'] ) ? ' <strong>' . esc_html__( '(Recovered via the backup channel — your hosting appears to strip form fields; the plugin worked around it.)', 'aosars-events' ) . '</strong>' : '';
		echo '<div class="notice notice-success"><p><strong>AOSARS Events ✓</strong> — ' . esc_html( implode( ' ', $bits ) ) . $extra . $ago . '</p></div>';
	} elseif ( 'no-fields' === $r['outcome'] ) {
		echo '<div class="notice notice-error"><p><strong>AOSARS Events ⚠</strong> — ' . esc_html__( 'The last save of this event arrived WITHOUT the event fields (date, join link, …), so none of them were changed. This happens when the event is saved from a screen that does not include the AOSARS boxes (e.g. Quick Edit, or Elementor’s Update button without using the ⚙ AOSARS Event details panel). To set the date: open this normal edit screen, fill “Start date & time” in the 📅 Date & time box on the right, and click Update.', 'aosars-events' ) . $ago . '</p></div>';
	} elseif ( 'elementor-sync' === $r['outcome'] ) {
		$msg = ! empty( $r['stored']['start'] )
			? sprintf( __( 'Elementor panel synced — date & time saved: %s.', 'aosars-events' ), $r['stored']['start'] )
			: __( 'Elementor save synced, but NO start date was in the panel. Set it in Elementor under ⚙ Page/Post Settings → “📅 AOSARS Event details”, or on this screen in the 📅 Date & time box.', 'aosars-events' );
		echo '<div class="notice ' . ( ! empty( $r['stored']['start'] ) ? 'notice-success' : 'notice-warning' ) . '"><p><strong>AOSARS Events</strong> — ' . esc_html( $msg ) . $ago . '</p></div>';
	}
}
/* Flag events with no start date on the edit screen, so an unset date is impossible to miss. */
add_action( 'admin_notices', aosev_guard( 'aosev_no_date_notice' ) );
function aosev_no_date_notice() {
	if ( ! function_exists( 'get_current_screen' ) ) { return; }
	$sc = get_current_screen();
	if ( ! $sc || 'aosars_event' !== $sc->post_type || 'post' !== $sc->base ) { return; }
	$id = isset( $_GET['post'] ) ? (int) $_GET['post'] : ( isset( $GLOBALS['post']->ID ) ? (int) $GLOBALS['post']->ID : 0 ); // phpcs:ignore WordPress.Security.NonceVerification
	if ( ! $id || 'auto-draft' === get_post_status( $id ) ) { return; }
	if ( '' !== trim( (string) get_post_meta( $id, '_aosev_start', true ) ) ) { return; }
	echo '<div class="notice notice-warning"><p><strong>' . esc_html__( 'This event has no start date.', 'aosars-events' ) . '</strong> '
		. esc_html__( 'Set “Start date & time” in the 📅 Event schedule box (top-right). Until then the event shows “To be announced” with no countdown.', 'aosars-events' ) . '</p></div>';
}
/* Deployment integrity: on the events screens, warn loudly if more than one copy of the
   plugin is active, and show which version/folder is actually running so "did my update
   land?" is answerable at a glance. */
add_action( 'admin_notices', aosev_guard( 'aosev_integrity_notice' ) );
function aosev_integrity_notice() {
	if ( ! function_exists( 'get_current_screen' ) ) { return; }
	$sc = get_current_screen();
	if ( ! $sc || false === strpos( (string) $sc->id, 'aosars_event' ) && false === strpos( (string) $sc->id, 'aosev-settings' ) ) { return; }
	$copies = array();
	foreach ( (array) get_option( 'active_plugins', array() ) as $ap ) {
		if ( 'aosars-events.php' === basename( (string) $ap ) ) { $copies[] = (string) $ap; }
	}
	if ( count( $copies ) > 1 ) {
		echo '<div class="notice notice-error"><p><strong>AOSARS Events:</strong> ' . esc_html( sprintf( __( '%1$d copies of the plugin are active (%2$s). Only one runs — keep one, delete the rest.', 'aosars-events' ), count( $copies ), implode( ', ', $copies ) ) ) . '</p></div>';
	}
	if ( 'edit-aosars_event' === $sc->id || false !== strpos( (string) $sc->id, 'aosev-settings' ) ) {
		echo '<div class="notice notice-info"><p>' . esc_html( sprintf( __( 'AOSARS Events v%1$s is running from wp-content/plugins/%2$s.', 'aosars-events' ), AOSEV_VER, basename( dirname( __FILE__ ) ) ) ) . '</p></div>';
	}
}
/* Curated per-event timezones — matches the viewer timezone bar on the front end. */
function aosev_timezones() {
	return array(
		'Africa/Nairobi'      => 'EAT — East Africa Time (Nairobi)',
		'Africa/Lagos'        => 'WAT — West Africa Time (Lagos)',
		'Africa/Maputo'       => 'CAT — Central Africa Time (Maputo)',
		'Africa/Johannesburg' => 'SAST — South Africa Time (Johannesburg)',
		'Africa/Accra'        => 'GMT — Greenwich Mean Time (Accra)',
		'UTC'                 => 'UTC — Coordinated Universal Time',
	);
}
function aosev_fields() {
	return array(
		'start'    => array( 'datetime-local', __( 'Start date & time', 'aosars-events' ) ),
		'end'      => array( 'datetime-local', __( 'End date & time', 'aosars-events' ) ),
		'tzone'    => array( 'tzselect', __( 'Timezone — the times above are in this zone', 'aosars-events' ) ),
		'mode'     => array( 'select', __( 'Format', 'aosars-events' ), array( 'Online', 'In-person', 'Hybrid' ) ),
		'venue'    => array( 'text', __( 'Venue (physical place, or leave for online platform)', 'aosars-events' ) ),
		'address'  => array( 'text', __( 'Address / joining note', 'aosars-events' ) ),
		'platform' => array( 'select', __( 'Online platform', 'aosars-events' ), array( 'Google Meet', 'Zoom', 'Microsoft Teams', 'Webex', 'YouTube Live', 'Other' ) ),
		'join_url' => array( 'url', __( 'Join link — full URL (e.g. https://zoom.us/j/123…)', 'aosars-events' ) ),
		'code'     => array( 'text', __( '…or a Google Meet code (abc-defg-hij) — the link is built for you', 'aosars-events' ) ),
		'link_private' => array( 'checkbox', __( 'Hide the join link on the page (shows “The joining link is sent on registration” instead)', 'aosars-events' ) ),
		'url'      => array( 'url', __( 'Registration link', 'aosars-events' ) ),
		'organiser' => array( 'text', __( 'Organiser (blank = AOSARS)', 'aosars-events' ) ),
		'fee'      => array( 'text', __( 'Fee (e.g. KES 2,500 or Free)', 'aosars-events' ) ),
		'capacity' => array( 'number', __( 'Capacity (blank = unlimited)', 'aosars-events' ) ),
		'taken'    => array( 'number', __( 'Spots taken', 'aosars-events' ) ),
		'icon'     => array( 'text', __( 'Icon emoji (optional)', 'aosars-events' ) ),
		'summary'  => array( 'textarea', __( 'Card blurb (short text shown on the events grid)', 'aosars-events' ) ),
		'custom_html' => array( 'code', __( 'Event content — write or paste HTML here (headings, lists, tables, images, even embedded video). Renders as the event’s “About this event” section. Leave blank to use the WordPress editor / Elementor body instead.', 'aosars-events' ) ),
		'use_builder' => array( 'checkbox', __( 'Design THIS event page in Elementor / the theme instead of the AOSARS layout', 'aosars-events' ) ),
	);
	// v6.3.0: the event body is now one HTML content field (custom_html) rather than the
	// separate lead / covers / agenda / facilitator fields. Legacy events that still hold
	// those meta values keep rendering them (the data bridge reads them directly); new
	// events author everything as HTML in the one content box.
}
/* Which fields live in the side "Date & time" box. Sourced from the box map. */
function aosev_schedule_keys() { $m = aosev_box_map(); return $m['schedule']; }
/* Groups for the main Details box — heading => field keys. The venue/joining and
   register groups now live in their own sidebar boxes (v6.2.0), so this box holds only
   the page content. Discoverability beats a flat list. */
function aosev_field_groups() {
	return array(
		__( '📝 Event content (HTML)', 'aosars-events' )  => array( 'custom_html' ),
		__( '🃏 Event card (grid)', 'aosars-events' )    => array( 'icon', 'summary' ),
		__( '⚙ Display', 'aosars-events' )               => array( 'use_builder' ),
	);
}
function aosev_field_html( $k, $def, $v ) {
	$t = $def[0];
	if ( 'checkbox' === $t ) {
		echo '<label style="display:block;margin:8px 0 4px;font-weight:600"><input type="checkbox" value="1" id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '" ' . checked( $v, '1', false ) . '> ' . esc_html( $def[1] ) . '</label>';
		return;
	}
	echo '<label for="aosev_' . esc_attr( $k ) . '">' . esc_html( $def[1] ) . '</label>';
	if ( 'select' === $t ) {
		echo '<select id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '"><option value="">' . esc_html__( 'Select', 'aosars-events' ) . '</option>';
		foreach ( $def[2] as $o ) { echo '<option value="' . esc_attr( $o ) . '" ' . selected( $v, $o, false ) . '>' . esc_html( $o ) . '</option>'; }
		echo '</select>';
	} elseif ( 'tzselect' === $t ) {
		// Default to EAT when unset — matches how legacy times are interpreted.
		$cur = $v ? $v : 'Africa/Nairobi';
		echo '<select id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '">';
		foreach ( aosev_timezones() as $zval => $zlabel ) { echo '<option value="' . esc_attr( $zval ) . '" ' . selected( $cur, $zval, false ) . '>' . esc_html( $zlabel ) . '</option>'; }
		echo '</select>';
	} elseif ( 'html' === $t ) {
		// Rich text: a compact visual editor whose output is filtered through wp_kses_post on save.
		if ( function_exists( 'wp_editor' ) ) {
			wp_editor( $v, 'aosev_' . $k, array(
				'textarea_name' => 'aosev_' . $k,
				'media_buttons' => false,
				'teeny'         => true,
				'textarea_rows' => 4,
				'quicktags'     => true,
			) );
		} else {
			echo '<textarea id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '">' . esc_textarea( $v ) . '</textarea>';
		}
	} elseif ( 'code' === $t ) {
		echo '<textarea id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '" rows="14" spellcheck="false" placeholder="&lt;h2&gt;What this session covers&lt;/h2&gt;&#10;&lt;p&gt;A hands-on masterclass…&lt;/p&gt;&#10;&lt;ul&gt;&#10;  &lt;li&gt;Frame a focused research question&lt;/li&gt;&#10;&lt;/ul&gt;" style="font-family:ui-monospace,Menlo,Consolas,monospace;font-size:13px;min-height:300px;white-space:pre;line-height:1.5">' . esc_textarea( $v ) . '</textarea>';
	} elseif ( 'textarea' === $t || 'lines' === $t ) {
		echo '<textarea id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '">' . esc_textarea( $v ) . '</textarea>';
	} else {
		echo '<input type="' . esc_attr( $t ) . '" id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '" value="' . esc_attr( $v ) . '">';
	}
}
function aosev_schedule_box_html( $post ) {
	wp_nonce_field( 'aosev_save', 'aosev_nonce' );
	echo '<style>#aosev_schedule label{display:block;font-weight:600;margin:10px 0 4px}#aosev_schedule input,#aosev_schedule select{width:100%}#aosev_schedule label[for="aosev_start"]::after{content:" *";color:#d63638;font-weight:800}#aosev_schedule .aosev-hint{font-size:11px;color:#646970;margin:6px 0 0}#aosev-when-preview{display:none;margin:10px 0 0;padding:9px 11px;border-radius:8px;background:#eaf7ff;border:1px solid #b6e3ff;font-size:12.5px;color:#0b5e86;font-weight:600}#aosev-when-preview b{color:#08405c}#aosev-nodate{margin:9px 0 0;padding:8px 11px;border-radius:8px;background:#fcf0f1;border:1px solid #f0b7ba;font-size:12px;color:#8a1f24;font-weight:600}#aosev_schedule .aosev-ver{font-size:10.5px;color:#8c8f94;margin:12px 0 0;text-align:right}</style>';
	$fields = aosev_fields();
	foreach ( aosev_schedule_keys() as $k ) {
		aosev_field_html( $k, $fields[ $k ], get_post_meta( $post->ID, '_aosev_' . $k, true ) );
	}
	echo '<div id="aosev-when-preview"></div>';
	echo '<div id="aosev-nodate" style="display:none">' . esc_html__( 'No start date yet — the event will show “To be announced” with no countdown until you set one.', 'aosars-events' ) . '</div>';
	echo '<p id="aosev-endwarn" style="display:none;color:#b32d2e;font-weight:600;margin:8px 0 0">' . esc_html__( 'End is not after start — the page will assume a 2-hour duration.', 'aosars-events' ) . '</p>';
	echo '<p class="aosev-hint">' . esc_html__( 'Times are read in the selected timezone. Start is required (marked *).', 'aosars-events' ) . '</p>';
	echo '<p class="aosev-hint">' . esc_html__( '⚠ The “Published on” date in the Publish box is NOT the event date — the event’s date & time is set HERE.', 'aosars-events' ) . '</p>';
	echo '<p class="aosev-ver">' . esc_html( sprintf( __( 'AOSARS Events v%s · your entries here are kept even if you jump to Elementor without clicking Update.', 'aosars-events' ), AOSEV_VER ) ) . '</p>';
	// The shared helper script lives here because this box is always present and rendered
	// first; it wires the live preview, the end<=start warning, the platform-aware
	// placeholder in the "How to attend" box, and the empty-date banner.
	aosev_box_script();
}
/* Compact renderer shared by the sidebar cards. Prints a nonce (saving must survive any
   one box being collapsed via Screen Options) plus a scoped style, then stacks fields. */
function aosev_render_side_box( $post, $keys, $box_id ) {
	wp_nonce_field( 'aosev_save', 'aosev_nonce' );
	echo '<style>#' . esc_attr( $box_id ) . ' label{display:block;font-weight:600;margin:10px 0 4px}#' . esc_attr( $box_id ) . ' input,#' . esc_attr( $box_id ) . ' select,#' . esc_attr( $box_id ) . ' textarea{width:100%}#' . esc_attr( $box_id ) . ' .aosev-note{font-size:11.5px;color:#50575e;margin:0 0 4px;line-height:1.45}</style>';
	$fields = aosev_fields();
	foreach ( $keys as $k ) {
		aosev_field_html( $k, $fields[ $k ], get_post_meta( $post->ID, '_aosev_' . $k, true ) );
	}
}
/* 📍 How to attend — format-aware: online events surface the platform + join link; the
   little note flips to venue guidance for in-person events (wired by aosev_box_script). */
function aosev_attend_box_html( $post ) {
	$map = aosev_box_map();
	echo '<p class="aosev-note" id="aosev-joinnote" style="font-size:11.5px;color:#50575e;margin:0 0 6px;line-height:1.45">' . esc_html__( 'Online / Hybrid: pick the platform, then paste its join link (a Google Meet code also works). In-person: fill Venue & Address.', 'aosars-events' ) . '</p>';
	aosev_render_side_box( $post, $map['attend'], 'aosev_attend' );
}
/* 🎟 Register & cost — fee, capacity, spots taken, registration link, organiser. */
function aosev_register_box_html( $post ) {
	$map = aosev_box_map();
	aosev_render_side_box( $post, $map['register'], 'aosev_register' );
}
function aosev_box_html( $post ) {
	// Nonce printed in BOTH boxes: saving must not depend on one box being visible.
	wp_nonce_field( 'aosev_save', 'aosev_nonce' );
	echo '<style>.aosev-mb label{display:block;font-weight:600;margin:10px 0 4px}.aosev-mb input,.aosev-mb select,.aosev-mb textarea{width:100%;max-width:640px}.aosev-mb textarea{min-height:88px}
	.aosev-mb .aosev-grp{margin:0 0 6px;padding:12px 14px;border:1px solid #e2e4e7;border-radius:8px;background:#fafafa}
	.aosev-mb .aosev-grp>h3{margin:0 0 2px;font-size:13px;text-transform:uppercase;letter-spacing:.4px;color:#1d2327}
	.aosev-mb .aosev-cols{display:grid;grid-template-columns:1fr 1fr;gap:0 22px}
	.aosev-mb .aosev-note{font-size:12px;color:#50575e;margin:0 0 8px}
	.aosev-mb .aosev-fieldnote{font-size:11px;color:#646970;margin:2px 0 0}
	@media(max-width:850px){.aosev-mb .aosev-cols{grid-template-columns:1fr}}</style><div class="aosev-mb">';
	echo '<p class="aosev-note">' . esc_html__( 'The date, join link, cost and capacity are in the sidebar boxes on the right. Here you write the event body as HTML — headings, lists, tables, images, even an embedded video — and it renders as the “About this event” section. All optional; leave the content box blank to use the WordPress editor / Elementor instead.', 'aosars-events' ) . '</p>';
	$fields = aosev_fields();
	$wide   = array( 'summary', 'custom_html', 'use_builder' );
	foreach ( aosev_field_groups() as $heading => $keys ) {
		echo '<div class="aosev-grp"><h3>' . esc_html( $heading ) . '</h3>';
		$cols = array_diff( $keys, $wide );
		if ( $cols ) {
			echo '<div class="aosev-cols">';
			foreach ( $cols as $k ) { echo '<div>'; aosev_field_html( $k, $fields[ $k ], get_post_meta( $post->ID, '_aosev_' . $k, true ) ); echo '</div>'; }
			echo '</div>';
		}
		foreach ( array_intersect( $keys, $wide ) as $k ) {
			aosev_field_html( $k, $fields[ $k ], get_post_meta( $post->ID, '_aosev_' . $k, true ) );
		}
		echo '</div>';
	}
	echo '</div>';
}
/* Meta-box helper JS: live date preview, end<=start warning, and a platform-aware
   placeholder/hint on the Join-link field. All null-guarded; degrades to plain fields. */
function aosev_box_script() {
	$tzlabels = wp_json_encode( array(
		'Africa/Nairobi' => 'EAT', 'Africa/Lagos' => 'WAT', 'Africa/Maputo' => 'CAT',
		'Africa/Johannesburg' => 'SAST', 'Africa/Accra' => 'GMT', 'UTC' => 'UTC',
	) );
	$placeholders = wp_json_encode( array(
		'Zoom' => 'https://zoom.us/j/1234567890', 'Microsoft Teams' => 'https://teams.microsoft.com/l/meetup-join/…',
		'Webex' => 'https://example.webex.com/meet/…', 'YouTube Live' => 'https://youtube.com/live/…',
		'Google Meet' => 'https://meet.google.com/abc-defg-hij (or use the code field)', 'Other' => 'https://…',
	) );
	$wk = wp_json_encode( array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' ) );
	$mo = wp_json_encode( array( 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ) );
	echo '<script>(function(){
	function $(id){return document.getElementById(id);}
	function ready(fn){if(document.readyState!=="loading"){fn();}else{document.addEventListener("DOMContentLoaded",fn);}}
	ready(function(){
	 var TZ=' . $tzlabels . ',PH=' . $placeholders . ',WK=' . $wk . ',MO=' . $mo . ';
	 var start=$("aosev_start"),end=$("aosev_end"),tz=$("aosev_tzone"),mode=$("aosev_mode"),
	     plat=$("aosev_platform"),link=$("aosev_join_url"),prev=$("aosev-when-preview"),nodate=$("aosev-nodate"),warn=$("aosev-endwarn"),note=$("aosev-joinnote");
	 function fmt(v){ if(!v)return ""; var p=v.split("T"); if(p.length<2)return ""; var d=p[0].split("-"),t=p[1].split(":");
	   var dt=new Date(+d[0],+d[1]-1,+d[2]); if(isNaN(dt))return "";
	   return WK[dt.getDay()]+" "+(+d[2])+" "+MO[+d[1]-1]+" "+d[0]+", "+t[0]+":"+t[1]; }
	 function updatePrev(){ if(!prev)return; var lab=(tz&&TZ[tz.value])?TZ[tz.value]:"EAT";
	   var s=start?fmt(start.value):"";
	   if(!s){ prev.style.display="none"; if(nodate)nodate.style.display="block"; return; }
	   if(nodate)nodate.style.display="none";
	   var e=end?fmt(end.value):""; var etime=(end&&end.value&&end.value.split("T")[1])?end.value.split("T")[1]:"";
	   prev.innerHTML="Shows on the event page as: <b>"+s+(etime&&e?"–"+etime:"")+" "+lab+"</b>";
	   prev.style.display="block"; }
	 function updateWarn(){ if(warn&&start&&end){ warn.style.display=(start.value&&end.value&&end.value<=start.value)?"block":"none"; } }
	 function updateLink(){ if(link&&plat){ var p=plat.value||"Google Meet"; link.placeholder=PH[p]||PH.Other; } }
	 function updateNote(){ if(note&&mode){ var m=mode.value; note.textContent = (m==="In-person")
	   ? "In-person event: fill Venue & Address below (platform/link are ignored)."
	   : "Online/Hybrid: pick the platform, then paste its join link (a Google Meet code also works)."; } }
	 [start,end,tz].forEach(function(el){ if(el){el.addEventListener("input",function(){updatePrev();updateWarn();});el.addEventListener("change",function(){updatePrev();updateWarn();});} });
	 if(plat){plat.addEventListener("change",updateLink);}
	 if(mode){mode.addEventListener("change",updateNote);}
	 updatePrev();updateWarn();updateLink();updateNote();
	 /* Mirror channel + Elementor-handoff auto-save. collect(): safe scalar aosev_* fields (+ nonce). */
	 var form=$("post")||document.querySelector("form[name=post]");
	 function collect(){
	   var o={},els=(form||document).querySelectorAll("input[name^=aosev_],select[name^=aosev_]");
	   for(var i=0;i<els.length;i++){var el=els[i];
	     if(el.type==="checkbox"){ if(el.checked){o[el.name]="1";} }
	     else if(o[el.name]===undefined){ o[el.name]=el.value; } }
	   return o;
	 }
	 /* (1) Mirror: prepend ONE innocuous hidden field to the form. If a host WAF or a low
	    max_input_vars strips the primary fields from the POST, the server recovers them from
	    this and flags it in the save receipt. */
	 var submitting=false;
	 if(form&&form.addEventListener){ form.addEventListener("submit",function(){
	   submitting=true;
	   try{
	     var m=document.createElement("input");m.type="hidden";m.name="wpev_m";
	     m.value=btoa(unescape(encodeURIComponent(JSON.stringify(collect()))));
	     form.insertBefore(m,form.firstChild);
	   }catch(e){}
	 }); }
	 /* (2) Abandoned-form rescue: the historical data-loss flow is typing the date here and then
	    jumping to "Edit with Elementor" (or closing the tab) WITHOUT clicking Update — the
	    autosave that fires on the handoff carries NO meta-box fields, so the typed date was
	    silently discarded. Now: when any aosev field is dirty and the page is left without a
	    submit, beacon the fields to admin-ajax (aosev_quick_save) so they persist anyway. */
	 var dirty=false;
	 document.addEventListener("input",function(ev){var t=ev.target||{};
	   if(t.name&&t.name.indexOf("aosev_")===0&&t.name!=="aosev_nonce"){dirty=true;}},true);
	 document.addEventListener("change",function(ev){var t=ev.target||{};
	   if(t.name&&t.name.indexOf("aosev_")===0&&t.name!=="aosev_nonce"){dirty=true;}},true);
	 function beacon(){
	   if(!dirty||submitting){return;}
	   try{
	     var o=collect(); o.action="aosev_quick_save";
	     var pid=$("post_ID"); o.post_id=pid?pid.value:"";
	     if(!o.post_id||!o.aosev_nonce){return;}
	     var fd=new FormData(); for(var k in o){ if(Object.prototype.hasOwnProperty.call(o,k)){fd.append(k,o[k]);} }
	     if(navigator.sendBeacon&&typeof ajaxurl!=="undefined"){ navigator.sendBeacon(ajaxurl,fd); dirty=false; }
	     else if(typeof ajaxurl!=="undefined"&&window.fetch){ fetch(ajaxurl,{method:"POST",body:fd,keepalive:true}); dirty=false; }
	   }catch(e){}
	 }
	 window.addEventListener("pagehide",beacon);
	 document.addEventListener("click",function(ev){
	   var a=ev.target&&ev.target.closest?ev.target.closest("#elementor-go-to-edit-page-link,#elementor-switch-mode-button,.elementor-switch-mode"):null;
	   if(a){beacon();}
	 },true);
	});
	})();</script>';
}
/* The "safe scalar" fields the JS mirror channel may carry: everything except the rich/large
   text types (whose values could be corrupted by the base64/JSON round trip and are never the
   fields that go missing). Includes the schedule + joining essentials. */
function aosev_mirror_keys() {
	$out = array();
	foreach ( aosev_fields() as $k => $def ) {
		if ( ! in_array( $def[0], array( 'html', 'code', 'lines', 'textarea' ), true ) ) { $out[] = $k; }
	}
	return $out;
}
/* Abandoned-form rescue endpoint: the schedule-box JS beacons the aosev_* fields here when the
   editor leaves the classic screen without submitting (e.g. the "Edit with Elementor" handoff,
   which historically discarded everything typed into the boxes). Runs the exact same
   nonce + capability + sanitisation path as a normal save (aosev_save reads the same $_POST). */
add_action( 'wp_ajax_aosev_quick_save', aosev_guard( 'aosev_quick_save_ajax' ) );
function aosev_quick_save_ajax() {
	$pid = isset( $_POST['post_id'] ) ? (int) $_POST['post_id'] : 0; // phpcs:ignore WordPress.Security.NonceVerification -- aosev_save verifies the nonce
	if ( $pid && 'aosars_event' === get_post_type( $pid ) ) {
		aosev_save( $pid, get_post( $pid ) );
	}
	wp_die( 'ok' );
}
add_action( 'save_post_aosars_event', aosev_guard( 'aosev_save' ), 10, 2 );
function aosev_save( $post_id, $post ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( function_exists( 'wp_is_post_revision' ) && wp_is_post_revision( $post_id ) ) { return; }
	// SOURCE of the field values. Normally $_POST. If the primary aosev_* fields never arrived
	// (hosts with aggressive WAF rules or a low max_input_vars can strip/truncate them) we
	// recover them from the mirror channel: one innocuous hidden field (wpev_m) that the
	// schedule-box JS prepends to the form, carrying the safe scalar fields + the nonce as
	// base64(JSON). Recovery still requires the nonce inside the payload to verify, and the
	// capability check below — it widens no security surface.
	$src = $_POST; $recovered = false;
	if ( ! isset( $src['aosev_nonce'] ) && isset( $src['wpev_m'] ) && is_string( $src['wpev_m'] ) ) {
		$dec = json_decode( base64_decode( sanitize_text_field( wp_unslash( $src['wpev_m'] ) ) ), true );
		if ( is_array( $dec ) && isset( $dec['aosev_nonce'] ) ) {
			$allowed = array( 'aosev_nonce' => 1 );
			foreach ( aosev_mirror_keys() as $mk ) { $allowed[ 'aosev_' . $mk ] = 1; }
			foreach ( $dec as $dk => $dv ) {
				if ( isset( $allowed[ $dk ] ) && is_scalar( $dv ) && ! isset( $src[ $dk ] ) ) { $src[ $dk ] = (string) $dv; }
			}
			$recovered = true;
		}
	}
	// SAVE RECEIPT / FLIGHT RECORDER: record exactly what this save delivered — which aosev_*
	// keys the request contained, the RAW start value seen, what was stored or rejected, the
	// request type and browser. Shown as an admin notice AND persisted per-event so a failing
	// entry flow on a live site is diagnosable after the fact (Settings → Diagnostics).
	$seen = array();
	foreach ( array_keys( $src ) as $sk ) { if ( 0 === strpos( (string) $sk, 'aosev_' ) && 'aosev_nonce' !== $sk ) { $seen[] = substr( (string) $sk, 6 ); } }
	$receipt = array(
		't' => time(), 'outcome' => '', 'recovered' => $recovered, 'stored' => array(), 'skipped' => array(),
		'seen'      => array_slice( $seen, 0, 30 ),
		'raw_start' => isset( $src['aosev_start'] ) && is_scalar( $src['aosev_start'] ) ? mb_substr( (string) $src['aosev_start'], 0, 40 ) : null,
		'via'       => ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ? 'ajax:' . ( isset( $_REQUEST['action'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_REQUEST['action'] ) ), 0, 24 ) : '?' ) : 'post',
		'ua'        => isset( $_SERVER['HTTP_USER_AGENT'] ) ? mb_substr( sanitize_text_field( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) ), 0, 120 ) : '',
	);
	$is_real = ! ( defined( 'DOING_CRON' ) && DOING_CRON ) && ( ! function_exists( 'get_post_status' ) || 'auto-draft' !== get_post_status( $post_id ) );
	if ( ! isset( $src['aosev_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $src['aosev_nonce'] ) ), 'aosev_save' ) ) {
		// This save carried NONE of our fields (quick edit, a programmatic save, an Elementor
		// AJAX save — its own sync runs separately — or a request whose fields were stripped
		// with no mirror). Nothing is changed; leave a receipt saying so.
		if ( $is_real ) {
			$receipt['outcome'] = 'no-fields';
			aosev_receipt_store( $post_id, $receipt );
		}
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	// An unchecked checkbox is absent from the request — but so is a checkbox whose whole meta box
	// was not rendered this request (Screen Options, or a save flow carrying our nonce but not
	// our inputs). Only resolve a checkbox when at least one sibling field of its OWN box was
	// posted, i.e. the box really rendered. Every box has always-posting text/select siblings.
	$box_of = array(); $box_posted = array();
	foreach ( aosev_box_map() as $box => $keys ) {
		$box_posted[ $box ] = false;
		foreach ( $keys as $bk ) {
			$box_of[ $bk ] = $box;
			if ( isset( $src[ 'aosev_' . $bk ] ) ) { $box_posted[ $box ] = true; }
		}
	}
	foreach ( aosev_fields() as $k => $def ) {
		$name = 'aosev_' . $k;
		if ( 'checkbox' === $def[0] ) {
			$box = isset( $box_of[ $k ] ) ? $box_of[ $k ] : '';
			if ( '' !== $box && empty( $box_posted[ $box ] ) ) { continue; } // box absent this request: keep stored value
			update_post_meta( $post_id, '_aosev_' . $k, empty( $src[ $name ] ) ? '' : '1' );
			continue;
		}
		if ( ! isset( $src[ $name ] ) ) { continue; }
		$raw = wp_unslash( $src[ $name ] );
		if ( ! is_scalar( $raw ) ) { continue; } // crafted array/object input never reaches the string sanitizers (no PHP 8 TypeError, no partial save)
		$raw = (string) $raw;
		if ( 'url' === $def[0] ) { $val = esc_url_raw( $raw ); }
		elseif ( 'datetime-local' === $def[0] ) {
			$val = aosev_clean_dt( $raw );
			if ( '' === $val && '' !== trim( $raw ) ) { $receipt['skipped'][ $k ] = $raw; continue; } // malformed non-empty: keep the stored date, never wipe it
		}
		elseif ( 'tzselect' === $def[0] ) { $val = array_key_exists( $raw, aosev_timezones() ) ? $raw : ''; }
		elseif ( 'select' === $def[0] ) { $val = ( '' === $raw || ( isset( $def[2] ) && in_array( $raw, (array) $def[2], true ) ) ) ? $raw : ''; } // closed vocabulary; '' keeps the empty option
		elseif ( 'code' === $def[0] ) { $val = current_user_can( 'unfiltered_html' ) ? $raw : wp_kses_post( $raw ); } // raw HTML for capable users, like the Custom HTML block
		elseif ( 'html' === $def[0] ) { $val = wp_kses_post( $raw ); }
		elseif ( 'lines' === $def[0] ) { $val = wp_kses_post( $raw ); } // one item per line; inline HTML allowed
		elseif ( 'textarea' === $def[0] ) { $val = sanitize_textarea_field( $raw ); }
		elseif ( 'number' === $def[0] ) { $val = '' === $raw ? '' : absint( $raw ); }
		else { $val = sanitize_text_field( $raw ); }
		update_post_meta( $post_id, '_aosev_' . $k, $val );
		if ( in_array( $k, array( 'start', 'end', 'tzone', 'mode', 'platform', 'join_url', 'code', 'fee' ), true ) ) {
			$receipt['stored'][ $k ] = mb_substr( (string) $val, 0, 80 );
		}
	}
	if ( $is_real ) {
		$receipt['outcome'] = 'ok';
		aosev_receipt_store( $post_id, $receipt );
	}
}
/* Persist a save receipt twice: a short transient (drives the immediate admin notice) and a
   PERMANENT per-event copy (_aosev_last_save) so the last save stays diagnosable after the
   fact — surfaced on the edit screen and in Settings → Diagnostics (the flight recorder). */
function aosev_receipt_store( $post_id, $receipt ) {
	if ( function_exists( 'set_transient' ) ) { set_transient( 'aosev_receipt_' . $post_id, $receipt, 600 ); }
	update_post_meta( $post_id, '_aosev_last_save', $receipt );
}
/* Accept exactly what <input type="datetime-local"> submits: '' or Y-m-d\TH:i[:s] (plus the
   space-separated Elementor variant), and — because a browser without a native picker degrades
   the field to plain text — the common HUMAN-TYPED formats too (d/m/Y H:i, d.m.Y, 12-hour with
   am/pm, "15 August 2026 14:00"), normalised to Y-m-d\TH:i. The wall-clock string is stored
   verbatim — timezone interpretation stays in aosev_ts(). Returns '' only for input that no
   interpretation understands; the caller keeps the previously stored value in that case. */
function aosev_clean_dt( $raw ) {
	$raw = trim( str_replace( ' ', 'T', sanitize_text_field( (string) $raw ) ) );
	if ( '' === $raw ) { return ''; }
	$raw = preg_replace( '/(\.\d+)?Z?$/', '', $raw ); // tolerate a trailing Z and fractional seconds
	// Accept unpadded month/day/hour too (some pickers submit them) and normalise to Y-m-d\TH:i[:s].
	if ( preg_match( '/^(\d{4})-(\d{1,2})-(\d{1,2})T(\d{1,2}):(\d{2})(?::(\d{2}))?$/', $raw, $m ) ) {
		$out = sprintf( '%04d-%02d-%02dT%02d:%02d', $m[1], $m[2], $m[3], $m[4], $m[5] );
		if ( isset( $m[6] ) && '' !== $m[6] ) { $out .= ':' . sprintf( '%02d', $m[6] ); }
		return $out;
	}
	// HUMAN-TYPED fallback: browsers without a native datetime-local picker degrade the field
	// to plain text, and typed values like "15/08/2026 14:00" used to be silently dropped —
	// the exact "I typed the date with my own eyes and it never stuck" failure. Try the common
	// human formats (day-first, as written in Kenya/UK) strictly, in order.
	$h = str_replace( 'T', ' ', $raw );
	foreach ( array( 'd/m/Y H:i', 'd/m/Y g:i a', 'd-m-Y H:i', 'd.m.Y H:i', 'Y/m/d H:i', 'j F Y H:i', 'j F Y g:i a', 'F j, Y g:i a', 'j M Y H:i' ) as $fmt ) {
		$d = DateTime::createFromFormat( '!' . $fmt, $h );
		if ( $d instanceof DateTime ) {
			$e = DateTime::getLastErrors();
			if ( is_array( $e ) && ( $e['warning_count'] > 0 || $e['error_count'] > 0 ) ) { continue; }
			return $d->format( 'Y-m-d\TH:i' );
		}
	}
	return '';
}

/* ---- 3. DATA BRIDGE: build the events array the app consumes ---- */
/* Render the event's own authored content (editor / blocks / shortcodes) as the
   single-page body. Deliberately NOT the_content, to avoid re-entrancy with our
   own the_content filter while an event is being queried. */
/* Remove the empty paragraphs wpautop leaves around block-level HTML — they render as
   large blank gaps under "About this event" when authors paste their own markup. */
function aosev_strip_empty_p( $html ) {
	return preg_replace( '#<p>(\s|&nbsp;|<br\s*/?>)*</p>#i', '', (string) $html );
}
function aosev_body_html( $post ) {
	$c = isset( $post->post_content ) ? trim( (string) $post->post_content ) : '';
	if ( '' === $c ) { return ''; }
	// Never expand our OWN shortcodes inside an event body — that would nest the whole app
	// (CSS + JS + a second AOSEV_DATA payload) inside its own data payload. Longest tags first
	// so 'aosars_event' cannot eat the prefix of the longer names. Third-party shortcodes are
	// untouched; strip_shortcodes() is deliberately NOT used because it removes ALL tags.
	$c = preg_replace( '/\[\/?aosars_(events_portal|events_home|events|event)(\s[^\]]*)?\]/i', '', $c );
	if ( function_exists( 'do_blocks' ) ) { $c = do_blocks( $c ); }
	return aosev_strip_empty_p( do_shortcode( wpautop( $c ) ) );
}
/* Safe, display-ready HTML for a stored rich field (bold/links/lists via wp_kses_post). */
function aosev_rich( $v ) {
	$v = (string) $v;
	if ( '' === $v ) { return ''; }
	return aosev_strip_empty_p( wpautop( wp_kses_post( $v ) ) );
}
/* Timezone-aware timestamp: the entered wall-clock time is interpreted in the event's
   own timezone. Events saved before timezone support default to EAT (Africa/Nairobi) —
   this CORRECTS legacy events, which were previously read as UTC and shown 3h late. */
function aosev_ts( $s, $tz ) {
	$s = trim( (string) $s );
	if ( '' === $s ) { return 0; }
	// Interpret the wall-clock string in the event's zone; if THAT ZONE is invalid, retry in
	// the documented EAT default rather than dropping to a server-zone strtotime (which would
	// silently shift a good date by the zone offset). strtotime remains only for date strings
	// DateTime itself rejects.
	foreach ( array( $tz, 'Africa/Nairobi' ) as $zone ) {
		if ( ! $zone ) { continue; }
		try {
			$d = new DateTime( $s, new DateTimeZone( (string) $zone ) );
			return $d->getTimestamp();
		} catch ( \Throwable $e ) {} // invalid zone or unparseable date — try the next interpretation
	}
	$t = strtotime( $s );
	return $t ? $t : 0;
}
/* One trimmed, non-empty item per line (inline HTML preserved). */
function aosev_lines( $s ) {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $s ) as $l ) { $l = trim( $l ); if ( '' !== $l ) { $out[] = $l; } }
	return $out;
}
/* Agenda rows: "HH:MM rest" → [time, text]; otherwise ['', text]. */
function aosev_agenda_rows( $s ) {
	$rows = array();
	foreach ( aosev_lines( $s ) as $l ) {
		if ( preg_match( '/^(\d{1,2}:\d{2})\s+(.*)$/', $l, $m ) ) { $rows[] = array( $m[1], $m[2] ); }
		else { $rows[] = array( '', $l ); }
	}
	return $rows;
}
/* True while aosev_json_events() is building its rows. aosev_append_single() checks this so a
   lazy get_the_excerpt() during the build cannot re-enter the_content -> mount -> full app
   render (which would consume the one-shot CSS/JS and pollute the excerpt with app markup). */
function aosev_rendering( $set = null ) {
	static $on = false;
	if ( null !== $set ) { $on = (bool) $set; }
	return $on;
}
function aosev_json_events( $limit = 200 ) {
	static $cache = array();
	static $building = false;
	// Test/save-hook escape hatch: aosev_json_events('flush') clears the per-request cache so a
	// long-lived process (unit harness, or a save that later renders) sees fresh data.
	if ( 'flush' === $limit ) { $cache = array(); $building = false; aosev_rendering( false ); return array( array(), array() ); }
	// Re-entrancy guard: an event body that (via shortcode/blocks) re-enters the bridge gets
	// an empty payload instead of unbounded recursion -> stack/memory fatal.
	if ( $building ) { return array( array(), array() ); }
	if ( isset( $cache[ $limit ] ) ) { return $cache[ $limit ]; }
	$building = true;
	aosev_rendering( true ); // block the_content re-injection while we build (esp. lazy get_the_excerpt)
	$rows = array(); $meets = array();
	try {
		$sync_keys = aosev_el_sync_keys(); // hoisted: was rebuilt on EVERY field read
		// Do NOT order by the _aosev_start meta here: that adds an INNER JOIN which would
		// silently drop any event missing the meta key. Fetch all published events and let
		// the app sort by start date (soonest()).
		$q = new WP_Query( array(
			'post_type' => 'aosars_event', 'post_status' => 'publish', 'posts_per_page' => $limit,
			'orderby' => 'date', 'order' => 'DESC', 'no_found_rows' => true,
		) );
		foreach ( (array) $q->posts as $p ) {
			$id  = $p->ID;
			$els = get_post_meta( $id, '_elementor_page_settings', true );
			$els = is_array( $els ) ? $els : array();
			$memo = array();
			$g = function ( $k ) use ( $id, $els, $sync_keys, &$memo ) {
				if ( array_key_exists( $k, $memo ) ) { return $memo[ $k ]; }
				$v = (string) get_post_meta( $id, '_aosev_' . $k, true );
				if ( '' === $v && in_array( $k, $sync_keys, true ) ) { $v = (string) aosev_elementor_fallback( $id, $k, $els ); }
				$memo[ $k ] = $v;
				return $v;
			};
			// Lazy excerpt: get_the_excerpt() runs the whole the_content filter chain — compute
			// it at most once, and only when no summary/lead exists.
			$excerpt = null;
			$ex = function () use ( &$excerpt, $id ) {
				if ( null === $excerpt ) { $excerpt = (string) get_the_excerpt( $id ); }
				return $excerpt;
			};
			$etz   = $g( 'tzone' );
			$start = aosev_ts( $g( 'start' ), $etz );
			$end   = aosev_ts( $g( 'end' ), $etz );
			$durH  = ( $start && $end && $end > $start ) ? round( ( $end - $start ) / 3600, 2 ) : 2;
			$mode  = $g( 'mode' ) ? $g( 'mode' ) : 'Online';
			$m     = ( 'In-person' === $mode ) ? 'm-person' : ( ( 'Hybrid' === $mode ) ? 'm-hybrid' : 'm-virtual' );
			$cap   = $g( 'capacity' );
			$terms = get_the_terms( $id, 'aosars_event_cat' );
			$cat   = ( $terms && ! is_wp_error( $terms ) ) ? $terms[0]->name : __( 'Event', 'aosars-events' );
			$rows[] = array(
				'id' => $id, 't' => get_the_title( $id ), 'cat' => $cat, 'mode' => $mode, 'm' => $m,
				'icon' => $g( 'icon' ) ? $g( 'icon' ) : '&#128197;', 'venue' => $g( 'venue' ) ? $g( 'venue' ) : 'Google Meet',
				'fee' => $g( 'fee' ) ? $g( 'fee' ) : 'Free', 'img' => has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'large' ) : '',
				'start' => $start * 1000, 'durH' => $durH, 'cap' => ( '' === $cap ? null : (int) $cap ), 'taken' => (int) $g( 'taken' ),
				'today' => ( $start && gmdate( 'Y-m-d', $start + (int) ( get_option( 'gmt_offset', 0 ) * 3600 ) ) === current_time( 'Y-m-d' ) ),
				'lead' => $g( 'lead' ) ? $g( 'lead' ) : ( $g( 'summary' ) ? $g( 'summary' ) : $ex() ),
				'blurb' => wp_strip_all_tags( $g( 'summary' ) ? $g( 'summary' ) : ( $g( 'lead' ) ? $g( 'lead' ) : $ex() ) ),
				'addr' => $g( 'address' ),
				'permalink' => get_permalink( $id ), 'url' => $g( 'url' ) ? $g( 'url' ) : get_permalink( $id ),
				'body'   => aosev_body_html( $p ),
				'leadH'  => aosev_rich( $g( 'lead' ) ),
				'covers' => aosev_lines( $g( 'covers' ) ),
				'agenda' => aosev_agenda_rows( $g( 'agenda' ) ),
				'facilName' => $g( 'facil_name' ),
				'facilBio'  => aosev_rich( $g( 'facil_bio' ) ),
				'customHtml' => (string) $g( 'custom_html' ),
				'org' => $g( 'organiser' ) ? $g( 'organiser' ) : 'AOSARS',
				'pub' => isset( $p->post_date_gmt ) && $p->post_date_gmt ? strtotime( $p->post_date_gmt . ' UTC' ) * 1000 : 0,
				'platform'    => $g( 'platform' ) ? $g( 'platform' ) : 'Google Meet',
				'joinUrl'     => $g( 'join_url' ) ? $g( 'join_url' ) : aosev_join_from_code( $g( 'code' ) ),
				'linkPrivate' => (bool) $g( 'link_private' ),
			);
			if ( $g( 'code' ) ) { $meets[ $id ] = $g( 'code' ); }
		}
		if ( function_exists( 'wp_reset_postdata' ) ) { wp_reset_postdata(); }
	} finally {
		$building = false; // never leave the latch set, even if a hook inside throws
		aosev_rendering( false );
	}
	if ( empty( $rows ) ) {
		// A live site with no published events should show a real empty state, not
		// demo content. Set the filter to true (or define AOSEV_DEMO) to preview samples.
		if ( ( defined( 'AOSEV_DEMO' ) && AOSEV_DEMO ) || apply_filters( 'aosev_use_sample_events', false ) ) {
			$cache[ $limit ] = aosev_sample_events();
			return $cache[ $limit ];
		}
		$cache[ $limit ] = array( array(), array() );
		return $cache[ $limit ];
	}
	$cache[ $limit ] = array( $rows, $meets );
	return $cache[ $limit ];
}
/* Derive a usable join URL from the Meet-code field. Admins commonly paste a full link
   into the code box — honour it (esc_url_raw) instead of double-prefixing; otherwise strip
   to Meet-code grammar and compose the URL, esc_url_raw-wrapped so quotes/spaces/control
   chars from the sanitize_text_field'd code can never smuggle into the href the JS builds.
   Stored _aosev_code meta is left untouched; the $meets map still carries the raw value. */
function aosev_join_from_code( $code ) {
	$code = trim( (string) $code );
	if ( '' === $code ) { return ''; }
	if ( preg_match( '#^https?://#i', $code ) ) { return esc_url_raw( $code ); }
	$slug = strtolower( preg_replace( '/[^A-Za-z0-9\-]/', '', $code ) ); // Meet codes are letters/digits + dashes
	return '' !== $slug ? esc_url_raw( 'https://meet.google.com/' . $slug ) : '';
}
function aosev_sample_events() {
	$now = time() * 1000; $H = 3600000; $D = 86400000;
	$mk = function ( $id, $t, $cat, $fee, $off, $durH, $cap, $tak, $icon, $lead, $covers, $agenda, $today = false ) use ( $now ) {
		return array( 'id' => $id, 't' => $t, 'cat' => $cat, 'mode' => 'Online', 'm' => 'm-virtual', 'icon' => $icon, 'venue' => 'Google Meet', 'fee' => $fee, 'img' => '', 'start' => $now + $off, 'durH' => $durH, 'cap' => $cap, 'taken' => $tak, 'today' => $today, 'lead' => $lead, 'addr' => 'Joined online, link sent on registration', 'covers' => $covers, 'agenda' => $agenda, 'permalink' => 'https://aosars.com/events/', 'url' => 'https://aosars.com/events/' );
	};
	$rows = array(
		$mk( 1, 'Systematic review masterclass', 'Research methods', 'KES 2,500', 6 * $H, 3, 50, 38, '&#128300;', 'A hands-on clinic that takes you from a fuzzy topic to a documented, reproducible systematic review.', array( 'Formulate a focused, answerable review question', 'Build and document a reproducible search string', 'Screen and appraise studies without bias', 'Map your results into a clean PRISMA flow', 'Draft the synthesis section with confidence' ), array( array( '14:00', 'Welcome and framing the review question' ), array( '14:30', 'Building the search string (LNPAMM)' ), array( '15:30', 'Screening and the PRISMA flow' ), array( '16:30', 'Synthesis and writing up' ), array( '17:00', 'Q&A and next steps' ) ), true ),
		$mk( 2, 'Writing your chapter two', 'Academic writing', 'Free', 3 * $D, 2, null, 0, '&#9999;', 'Turn a blank page into a structured Chapter Two, with paragraph templates you can write into the same week.', array( 'Outline the argument before you write', 'Map the literature into themes', 'Use paragraph templates that flow', 'Cite as you write, not after', 'Avoid the summary trap' ), array( array( '16:00', 'Framing the argument' ), array( '16:40', 'Mapping the literature' ), array( '17:20', 'Paragraph templates' ), array( '18:00', 'Q&A' ) ) ),
		$mk( 3, 'PhD proposal defence clinic', 'Doctoral support', 'KES 1,500', 8 * $D, 3, 30, 12, '&#127891;', 'Rehearse your proposal defence with a mock panel and leave with a response-to-comments plan.', array( 'Make the proposal defence-ready', "Anticipate the panel's questions", 'Tighten scope and feasibility', 'Close methodology gaps', 'Build a response matrix' ), array( array( '09:00', 'Introductions and aims' ), array( '09:45', 'Mock panel round' ), array( '11:00', 'Structured feedback' ), array( '12:00', 'Revision plan' ) ) ),
		$mk( 4, 'Data analysis with R', 'Quantitative', 'KES 3,000', 12 * $D, 5, 40, 31, '&#128202;', 'From a messy spreadsheet to a publishable analysis in R, with scripts you can rerun.', array( 'Clean and shape data in R', 'Run the right descriptive statistics', 'Fit and read a regression', 'Make plots that publish', 'Keep reproducible scripts' ), array( array( '10:00', 'Setup and import' ), array( '10:45', 'Wrangling' ), array( '12:00', 'Modelling' ), array( '14:00', 'Visualisation' ) ) ),
		$mk( 5, 'Turning your thesis into papers', 'Publishing', 'KES 2,500', 24 * $D, 3, 35, 11, '&#128221;', 'Reshape a completed thesis into one or more publishable journal articles, and pick the right journal.', array( 'Split a thesis into paper-sized claims', 'Rewrite for a journal audience', 'Choose a fitting journal', 'Build a submission-ready structure', 'Respond to reviewers' ), array( array( '09:00', 'From thesis to papers' ), array( '10:00', 'Targeting a journal' ), array( '11:15', 'Restructuring' ), array( '12:15', 'Submission and review' ) ) ),
	);
	$meets = array( 1 => 'rea-dxkq-mtv', 2 => 'qto-bwsf-hjn', 3 => 'vih-zptc-qra', 4 => 'sdn-koru-bwe', 5 => 'pbe-hsdu-yrg' );
	return array( $rows, $meets );
}

/* ---- 4. ASSETS + MOUNT ---- */
function aosev_css() {
	static $d = false; if ( $d ) { return ''; } $d = true;
	return "<style id=\"aosev-css\">\n" . AOSEV_CSS . "\n</style>";
}
function aosev_js() {
	static $d = false; if ( $d ) { return ''; } $d = true;
	return "<script id=\"aosev-js\">\n" . AOSEV_JS . "\n</script>";
}
/* Encode the app payload for inline <script> injection. Two defenses fold together here:
   (1) JSON_HEX_TAG|AMP|APOS|QUOT escape <,>,&,'," as \uXXXX inside JSON strings, so raw
       stored HTML (customHtml is raw for unfiltered_html authors; body is post_content)
       can never drive the HTML tokenizer into the script-data-double-escaped state via a
       '<!--<script>' sequence that default slash-escaping does NOT neutralize. JS decodes
       the escapes identically, so window.AOSEV_DATA is byte-identical after parse.
   (2) wp_json_encode() returns false on unrepairable UTF-8; a bare false ships
       'window.AOSEV_DATA=;' — a JS syntax error that blanks the whole portal. Drop only
       the offending row(s), then degrade to a valid empty payload, never broken JS. */
function aosev_encode_data( $data ) {
	$flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
	$json  = wp_json_encode( $data, $flags );
	if ( ( ! is_string( $json ) || '' === $json ) && isset( $data['events'] ) && is_array( $data['events'] ) ) {
		$keep = array();
		foreach ( $data['events'] as $row ) {
			if ( false !== wp_json_encode( $row, $flags ) ) { $keep[] = $row; }
		}
		$data['events'] = $keep;
		$json = wp_json_encode( $data, $flags );
	}
	if ( ! is_string( $json ) || '' === $json ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) { error_log( '[AOSARS Events] wp_json_encode failed; shipping empty payload' ); }
		if ( is_array( $data ) ) { $data['events'] = array(); }
		$json = wp_json_encode( $data, $flags );
	}
	if ( ! is_string( $json ) || '' === $json ) { $json = '{"events":[]}'; }
	return $json;
}
function aosev_mount( $state = null ) {
	try {
		// Claim the one-shot CSS/JS slots BEFORE aosev_json_events(): that call may lazily run
		// get_the_excerpt() on a single event page, which re-enters the_content ->
		// aosev_append_single -> aosev_mount and would otherwise consume the one-shot static
		// flags in aosev_css()/aosev_js(), leaving the real page with #AOSEV_ROOT but no
		// mounting JS (the "date/countdown never shows" bug). aosev_css()/aosev_js() are called
		// from nowhere else, so this reorder is safe and the emitted order is unchanged.
		$css = aosev_css();
		$js  = aosev_js();
		list( $events, $meets ) = aosev_json_events();
		$set  = aosev_settings();
		$data = array( 'events' => $events, 'meets' => (object) $meets, 'allUrl' => isset( $set['all_url'] ) ? $set['all_url'] : '' );
		if ( $state ) { $data['state'] = $state; }
		$out  = "\n<!-- aosars-events v" . AOSEV_VER . " -->\n" . $css;
		$out .= '<script>window.AOSEV_DATA=' . aosev_encode_data( $data ) . ';</script>';
		$out .= '<div class="aosev-app"><main class="wrap" id="AOSEV_ROOT"></main></div>';
		$out .= $js;
		return $out;
	} catch ( \Throwable $e ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) { error_log( '[AOSARS Events] mount: ' . $e->getMessage() ); }
		return '';
	}
}

/* ---- 5. SHORTCODES ---- */
function aosev_sc_portal( $atts = array() ) { return aosev_mount(); }
add_shortcode( 'aosars_events_portal', 'aosev_sc_portal' );
add_shortcode( 'aosars_events', 'aosev_sc_portal' );
function aosev_sc_single( $atts ) {
	$a  = shortcode_atts( array( 'id' => 0 ), $atts, 'aosars_event' );
	$id = (int) $a['id'];
	if ( ! $id && function_exists( 'get_the_ID' ) ) { $id = (int) get_the_ID(); }
	if ( ! $id || 'aosars_event' !== get_post_type( $id ) ) { return ''; }
	return aosev_mount( array( 'view' => 'single', 'id' => $id ) );
}
add_shortcode( 'aosars_event', 'aosev_sc_single' );

/* Home component: featured next event + "coming up" carousel, for a normal page. */
function aosev_home_css() {
	static $d = false; if ( $d ) { return ''; } $d = true;
	return "<style id=\"aosev-home-css\">\n" . AOSEV_HOME_CSS . "\n</style>";
}
function aosev_home_js() {
	static $d = false; if ( $d ) { return ''; } $d = true;
	return "<script id=\"aosev-home-js\">\n" . AOSEV_HOME_JS . "\n</script>";
}
function aosev_home_mount() {
	try {
		$css = aosev_home_css(); // claim one-shot slots before json_events (see aosev_mount note)
		$js  = aosev_home_js();
		list( $events, $meets ) = aosev_json_events();
		$s    = aosev_settings();
		$data = array( 'events' => $events, 'allUrl' => isset( $s['all_url'] ) ? $s['all_url'] : '' );
		$out  = "\n<!-- aosars-events v" . AOSEV_VER . " -->\n" . $css;
		$out .= '<script>window.AOSEV_HDATA=' . aosev_encode_data( $data ) . ';</script>';
		$out .= '<div class="aosev-home" id="AOSEV_HOME"></div>';
		$out .= $js;
		return $out;
	} catch ( \Throwable $e ) {
		if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) { error_log( '[AOSARS Events] home mount: ' . $e->getMessage() ); }
		return '';
	}
}
function aosev_sc_home( $atts = array() ) { return aosev_home_mount(); }
add_shortcode( 'aosars_events_home', 'aosev_sc_home' );

/* ---- 6. SINGLE CPT PAGE + SCHEMA ---- */
/* True while the Elementor editor (or its preview iframe) is loading the page. In that
   context page builders MUST be able to call the_content(), so we do NOT take over or
   replace the content — otherwise Elementor reports "you must call the_content function". */
function aosev_is_builder_edit_context() {
	// phpcs:disable WordPress.Security.NonceVerification.Recommended
	if ( isset( $_GET['elementor-preview'] ) ) { return true; }
	if ( isset( $_GET['action'] ) && 'elementor' === $_GET['action'] ) { return true; }
	if ( isset( $_REQUEST['action'] ) && in_array( $_REQUEST['action'], array( 'elementor_ajax', 'elementor' ), true ) ) { return true; }
	// phpcs:enable
	if ( class_exists( '\Elementor\Plugin' ) && isset( \Elementor\Plugin::$instance ) ) {
		$ep = \Elementor\Plugin::$instance;
		if ( isset( $ep->editor ) && is_callable( array( $ep->editor, 'is_edit_mode' ) ) && $ep->editor->is_edit_mode() ) { return true; }
		if ( isset( $ep->preview ) && is_callable( array( $ep->preview, 'is_preview_mode' ) ) && $ep->preview->is_preview_mode() ) { return true; }
	}
	return false;
}
/* PRIMARY: take over the whole event page so ONLY our design renders — site header +
   app + site footer. This bypasses the theme's own post title / featured image /
   content, so nothing is displayed twice. Falls back to the the_content path (below)
   when this is turned off, on block themes, or when Elementor is driving the layout. */
add_action( 'template_redirect', aosev_guard( 'aosev_single_takeover' ) );
function aosev_single_takeover() {
	if ( is_admin() || ! is_singular( 'aosars_event' ) || ! is_main_query() ) { return; }
	if ( ( function_exists( 'is_embed' ) && is_embed() ) || is_feed() || post_password_required() ) { return; }
	if ( aosev_is_builder_edit_context() ) { return; } // let Elementor's editor/preview use the theme template + the_content()
	$s = aosev_settings();
	if ( empty( $s['own_template'] ) ) { return; }
	$id = (int) get_the_ID();
	// Consistency: every event uses the AOSARS design UNLESS it is explicitly opted out
	// per-event. (Previously any event merely opened in Elementor was auto-skipped, which
	// made newly-created events render the theme's plain page instead of the AOSARS one.)
	if ( get_post_meta( $id, '_aosev_use_builder', true ) ) { return; }
	if ( function_exists( 'wp_is_block_theme' ) && wp_is_block_theme() ) { return; }     // block theme: use the_content fallback
	if ( ! function_exists( 'get_header' ) ) { return; }
	$app = aosev_mount( array( 'view' => 'single', 'id' => $id ) );
	if ( '' === $app ) { return; }
	get_header();
	echo '<div class="aosev-single-takeover">' . $app . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput -- app is our own generated markup.
	get_footer();
	exit;
}
add_filter( 'the_content', aosev_guard( 'aosev_append_single' ), 20 );
function aosev_append_single( $content ) {
	if ( is_admin() || ! is_singular( 'aosars_event' ) || ! in_the_loop() || ! is_main_query() ) { return $content; }
	if ( aosev_rendering() ) { return $content; } // building the data payload (e.g. a lazy excerpt) — do NOT inject
	if ( false !== strpos( $content, 'aosev-app' ) ) { return $content; }
	if ( aosev_is_builder_edit_context() ) { return $content; } // let the Elementor editor edit the real content
	$s = aosev_settings();
	if ( empty( $s['auto_append'] ) ) { return $content; }
	$id = (int) get_the_ID();
	// Only step aside when the event is explicitly opted out per-event (not merely
	// because it was once opened in Elementor).
	if ( get_post_meta( $id, '_aosev_use_builder', true ) ) { return $content; }
	// Render the full branded single design in place of the raw content. The event's
	// own body is rendered inside the design's "About this event" area, so we replace
	// $content (rather than append) to avoid showing it twice.
	return aosev_mount( array( 'view' => 'single', 'id' => $id ) );
}
add_action( 'wp_head', aosev_guard( 'aosev_schema' ) );
function aosev_schema() {
	if ( ! is_singular( 'aosars_event' ) ) { return; }
	$id = get_the_ID(); if ( ! $id ) { return; }
	$g   = function ( $k ) use ( $id ) { return get_post_meta( $id, '_aosev_' . $k, true ); };
	$tz    = $g( 'tzone' ) ? (string) $g( 'tzone' ) : 'Africa/Nairobi'; // same legacy default as aosev_ts()
	$start = aosev_ts( $g( 'start' ), $tz );
	$end   = aosev_ts( $g( 'end' ), $tz );
	$mode  = $g( 'mode' ) ? $g( 'mode' ) : 'Online';
	$modes = array(
		'Online'    => 'https://schema.org/OnlineEventAttendanceMode',
		'In-person' => 'https://schema.org/OfflineEventAttendanceMode',
		'Hybrid'    => 'https://schema.org/MixedEventAttendanceMode',
	);
	$data  = array(
		'@context' => 'https://schema.org', '@type' => 'Event', 'name' => get_the_title( $id ),
		'startDate' => $start ? aosev_iso8601( $start, $tz ) : '',
		'endDate'   => $end ? aosev_iso8601( $end, $tz ) : '',
		'eventAttendanceMode' => isset( $modes[ $mode ] ) ? $modes[ $mode ] : $modes['Online'],
		'description' => wp_strip_all_tags( $g( 'lead' ) ? $g( 'lead' ) : $g( 'summary' ) ), 'url' => get_permalink( $id ),
	);
	if ( $g( 'venue' ) ) { $data['location'] = ( 'Online' === $mode ) ? array( '@type' => 'VirtualLocation', 'url' => $g( 'url' ) ? $g( 'url' ) : get_permalink( $id ) ) : array( '@type' => 'Place', 'name' => $g( 'venue' ), 'address' => $g( 'address' ) ); }
	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( array_filter( $data ) ) . '</script>' . "\n";
}
/* Epoch -> ISO 8601 carrying the event zone's own offset (e.g. 2026-01-15T14:00:00+03:00),
   so the structured data denotes the same instant the front-end countdown shows. */
function aosev_iso8601( $ts, $tz ) {
	try {
		$d = new DateTime( '@' . (int) $ts );
		$d->setTimezone( new DateTimeZone( $tz ? $tz : 'Africa/Nairobi' ) );
		return $d->format( 'c' );
	} catch ( \Throwable $e ) { return gmdate( 'c', (int) $ts ); }
}
/* Open Graph / Twitter tags so a shared permalink previews correctly. Skipped when a
   dedicated SEO plugin is active, to avoid duplicate tags. */
add_action( 'wp_head', aosev_guard( 'aosev_og' ), 5 );
function aosev_og() {
	if ( ! is_singular( 'aosars_event' ) ) { return; }
	if ( defined( 'WPSEO_VERSION' ) || class_exists( 'RankMath' ) || defined( 'SEOPRESS_VERSION' ) || function_exists( 'aioseo' ) || class_exists( 'The_SEO_Framework\\Load' ) ) { return; }
	$id = get_the_ID(); if ( ! $id ) { return; }
	$g    = function ( $k ) use ( $id ) { return get_post_meta( $id, '_aosev_' . $k, true ); };
	$desc = wp_strip_all_tags( $g( 'lead' ) ? $g( 'lead' ) : ( $g( 'summary' ) ? $g( 'summary' ) : get_the_excerpt( $id ) ) );
	$desc = wp_trim_words( $desc, 40, '…' );
	$img  = has_post_thumbnail( $id ) ? get_the_post_thumbnail_url( $id, 'large' ) : '';
	$tags = array(
		'og:type'        => 'article',
		'og:title'       => get_the_title( $id ),
		'og:description' => $desc,
		'og:url'         => get_permalink( $id ),
		'og:site_name'   => get_bloginfo( 'name' ),
	);
	if ( $img ) { $tags['og:image'] = $img; }
	$out = "\n";
	foreach ( $tags as $prop => $val ) {
		if ( '' === $val ) { continue; }
		$out .= '<meta property="' . esc_attr( $prop ) . '" content="' . esc_attr( $val ) . '">' . "\n";
	}
	$out .= '<meta name="twitter:card" content="' . ( $img ? 'summary_large_image' : 'summary' ) . '">' . "\n";
	echo $out; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- values escaped above.
}

/* Optional helper: hide the theme's own single-event title so it isn't shown twice
   (the branded hero already prints the title). Opt-in via Settings. Best-effort across
   common themes; the selector list is filterable. */
add_action( 'wp_head', aosev_guard( 'aosev_hide_title_css' ), 20 );
function aosev_hide_title_css() {
	if ( ! is_singular( 'aosars_event' ) ) { return; }
	$s = aosev_settings();
	if ( empty( $s['hide_title'] ) ) { return; }
	$selectors = apply_filters( 'aosev_hide_title_selectors', array(
		'.single-aosars_event .entry-title',
		'.single-aosars_event .entry-header .entry-title',
		'.single-aosars_event header.entry-header > .entry-title',
		'.single-aosars_event .page-title',
		'.single-aosars_event h1.wp-block-post-title',
		'.single-aosars_event .elementor-heading-title.entry-title',
	) );
	$selectors = array_filter( array_map( 'sanitize_text_field', (array) $selectors ) );
	if ( empty( $selectors ) ) { return; }
	echo "\n<style id=\"aosev-hide-title\">" . implode( ',', $selectors ) . '{position:absolute!important;width:1px;height:1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;}</style>' . "\n";
}
/* Make the single-event design render EDGE-TO-EDGE (like the prototype) instead of being
   squeezed into the theme's narrow content column. Opt-out via Settings. The app's inner
   .wrap still centres content at 1180px, so this just removes the theme's width cage. */
add_action( 'wp_head', aosev_guard( 'aosev_full_single_css' ), 21 );
function aosev_full_single_css() {
	if ( ! is_singular( 'aosars_event' ) ) { return; }
	$s = aosev_settings();
	if ( empty( $s['full_single'] ) ) { return; }
	echo "\n<style id=\"aosev-full-single\">"
		. '.single-aosars_event .aosev-app{width:100vw;max-width:100vw;margin-left:calc(50% - 50vw);margin-right:calc(50% - 50vw);border-radius:0;}'
		. '.single-aosars_event .aosev-app .wrap{max-width:1180px;margin:0 auto;}'
		. '@media(max-width:1180px){.single-aosars_event .aosev-app .wrap{padding-left:20px;padding-right:20px;}}'
		. '</style>' . "\n";
}

/* ---- 7a. ELEMENTOR DOCUMENT SETTINGS: enter event details INSIDE the Elementor editor ----
   Live-site forensics showed every event had start=0 and joinUrl="" although the entry
   fields exist — because events are created in Elementor, where WP meta boxes are not
   shown. So we register the essential fields as Elementor Document Settings (the ⚙
   panel, bottom-left of the editor) and sync them to the same _aosev_* meta on save. */
add_action( 'elementor/documents/register_controls', aosev_guard( 'aosev_el_doc_controls' ) );
function aosev_el_doc_controls( $document ) {
	if ( ! class_exists( '\Elementor\Controls_Manager' ) || ! is_object( $document ) || ! method_exists( $document, 'get_main_id' ) || ! method_exists( $document, 'start_controls_section' ) ) { return; }
	$id = (int) $document->get_main_id();
	if ( 'aosars_event' !== get_post_type( $id ) ) { return; }
	$g = function ( $k ) use ( $id ) { return (string) get_post_meta( $id, '_aosev_' . $k, true ); };
	$document->start_controls_section( 'aosev_doc', array(
		'label' => __( '📅 AOSARS Event details', 'aosars-events' ),
		'tab'   => \Elementor\Controls_Manager::TAB_SETTINGS,
	) );
	$document->add_control( 'aosev_doc_note', array(
		'type'            => \Elementor\Controls_Manager::RAW_HTML,
		'raw'             => __( 'These save to the event itself and drive the date, countdown, platform and Join button on the page. Click UPDATE after changing them.', 'aosars-events' ),
		'content_classes' => 'elementor-descriptor',
	) );
	$document->add_control( 'aosev_start', array( 'label' => __( 'Start date & time', 'aosars-events' ), 'type' => \Elementor\Controls_Manager::DATE_TIME, 'default' => str_replace( 'T', ' ', $g( 'start' ) ), 'picker_options' => array( 'allowInput' => true ) ) );
	$document->add_control( 'aosev_end', array( 'label' => __( 'End date & time', 'aosars-events' ), 'type' => \Elementor\Controls_Manager::DATE_TIME, 'default' => str_replace( 'T', ' ', $g( 'end' ) ), 'picker_options' => array( 'allowInput' => true ) ) );
	$document->add_control( 'aosev_tzone', array( 'label' => __( 'Timezone (times above are in this zone)', 'aosars-events' ), 'type' => \Elementor\Controls_Manager::SELECT, 'options' => aosev_timezones(), 'default' => $g( 'tzone' ) ? $g( 'tzone' ) : 'Africa/Nairobi' ) );
	$document->add_control( 'aosev_mode', array( 'label' => __( 'Format', 'aosars-events' ), 'type' => \Elementor\Controls_Manager::SELECT, 'options' => array( 'Online' => 'Online', 'In-person' => 'In-person', 'Hybrid' => 'Hybrid' ), 'default' => $g( 'mode' ) ? $g( 'mode' ) : 'Online' ) );
	$document->add_control( 'aosev_platform', array( 'label' => __( 'Online platform', 'aosars-events' ), 'type' => \Elementor\Controls_Manager::SELECT, 'options' => array( 'Google Meet' => 'Google Meet', 'Zoom' => 'Zoom', 'Microsoft Teams' => 'Microsoft Teams', 'Webex' => 'Webex', 'YouTube Live' => 'YouTube Live', 'Other' => 'Other' ), 'default' => $g( 'platform' ) ? $g( 'platform' ) : 'Google Meet' ) );
	$document->add_control( 'aosev_join_url', array( 'label' => __( 'Join link — paste the Meet/Zoom/Teams URL', 'aosars-events' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => $g( 'join_url' ), 'placeholder' => 'https://zoom.us/j/…' ) );
	$document->add_control( 'aosev_venue', array( 'label' => __( 'Venue (for in-person / hybrid)', 'aosars-events' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => $g( 'venue' ) ) );
	$document->add_control( 'aosev_fee', array( 'label' => __( 'Fee (e.g. KES 2,500 or Free)', 'aosars-events' ), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => $g( 'fee' ) ) );
	$document->end_controls_section();
}
/* The eight fields the Elementor panel edits (control id = 'aosev_' + key). */
function aosev_el_sync_keys() { return array( 'start', 'end', 'tzone', 'mode', 'platform', 'join_url', 'venue', 'fee' ); }
/* Validate/normalise one Elementor-supplied value for a given key; '' means rejected. */
function aosev_el_clean( $key, $v ) {
	if ( is_array( $v ) || null === $v ) { return ''; }
	$v = (string) $v;
	if ( '' === trim( $v ) ) { return ''; }
	if ( 'start' === $key || 'end' === $key ) { return aosev_clean_dt( $v ); } // validate typed (allowInput) dates too
	if ( 'tzone' === $key ) { return array_key_exists( $v, aosev_timezones() ) ? $v : ''; }
	if ( 'join_url' === $key ) { return esc_url_raw( $v ); }
	return sanitize_text_field( $v );
}
add_action( 'elementor/document/after_save', aosev_guard( 'aosev_el_doc_saved' ), 10, 2 );
function aosev_el_doc_saved( $document, $data = null ) {
	if ( ! is_object( $document ) || ! method_exists( $document, 'get_main_id' ) ) { return; }
	$id = (int) $document->get_main_id();
	if ( 'aosars_event' !== get_post_type( $id ) ) { return; }
	if ( ! current_user_can( 'edit_post', $id ) ) { return; } // defense-in-depth: hook-driven meta writes verify caps themselves
	// Prefer the FRESH settings payload of this very save; the document object's own
	// get_settings() can be stale (initialised before the save) — the live-site Doctor
	// report showed 'end' persisting while 'start' vanished, the stale-read signature.
	$fresh = array();
	$ps    = get_post_meta( $id, '_elementor_page_settings', true );
	if ( is_array( $ps ) ) { $fresh = $ps; }
	if ( is_array( $data ) && isset( $data['settings'] ) && is_array( $data['settings'] ) ) { $fresh = array_merge( $fresh, $data['settings'] ); }
	$receipt = array( 't' => time(), 'outcome' => 'elementor-sync', 'recovered' => false, 'stored' => array(), 'skipped' => array() );
	foreach ( aosev_el_sync_keys() as $key ) {
		$raw = isset( $fresh[ 'aosev_' . $key ] ) ? $fresh[ 'aosev_' . $key ] : ( method_exists( $document, 'get_settings' ) ? $document->get_settings( 'aosev_' . $key ) : null );
		$v   = aosev_el_clean( $key, $raw );
		if ( '' === $v ) { continue; } // non-destructive: blanks never wipe values entered in wp-admin
		update_post_meta( $id, '_aosev_' . $key, $v );
		$receipt['stored'][ $key ] = mb_substr( (string) $v, 0, 80 );
	}
	// Leave a receipt so the next wp-admin visit reports whether the Elementor save carried a date.
	aosev_receipt_store( $id, $receipt );
}
/* RENDER-TIME SAFETY NET: if a field is empty in our meta but Elementor stored a value
   for it in _elementor_page_settings, use it AND backfill the meta (self-heal). This
   guarantees panel-entered data reaches the page even if the save hook misfires. */
function aosev_elementor_fallback( $id, $key, $els ) {
	if ( ! is_array( $els ) || ! isset( $els[ 'aosev_' . $key ] ) ) { return ''; }
	$v = aosev_el_clean( $key, $els[ 'aosev_' . $key ] );
	if ( '' === $v ) { return ''; }
	// Self-heal (backfill _aosev_* from the Elementor panel value) only when an editor is
	// looking — an anonymous front-end GET must never write to the database. Anonymous
	// visitors still SEE the resolved value (returned below); the editor's own visit heals it.
	if ( is_user_logged_in() && current_user_can( 'edit_post', $id ) ) {
		update_post_meta( $id, '_aosev_' . $key, $v );
	}
	return $v;
}

/* ---- 7. ELEMENTOR (optional; loaded only when active) ---- */
add_action( 'elementor/elements/categories_registered', aosev_guard( 'aosev_el_cat' ) );
function aosev_el_cat( $mgr ) { if ( is_object( $mgr ) && method_exists( $mgr, 'add_category' ) ) { $mgr->add_category( 'aosars', array( 'title' => __( 'AOSARS', 'aosars-events' ), 'icon' => 'eicon-calendar' ) ); } }
add_action( 'elementor/widgets/register', aosev_guard( 'aosev_el_widgets' ) );
add_action( 'elementor/widgets/widgets_registered', aosev_guard( 'aosev_el_widgets' ) );
function aosev_el_widgets( $wm = null ) {
	if ( ! class_exists( '\Elementor\Widget_Base' ) ) { return; }
	if ( ! class_exists( 'AOSEV_El_Portal' ) ) { aosev_define_widgets(); }
	$wm = $wm ? $wm : ( class_exists( '\Elementor\Plugin' ) ? \Elementor\Plugin::instance()->widgets_manager : null );
	if ( ! $wm ) { return; }
	foreach ( array( 'AOSEV_El_Portal', 'AOSEV_El_Single', 'AOSEV_El_Home' ) as $c ) {
		try { if ( method_exists( $wm, 'register' ) ) { $wm->register( new $c() ); } elseif ( method_exists( $wm, 'register_widget_type' ) ) { $wm->register_widget_type( new $c() ); } }
		catch ( \Throwable $e ) { if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) { error_log( '[AOSARS Events] elementor: ' . $e->getMessage() ); } }
	}
}
function aosev_define_widgets() {
	abstract class AOSEV_El_Base extends \Elementor\Widget_Base {
		public function get_categories() { return array( 'aosars' ); }
		public function get_icon() { return 'eicon-calendar'; }
	}
	class AOSEV_El_Portal extends AOSEV_El_Base {
		public function get_name() { return 'aosev_portal'; }
		public function get_title() { return __( 'AOSARS Events Portal', 'aosars-events' ); }
		protected function render() { echo aosev_sc_portal( array() ); }
	}
	class AOSEV_El_Single extends AOSEV_El_Base {
		public function get_name() { return 'aosev_single'; }
		public function get_title() { return __( 'AOSARS Single Event', 'aosars-events' ); }
		protected function register_controls() {
			$this->start_controls_section( 'sec', array( 'label' => __( 'Single Event', 'aosars-events' ) ) );
			$this->add_control( 'id', array( 'label' => __( 'Event ID (blank = current)', 'aosars-events' ), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => '' ) );
			$this->end_controls_section();
		}
		protected function render() { $s = $this->get_settings_for_display(); echo aosev_sc_single( array( 'id' => $s['id'] ) ); }
	}
	class AOSEV_El_Home extends AOSEV_El_Base {
		public function get_name() { return 'aosev_home'; }
		public function get_title() { return __( 'AOSARS Events Home', 'aosars-events' ); }
		protected function render() { echo aosev_sc_home( array() ); }
	}
}

/* ---- 8. SETTINGS + SITE HEALTH ---- */
if ( is_admin() ) {
	add_action( 'admin_menu', aosev_guard( 'aosev_settings_menu' ) );
	add_action( 'admin_init', aosev_guard( 'aosev_settings_register' ) );
}
function aosev_settings_menu() { add_submenu_page( 'edit.php?post_type=aosars_event', __( 'Events Settings', 'aosars-events' ), __( 'Settings', 'aosars-events' ), 'manage_options', 'aosev-settings', 'aosev_settings_page' ); }
function aosev_settings_register() { register_setting( 'aosev_settings_group', AOSEV_OPTION, 'aosev_settings_sanitize' ); }
function aosev_settings_sanitize( $in ) {
	$in = is_array( $in ) ? $in : array();
	return array(
		'currency'       => isset( $in['currency'] ) ? sanitize_text_field( $in['currency'] ) : 'KES',
		'all_url'        => isset( $in['all_url'] ) ? esc_url_raw( $in['all_url'] ) : '',
		'auto_append'    => empty( $in['auto_append'] ) ? 0 : 1,
		'hide_title'     => empty( $in['hide_title'] ) ? 0 : 1,
		'full_single'    => empty( $in['full_single'] ) ? 0 : 1,
		'own_template'   => empty( $in['own_template'] ) ? 0 : 1,
	);
}
function aosev_settings_page() {
	$s = aosev_settings();
	echo '<div class="wrap"><h1>' . esc_html__( 'AOSARS Events Settings', 'aosars-events' ) . '</h1><form method="post" action="options.php">';
	settings_fields( 'aosev_settings_group' );
	echo '<table class="form-table"><tbody>';
	echo '<tr><th>' . esc_html__( 'Default currency', 'aosars-events' ) . '</th><td><input type="text" name="' . esc_attr( AOSEV_OPTION ) . '[currency]" value="' . esc_attr( $s['currency'] ) . '" class="regular-text"></td></tr>';
	echo '<tr><th>' . esc_html__( 'View all events URL', 'aosars-events' ) . '</th><td><input type="url" name="' . esc_attr( AOSEV_OPTION ) . '[all_url]" value="' . esc_attr( $s['all_url'] ) . '" class="regular-text"></td></tr>';
	echo '<tr><th>' . esc_html__( 'Auto-show event layout', 'aosars-events' ) . '</th><td><label><input type="checkbox" name="' . esc_attr( AOSEV_OPTION ) . '[auto_append]" value="1" ' . checked( ! empty( $s['auto_append'] ), true, false ) . '> ' . esc_html__( 'Append the AOSARS chrome (hero, countdown, facts, related) below single event pages. Turn off to design events entirely in Elementor or the block editor.', 'aosars-events' ) . '</label></td></tr>';
	echo '<tr><th>' . esc_html__( 'Hide theme title on events', 'aosars-events' ) . '</th><td><label><input type="checkbox" name="' . esc_attr( AOSEV_OPTION ) . '[hide_title]" value="1" ' . checked( ! empty( $s['hide_title'] ), true, false ) . '> ' . esc_html__( "Hide the theme's own page title on single event pages, so it isn't shown twice (the branded hero already shows the title). Best-effort across common themes.", 'aosars-events' ) . '</label></td></tr>';
	echo '<tr><th>' . esc_html__( 'Full-width single pages', 'aosars-events' ) . '</th><td><label><input type="checkbox" name="' . esc_attr( AOSEV_OPTION ) . '[full_single]" value="1" ' . checked( ! empty( $s['full_single'] ), true, false ) . '> ' . esc_html__( 'Render single event pages edge-to-edge (like the prototype) instead of inside the theme\'s narrow content column. Turn off if your theme already provides a full-width template.', 'aosars-events' ) . '</label></td></tr>';
	echo '<tr><th>' . esc_html__( 'Use the AOSARS single-event page', 'aosars-events' ) . '</th><td><label><input type="checkbox" name="' . esc_attr( AOSEV_OPTION ) . '[own_template]" value="1" ' . checked( ! empty( $s['own_template'] ), true, false ) . '> ' . esc_html__( 'Render the whole event page with the AOSARS design (site header + design + site footer) and bypass the theme\'s own title, featured image and content, so nothing is shown twice. Recommended. Turn off to keep the theme\'s single template.', 'aosars-events' ) . '</label></td></tr>';
	echo '</tbody></table>';
	submit_button();
	echo '</form><h2>' . esc_html__( 'How to place events', 'aosars-events' ) . '</h2>';
	echo '<p><code>[aosars_events_portal]</code> ' . esc_html__( 'or the Elementor "AOSARS Events Portal" widget shows the full portal.', 'aosars-events' ) . '</p>';
	echo '<p><code>[aosars_event id="123"]</code> ' . esc_html__( 'shows one event. Each event also has its own page.', 'aosars-events' ) . '</p>';
	echo '<p><code>[aosars_events_home]</code> ' . esc_html__( 'or the Elementor "AOSARS Events Home" widget shows the homepage component (featured next event + carousel).', 'aosars-events' ) . '</p>';
	echo '<p>' . esc_html__( 'Events are edited like posts and can be opened with Edit with Elementor.', 'aosars-events' ) . '</p>';
	// ---- Diagnostics: answers "which code is running and what data does it see?" ----
	echo '<hr><h2>' . esc_html__( 'Diagnostics', 'aosars-events' ) . '</h2>';
	echo '<p><code>' . esc_html( 'AOSARS Events v' . AOSEV_VER . ' · wp-content/plugins/' . basename( dirname( __FILE__ ) ) . ' · PHP ' . PHP_VERSION ) . '</code></p>';
	$probe = aosev_ts( '2026-01-15T14:00', 'Africa/Nairobi' );
	$ok    = ( $probe === 1768474800 ); // 2026-01-15 11:00 UTC == 14:00 EAT
	echo '<p>' . esc_html__( 'Time check — “15 Jan 2026, 14:00” entered in EAT converts to epoch ', 'aosars-events' ) . '<code>' . esc_html( (string) $probe ) . '</code>: '
		. ( $ok ? '<strong style="color:#00a32a">' . esc_html__( 'CORRECT (displays 14:00 EAT on the page)', 'aosars-events' ) . '</strong>' : '<strong style="color:#b32d2e">' . esc_html__( 'WRONG — report this number', 'aosars-events' ) . '</strong>' ) . '</p>';
	if ( function_exists( 'get_posts' ) ) {
		$ids = get_posts( array( 'post_type' => 'aosars_event', 'post_status' => 'publish', 'numberposts' => 100, 'fields' => 'ids' ) );
		$missing = array();
		foreach ( (array) $ids as $eid ) {
			if ( '' === trim( (string) get_post_meta( $eid, '_aosev_start', true ) ) ) { $missing[] = get_the_title( $eid ); }
		}
		echo '<p>' . esc_html( sprintf( __( 'Published events: %1$d · without a start date: %2$d', 'aosars-events' ), count( (array) $ids ), count( $missing ) ) )
			. ( $missing ? ' — <em>' . esc_html( implode( ', ', array_slice( $missing, 0, 10 ) ) ) . '</em>' : '' ) . '</p>';
	}
	// ---- SAVE FLIGHT RECORDER: the last save receipt of the 10 most recent events, as one
	// copy-paste report. For each save it shows WHICH aosev_* fields the request contained,
	// the RAW start value received, what was stored/rejected, the request type and browser —
	// enough to pinpoint a failing entry flow without access to the site. ----
	echo '<h3>' . esc_html__( 'Save flight recorder (copy & send this when a date does not stick)', 'aosars-events' ) . '</h3>';
	$L   = array( 'AOSARS SAVE FLIGHT RECORDER — v' . AOSEV_VER . ' — ' . gmdate( 'Y-m-d H:i' ) . ' UTC' );
	$evs = function_exists( 'get_posts' ) ? get_posts( array( 'post_type' => 'aosars_event', 'post_status' => 'any', 'numberposts' => 10 ) ) : array();
	foreach ( (array) $evs as $ev ) {
		$rec = get_post_meta( $ev->ID, '_aosev_last_save', true );
		$cur = (string) get_post_meta( $ev->ID, '_aosev_start', true );
		$L[] = sprintf( '#%d "%s" [%s] | stored start now: %s', $ev->ID, $ev->post_title, $ev->post_status, '' !== $cur ? $cur : '(EMPTY)' );
		if ( is_array( $rec ) && ! empty( $rec['outcome'] ) ) {
			$L[] = sprintf(
				'     last save %s | outcome=%s%s | via=%s | fields in request: %s | raw start received: %s | stored: %s | rejected: %s',
				! empty( $rec['t'] ) ? gmdate( 'Y-m-d H:i', (int) $rec['t'] ) . ' UTC' : '?',
				$rec['outcome'], ! empty( $rec['recovered'] ) ? '+RECOVERED-VIA-MIRROR' : '',
				isset( $rec['via'] ) ? $rec['via'] : '?',
				! empty( $rec['seen'] ) ? implode( ',', (array) $rec['seen'] ) : '(NONE — request carried no aosev fields)',
				isset( $rec['raw_start'] ) && null !== $rec['raw_start'] ? '"' . $rec['raw_start'] . '"' : '(not in request)',
				! empty( $rec['stored'] ) ? implode( ',', array_keys( (array) $rec['stored'] ) ) : '(nothing)',
				! empty( $rec['skipped'] ) ? wp_json_encode( $rec['skipped'] ) : '(nothing)'
			);
			if ( ! empty( $rec['ua'] ) ) { $L[] = '     browser: ' . $rec['ua']; }
		} else {
			$L[] = '     last save: (no receipt recorded — every save of this event predates v6.8.0, or none has our fields)';
		}
	}
	echo '<textarea readonly style="width:100%;height:280px;font-family:ui-monospace,Menlo,Consolas,monospace;font-size:12px" onclick="this.select()">' . esc_textarea( implode( "\n", $L ) ) . '</textarea>';
	echo '</div>';
}
add_filter( 'site_status_tests', aosev_guard( 'aosev_sh_register' ) );
function aosev_sh_register( $t ) { $t['direct']['aosev_events'] = array( 'label' => __( 'AOSARS Events', 'aosars-events' ), 'test' => 'aosev_sh' ); return $t; }
function aosev_sh() {
	$i = array();
	if ( ! post_type_exists( 'aosars_event' ) ) { $i[] = __( 'the event post type is not registered', 'aosars-events' ); }
	foreach ( array( 'aosars_events_portal', 'aosars_event', 'aosars_events_home' ) as $sc ) { if ( ! shortcode_exists( $sc ) ) { $i[] = sprintf( __( 'shortcode [%s] missing', 'aosars-events' ), $sc ); } }
	$copies = 0;
	foreach ( (array) get_option( 'active_plugins', array() ) as $ap ) { if ( 'aosars-events.php' === basename( (string) $ap ) ) { $copies++; } }
	if ( $copies > 1 ) { $i[] = sprintf( __( '%d copies of the plugin are active — keep one, delete the rest', 'aosars-events' ), $copies ); }
	$ok = empty( $i );
	$where = sprintf( __( 'Running v%1$s from wp-content/plugins/%2$s.', 'aosars-events' ), AOSEV_VER, basename( dirname( __FILE__ ) ) );
	return array(
		'label' => $ok ? __( 'AOSARS Events is ready', 'aosars-events' ) : __( 'AOSARS Events needs attention', 'aosars-events' ),
		'status' => $ok ? 'good' : 'recommended', 'badge' => array( 'label' => __( 'AOSARS', 'aosars-events' ), 'color' => 'blue' ),
		'description' => '<p>' . esc_html( ( $ok ? __( 'The event type and shortcodes are active.', 'aosars-events' ) : implode( '; ', array_map( 'sanitize_text_field', $i ) ) ) . ' ' . $where ) . '</p>', 'test' => 'aosev_events',
	);
}
