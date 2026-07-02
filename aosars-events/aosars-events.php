<?php
/**
 * Plugin Name:       AOSARS Events
 * Description:       The full AOSARS events experience, faithful to the agreed mockup: portal with calendar widget, ticker, next-event counter, animated countdowns, timezone bar, grid/list, category and day filters, and a rich single-event view with add-to-calendar. Post-like CPT that is Elementor-editable, with native Elementor widgets. One guarded file, fail-safe by design; Elementor optional; no database table, no REST.
 * Version:           4.1.1
 * Author:            Karanja Maina
 * License:           GPL-2.0-or-later
 * Text Domain:       aosars-events
 * Requires at least: 7.0
 * Requires PHP:      7.4
 * Tested up to:      7.0
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }
if ( defined( 'AOSEV_VER' ) ) { return; }
define( 'AOSEV_VER', '4.1.1' );
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
  var MONTHS=["January","February","March","April","May","June","July","August","September","October","November","December"];
  var MON3=["Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"];
  var WD=["Mo","Tu","We","Th","Fr","Sa","Su"];

  var tz="Africa/Nairobi", tzLabel="EAT", gridMode="grid";
  var state=(window.AOSEV_DATA&&window.AOSEV_DATA.state&&window.AOSEV_DATA.state.view==="single")?{view:"single",id:window.AOSEV_DATA.state.id}:{view:"portal",id:null};
  var _t=new Date(), calY=_t.getFullYear(), calM=_t.getMonth(), calMode="month", selDay=null, selCat=null;

  function pad(n){return (n<10?"0":"")+n;}
  function timeOnly(ms){return new Date(ms).toLocaleTimeString("en-GB",{hour:"2-digit",minute:"2-digit",hour12:false,timeZone:tz})+" "+tzLabel;}
  function dateBadge(ms){var d=new Date(ms),td=new Date(now);
    var ds=d.toLocaleDateString("en-GB",{day:"numeric",month:"short",timeZone:tz});
    var ts=td.toLocaleDateString("en-GB",{day:"numeric",month:"short",timeZone:tz});
    if(ds===ts)return '<span class="d">Today</span>';var p=ds.split(" ");return p[1].toUpperCase()+'<span class="d">'+p[0]+'</span>';}
  function fullDate(e){var d=new Date(e.start);
    var ds=d.toLocaleDateString("en-GB",{weekday:"short",day:"numeric",month:"long",timeZone:tz});
    var ts=d.toLocaleTimeString("en-GB",{hour:"2-digit",minute:"2-digit",hour12:false,timeZone:tz});
    var te=new Date(e.start+e.durH*H).toLocaleTimeString("en-GB",{hour:"2-digit",minute:"2-digit",hour12:false,timeZone:tz});
    return ds+" \u00b7 "+ts+"\u2013"+te+" "+tzLabel;}
  function parts(ms){var s=Math.max(0,Math.floor((ms-Date.now())/1000));return{d:Math.floor(s/86400),h:Math.floor(s/3600)%24,m:Math.floor(s/60)%60,s:s%60};}
  function mini(ms){var p=parts(ms);if(p.d>0)return "Starts in "+p.d+"d "+p.h+"h";if(p.h>0)return "Starts in "+p.h+"h "+p.m+"m";if(p.m>0)return "Starts in "+pad(p.m)+":"+pad(p.s);return (ms-Date.now()>0)?"Starts in "+p.s+"s":"Happening now";}
  function clockHTML(pfx,big){
    var u=[["D","days"],["H","hrs"],["M","min"],["S","sec"]];
    return '<div class="lvclk'+(big?' lg':'')+'">'+u.map(function(x){
      return '<div class="lvt"><span class="bar" id="'+pfx+x[0]+'b"></span><b id="'+pfx+x[0]+'">00</b><i>'+x[1]+'</i></div>';}).join("")+'</div>';}
  function updateClock(pfx,ms){var p=parts(ms),v={D:p.d,H:p.h,M:p.m,S:p.s},fr={D:Math.min(p.d/30,1),H:p.h/24,M:p.m/60,S:p.s/60};
    ["D","H","M","S"].forEach(function(k){set(pfx+k,pad(v[k]));var b=document.getElementById(pfx+k+"b");if(b)b.style.height=(fr[k]*100).toFixed(1)+"%";});}
  function utc(ms){return new Date(ms).toISOString().replace(/[-:]/g,"").split(".")[0]+"Z";}
  function soonest(){return EVENTS.slice().sort(function(a,b){return a.start-b.start;});}
  function dayKey(ms){var d=new Date(ms);return d.getFullYear()+"-"+pad(d.getMonth()+1)+"-"+pad(d.getDate());}
  function cellKey(y,m,d){return y+"-"+pad(m+1)+"-"+pad(d);}
  function eventsByDay(){var x={};EVENTS.forEach(function(e){var k=dayKey(e.start);(x[k]=x[k]||[]).push(e);});return x;}
  function eventsByMonth(){var x={};EVENTS.forEach(function(e){var d=new Date(e.start);var k=d.getFullYear()+"-"+d.getMonth();x[k]=(x[k]||0)+1;});return x;}
  function categories(){var x={};EVENTS.forEach(function(e){x[e.cat]=(x[e.cat]||0)+1;});return Object.keys(x).map(function(k){return [k,x[k]];});}
  function visibleEvents(){return soonest().filter(function(e){
    if(selCat&&e.cat!==selCat)return false;
    if(selDay&&dayKey(e.start)!==selDay)return false; return true;});}
  function filterLabel(){
    if(selDay){var d=new Date(selDay+"T12:00:00");return "Events on "+d.toLocaleDateString("en-GB",{weekday:"short",day:"numeric",month:"short"});}
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
    var nxDate=new Date(nx.start).toLocaleDateString("en-GB",{weekday:"short",day:"numeric",month:"short",timeZone:tz});
    var nextup='<div class="nextup">'+
      '<div class="nu-top"><span class="nu-live"><i></i> Next event</span><span class="nu-mode">'+nx.mode+'</span></div>'+
      '<h4 data-act="view-event" data-id="'+nx.id+'">'+nx.t+'</h4>'+
      '<div class="nu-date">'+nxDate+' \u00b7 '+timeOnly(nx.start)+'</div>'+
      clockHTML("nu")+
      '<div class="nu-foot"><span class="when" id="nuWhen">'+mini(nx.start)+'</span><a data-act="view-event" data-id="'+nx.id+'">View details &#8594;</a></div></div>';
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
      '<div class="card__body"><h3>'+e.t+'</h3><p class="cdesc">'+e.lead+'</p>'+
      '<div class="row"><span class="g">&#128337;</span><span class="time">'+timeOnly(e.start)+'</span></div>'+
      '<span class="cdmini'+((e.start-now)<D?' soon':'')+'"><i></i><span data-cd="'+e.id+'">'+mini(e.start)+'</span></span>'+
      '<div class="row"><span class="g">&#128249;</span>'+e.venue+(full?' &middot; <span class="soldout">Sold out</span>':'')+'</div>'+
      '<div class="foot"><span class="pill">'+e.cat+'</span><span class="more">'+(full?'Waitlist':'View event')+' <i>&#8594;</i></span></div></div></article>';
  }

  function portalHTML(){
    var todays=EVENTS.filter(function(e){return e.today;});
    var ticker=todays.length?'<aside class="ticker"><span class="ticker__tag">TODAY</span><div class="ticker__item"><strong>'+todays[0].t+'</strong> <span>&middot; '+timeOnly(todays[0].start)+' &middot; '+todays[0].venue+'</span></div><span class="ticker__x" data-act="dismiss">&times;</span></aside>':'';
    var vis=visibleEvents();
    var cards=vis.length?vis.map(cardHTML).join(""):'<div class="empty-state">No events match this filter. <b data-act="clear-filter" style="color:var(--indigo);cursor:pointer">Clear filter</b></div>';
    var filtered=selDay||selCat;
    var fbar='<div class="fbar"><div class="lab">'+filterLabel()+'<span>'+vis.length+' event'+(vis.length===1?'':'s')+'</span></div>'+(filtered?'<button class="clear" data-act="clear-filter">&times; Clear filter</button>':'')+'</div>';
    return ''+ticker+
      '<div class="head"><div><h1 tabindex="-1" id="focusH">Upcoming events</h1><div class="stats">'+EVENTS.length+' upcoming \u00b7 <b>'+todays.length+' today</b></div></div>'+
        '<div class="view" role="group" aria-label="Layout"><button data-act="grid" class="'+(gridMode==="grid"?"on":"")+'" title="Grid">&#9707;</button><button data-act="list" class="'+(gridMode==="list"?"on":"")+'" title="List">&#9776;</button></div></div>'+
      tzbarHTML()+
      '<div class="portalwrap"><div class="pmain">'+fbar+'<div class="egrid '+(gridMode==="list"?"is-list":"")+'">'+cards+'</div>'+
        (filtered?'':'<nav class="pager"><span class="pg">&#8249;</span><span class="pg on">1</span><span class="pg">2</span><span class="pg">&#8250;</span></nav>')+
      '</div>'+sidebarHTML()+'</div>';
  }

  /* ---------- SINGLE ---------- */
  function singleHTML(id){
    var e=byId[id], full=e.cap&&e.taken>=e.cap;
    var others=soonest().filter(function(x){return x.id!==id;}).slice(0,3);
    var meet=MEETS[e.id]||"aos-meet-room";
    var venueBlock='<p class="meet-sub">This session runs live online on Google Meet. The joining link is posted right here, so you can save it now.</p>'+
      '<div class="meet-link"><span class="meet-ic">&#128249;</span><span class="meet-url">meet.google.com/'+meet+'</span><button class="meet-copy" data-act="copy-meet" data-link="https://meet.google.com/'+meet+'">Copy link</button></div>'+
      '<div class="meet-actions"><a class="btn primary" href="https://meet.google.com/'+meet+'" target="_blank" rel="noopener">&#128249; Join the meeting</a></div>'+
      '<p class="meet-note">The room opens 10 minutes before the start time. Add the event to your calendar so the link is always to hand.</p>';
    var rel=others.map(function(o){return cardHTML(o);}).join("");
    return ''+
      '<div class="topline"><button class="back" data-act="all-events">&#8592; All events</button><div class="crumb"><a data-act="all-events">Events</a> &nbsp;&rsaquo;&nbsp; <b>'+e.t+'</b></div></div>'+
      '<header class="shead"><div class="ab-post-meta-block"><span class="ab-eyebrow">'+e.cat+'</span>'+
      '<div class="ab-post-meta"><span>'+e.mode+'</span><span class="ab-dot"></span>'+
      '<span>'+new Date(e.start).toLocaleDateString("en-GB",{weekday:"short",day:"numeric",month:"long",timeZone:tz})+'</span><span class="ab-dot"></span>'+
      '<span>'+e.durH+' hour'+(e.durH>1?'s':'')+'</span></div></div>'+
      '<h1 tabindex="-1" id="focusH">'+e.t+'</h1></header>'+
      '<div class="bframe"><img class="bphoto" src="'+e.img+'" alt="" loading="lazy" onerror="this.style.display=\'none\'"></div>'+
      '<section class="cdband"><div class="lbl"><i></i> Starts in</div><div class="when" id="sWhen">'+fullDate(e)+'</div>'+clockHTML("sh",true)+'</section>'+
      tzbarHTML()+
      '<div class="layout"><div class="main">'+
        '<div class="sec"><span class="sec-eyebrow">Overview</span><h2>About this event</h2><p>'+e.lead+'</p><p>You will leave with templates and a recording, and a clear next step you can act on the same week.</p></div>'+
        '<div class="sec"><span class="sec-eyebrow">How to join</span><h2>Join on Google Meet</h2>'+venueBlock+'</div>'+
        '<div class="sec"><span class="sec-eyebrow">What you\'ll learn</span><h2>What you\'ll cover</h2><ul class="checks">'+e.covers.map(function(c){return '<li><span class="ck">&#10003;</span> '+c+'</li>';}).join("")+'</ul></div>'+
        '<div class="sec"><span class="sec-eyebrow">Run of show</span><h2>Agenda</h2><div class="agenda">'+e.agenda.map(function(r){return '<div class="arow"><span class="at">'+r[0]+'</span><span>'+r[1]+'</span></div>';}).join("")+'</div></div>'+
        '<div class="sec"><span class="sec-eyebrow">Your facilitator</span><h2>Led by the AOSARS faculty</h2><div class="facil"><div class="facil-av">AOSARS</div><div class="facil-b"><div class="facil-n">AOSARS Research Faculty</div><div class="facil-d">Sessions are led by experienced AOSARS researcher-trainers who have guided postgraduate scholars across seven African countries. You leave with practical guidance you can apply to your own work the same week.</div></div></div></div>'+
      '</div>'+
      '<aside class="sticky">'+
        '<div class="panel"><h3>Event details</h3><div class="facts">'+
          '<div class="fact"><span class="fi">&#128197;</span><div><div class="fk">Date &amp; time</div><div class="fv" id="sFacts">'+fullDate(e)+'</div></div></div>'+
          '<div class="fact"><span class="fi">&#128421;</span><div><div class="fk">Format</div><div class="fv">'+e.mode+'</div></div></div>'+
          '<div class="fact"><span class="fi">&#128249;</span><div><div class="fk">Platform</div><div class="fv">'+e.venue+'</div></div></div>'+
          '<div class="fact"><span class="fi">&#127891;</span><div><div class="fk">Organiser</div><div class="fv">AOSARS</div></div></div>'+
          '<div class="fact"><span class="fi">&#128176;</span><div><div class="fk">Fee</div><div class="fv">'+e.fee+'</div></div></div></div>'+
        '<div class="calbtns"><a class="btn primary" data-act="ics" data-id="'+e.id+'">&#11015; Add to calendar (.ics)</a><a class="btn" id="gcal" target="_blank" rel="noopener">&#128197; Google Calendar</a></div>'+
        '<p class="attend-note">'+(e.fee==="Free"?"Free to attend.":e.fee+".")+' The Google Meet link is posted on this page under &ldquo;How to join&rdquo;.</p></div>'+
        '<div class="moreevents"><div class="moreevents-h">More events <a data-act="all-events">View all &#8594;</a></div>'+rel+'</div></aside></div>';
  }

  /* ---------- router + ticking ---------- */
  function renderApp(){
    document.getElementById("AOSEV_ROOT").innerHTML = state.view==="portal" ? portalHTML() : singleHTML(state.id);
    if(state.view==="single"){ buildGcal(byId[state.id]); }
    tick();
    var h=document.getElementById("focusH"); if(h){window.scrollTo(0,0); try{h.focus();}catch(x){}}
  }
  function buildGcal(e){var a=document.getElementById("gcal"); if(!a)return;
    a.href="https://www.google.com/calendar/render?action=TEMPLATE&text="+encodeURIComponent(e.t)+"&dates="+utc(e.start)+"/"+utc(e.start+e.durH*H)+"&details="+encodeURIComponent("Hosted by AOSARS")+"&location="+encodeURIComponent(e.venue);}
  function set(id,v){var el=document.getElementById(id);if(el)el.textContent=v;}
  function tick(){
    if(state.view==="portal"){
      var nx=soonest()[0];
      updateClock("nu",nx.start);
      set("nuWhen",mini(nx.start));
      EVENTS.forEach(function(e){var c=document.querySelector('[data-cd="'+e.id+'"]');if(c)c.textContent=mini(e.start);});
    }else{
      var e=byId[state.id];
      updateClock("sh",e.start);
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
    if(a==="view-event"){go("single",parseInt(el.dataset.id,10));}
    else if(a==="all-events"){go("portal");}
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
    else if(a==="clear-filter"){selDay=null;selCat=null;renderApp();}
    else if(a==="sub-all"){ev.preventDefault();downloadAllICS();}
    else if(a==="copy-meet"){ev.preventDefault();var lk=el.dataset.link;if(navigator.clipboard&&navigator.clipboard.writeText){navigator.clipboard.writeText(lk);}el.textContent="Copied";setTimeout(function(){el.textContent="Copy link";},1600);}
  });
  document.addEventListener("change",function(ev){
    if(ev.target.id==="catSelect"&&ev.target.closest(".aosev-app")){selCat=ev.target.value||null;renderApp();}
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


function aosev_guard( $cb ) {
	return function ( ...$args ) use ( $cb ) {
		try { return call_user_func_array( $cb, $args ); }
		catch ( \Throwable $e ) {
			if ( defined( 'WP_DEBUG' ) && WP_DEBUG ) { error_log( '[AOSARS Events] skipped: ' . $e->getMessage() . ' @ ' . $e->getFile() . ':' . $e->getLine() ); }
			return isset( $args[0] ) ? $args[0] : null;
		}
	};
}
function aosev_settings() {
	$d = array( 'currency' => 'KES', 'all_url' => 'https://aosars.com/events/', 'auto_append' => 1 );
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
		'supports' => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'author', 'comments', 'trackbacks', 'revisions' ),
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
		$s = get_post_meta( $post_id, '_aosev_start', true );
		echo $s ? esc_html( date_i18n( 'D, j M Y H:i', strtotime( $s ) ) ) : '&mdash;';
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
	$q->set( 'meta_key', '_aosev_start' );
	$q->set( 'orderby', 'meta_value' );
}
register_activation_hook( __FILE__, 'aosev_activate' );
function aosev_activate() {
	if ( version_compare( PHP_VERSION, '7.4', '<' ) ) {
		deactivate_plugins( plugin_basename( __FILE__ ) );
		wp_die( esc_html__( 'AOSARS Events requires PHP 7.4 or newer.', 'aosars-events' ), esc_html__( 'Plugin activation blocked', 'aosars-events' ), array( 'back_link' => true ) );
	}
	try {
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

/* ---- 2. DETAILS META BOX ---- */
add_action( 'add_meta_boxes', aosev_guard( 'aosev_add_box' ) );
function aosev_add_box() { add_meta_box( 'aosev_details', __( 'Event details', 'aosars-events' ), 'aosev_box_html', 'aosars_event', 'normal', 'high' ); }
function aosev_fields() {
	return array(
		'start'    => array( 'datetime-local', __( 'Start date and time', 'aosars-events' ) ),
		'end'      => array( 'datetime-local', __( 'End date and time', 'aosars-events' ) ),
		'mode'     => array( 'select', __( 'Mode', 'aosars-events' ), array( 'Online', 'In-person', 'Hybrid' ) ),
		'icon'     => array( 'text', __( 'Icon emoji (optional, e.g. a magnifier)', 'aosars-events' ) ),
		'venue'    => array( 'text', __( 'Venue / platform (e.g. Google Meet)', 'aosars-events' ) ),
		'address'  => array( 'text', __( 'Address / joining note', 'aosars-events' ) ),
		'code'     => array( 'text', __( 'Meeting code (e.g. abc-defg-hij)', 'aosars-events' ) ),
		'fee'      => array( 'text', __( 'Fee (e.g. KES 2,500 or Free)', 'aosars-events' ) ),
		'capacity' => array( 'number', __( 'Capacity (blank = unlimited)', 'aosars-events' ) ),
		'taken'    => array( 'number', __( 'Spots taken', 'aosars-events' ) ),
		'url'      => array( 'url', __( 'Registration link', 'aosars-events' ) ),
		'summary'  => array( 'textarea', __( 'Card summary', 'aosars-events' ) ),
		'lead'     => array( 'textarea', __( 'Lead paragraph', 'aosars-events' ) ),
		'covers'   => array( 'lines', __( "What you'll cover (one point per line)", 'aosars-events' ) ),
		'agenda'   => array( 'lines', __( 'Agenda (one per line, e.g. 14:00 Welcome)', 'aosars-events' ) ),
	);
}
function aosev_box_html( $post ) {
	wp_nonce_field( 'aosev_save', 'aosev_nonce' );
	echo '<style>.aosev-mb label{display:block;font-weight:600;margin:12px 0 4px}.aosev-mb input,.aosev-mb select,.aosev-mb textarea{width:100%;max-width:560px}.aosev-mb textarea{min-height:88px}</style><div class="aosev-mb">';
	foreach ( aosev_fields() as $k => $def ) {
		$t = $def[0]; $v = get_post_meta( $post->ID, '_aosev_' . $k, true );
		echo '<label for="aosev_' . esc_attr( $k ) . '">' . esc_html( $def[1] ) . '</label>';
		if ( 'select' === $t ) {
			echo '<select id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '"><option value="">' . esc_html__( 'Select', 'aosars-events' ) . '</option>';
			foreach ( $def[2] as $o ) { echo '<option value="' . esc_attr( $o ) . '" ' . selected( $v, $o, false ) . '>' . esc_html( $o ) . '</option>'; }
			echo '</select>';
		} elseif ( 'textarea' === $t || 'lines' === $t ) {
			echo '<textarea id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '">' . esc_textarea( $v ) . '</textarea>';
		} else {
			echo '<input type="' . esc_attr( $t ) . '" id="aosev_' . esc_attr( $k ) . '" name="aosev_' . esc_attr( $k ) . '" value="' . esc_attr( $v ) . '">';
		}
	}
	echo '</div>';
}
add_action( 'save_post_aosars_event', aosev_guard( 'aosev_save' ), 10, 2 );
function aosev_save( $post_id, $post ) {
	if ( ! isset( $_POST['aosev_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['aosev_nonce'] ) ), 'aosev_save' ) ) { return; }
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return; }
	if ( ! current_user_can( 'edit_post', $post_id ) ) { return; }
	foreach ( aosev_fields() as $k => $def ) {
		$name = 'aosev_' . $k;
		if ( ! isset( $_POST[ $name ] ) ) { continue; }
		$raw = wp_unslash( $_POST[ $name ] );
		if ( 'url' === $def[0] ) { $val = esc_url_raw( $raw ); }
		elseif ( 'textarea' === $def[0] || 'lines' === $def[0] ) { $val = sanitize_textarea_field( $raw ); }
		elseif ( 'number' === $def[0] ) { $val = '' === $raw ? '' : absint( $raw ); }
		else { $val = sanitize_text_field( $raw ); }
		update_post_meta( $post_id, '_aosev_' . $k, $val );
	}
}

/* ---- 3. DATA BRIDGE: build the events array the app consumes ---- */
function aosev_lines( $s ) {
	$out = array();
	foreach ( preg_split( '/\r\n|\r|\n/', (string) $s ) as $l ) { $l = trim( $l ); if ( '' !== $l ) { $out[] = $l; } }
	return $out;
}
function aosev_agenda_rows( $s ) {
	$rows = array();
	foreach ( aosev_lines( $s ) as $l ) {
		if ( preg_match( '/^(\d{1,2}:\d{2})\s+(.*)$/', $l, $m ) ) { $rows[] = array( $m[1], $m[2] ); }
		else { $rows[] = array( '', $l ); }
	}
	return $rows;
}
function aosev_json_events( $limit = 200 ) {
	$rows = array(); $meets = array();
	$q = new WP_Query( array(
		'post_type' => 'aosars_event', 'post_status' => 'publish', 'posts_per_page' => $limit,
		'orderby' => 'meta_value', 'meta_key' => '_aosev_start', 'order' => 'ASC', 'no_found_rows' => true,
	) );
	foreach ( (array) $q->posts as $p ) {
		$id = $p->ID;
		$g  = function ( $k ) use ( $id ) { return get_post_meta( $id, '_aosev_' . $k, true ); };
		$start = $g( 'start' ) ? strtotime( $g( 'start' ) ) : 0;
		$end   = $g( 'end' ) ? strtotime( $g( 'end' ) ) : 0;
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
			'lead' => $g( 'lead' ) ? $g( 'lead' ) : ( $g( 'summary' ) ? $g( 'summary' ) : get_the_excerpt( $id ) ),
			'addr' => $g( 'address' ), 'covers' => aosev_lines( $g( 'covers' ) ), 'agenda' => aosev_agenda_rows( $g( 'agenda' ) ),
			'permalink' => get_permalink( $id ), 'url' => $g( 'url' ) ? $g( 'url' ) : get_permalink( $id ),
		);
		if ( $g( 'code' ) ) { $meets[ $id ] = $g( 'code' ); }
	}
	if ( function_exists( 'wp_reset_postdata' ) ) { wp_reset_postdata(); }
	if ( empty( $rows ) ) { return aosev_sample_events(); }
	return array( $rows, $meets );
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
function aosev_mount( $state = null ) {
	try {
		list( $events, $meets ) = aosev_json_events();
		$data = array( 'events' => $events, 'meets' => (object) $meets );
		if ( $state ) { $data['state'] = $state; }
		$json = wp_json_encode( $data );
		$out  = aosev_css();
		$out .= '<script>window.AOSEV_DATA=' . $json . ';</script>';
		$out .= '<div class="aosev-app"><main class="wrap" id="AOSEV_ROOT"></main></div>';
		$out .= aosev_js();
		return $out;
	} catch ( \Throwable $e ) { return ''; }
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

/* ---- 6. SINGLE CPT PAGE + SCHEMA ---- */
add_filter( 'the_content', aosev_guard( 'aosev_append_single' ), 20 );
function aosev_append_single( $content ) {
	if ( is_admin() || ! is_singular( 'aosars_event' ) || ! in_the_loop() || ! is_main_query() ) { return $content; }
	if ( false !== strpos( $content, 'aosev-app' ) ) { return $content; }
	$s = aosev_settings();
	if ( empty( $s['auto_append'] ) ) { return $content; }
	$id = (int) get_the_ID();
	// Behave like a post: if the event page is designed in Elementor, respect that
	// layout and do not append the default view (add the Single Event widget instead).
	if ( 'builder' === get_post_meta( $id, '_elementor_edit_mode', true ) ) { return $content; }
	return $content . aosev_mount( array( 'view' => 'single', 'id' => $id ) );
}

/* Provide a single-event template that calls the_content() so page builders
   (Elementor) can edit events even when the active theme's template does not
   call the_content() for custom post types. Without this, Elementor reports
   "you must call the the_content function in the current template". Theme
   overrides are respected, and it can be disabled with:
   add_filter( 'aosev_use_single_template', '__return_false' ); */
add_filter( 'single_template', aosev_guard( 'aosev_single_template' ) );
function aosev_single_template( $template ) {
	if ( ! is_singular( 'aosars_event' ) ) { return $template; }
	if ( ! apply_filters( 'aosev_use_single_template', true, $template ) ) { return $template; }
	// Respect a theme that explicitly targets this post type.
	$theme = locate_template( array( 'single-aosars_event.php', 'aosars-events/single-aosars_event.php' ) );
	if ( $theme ) { return $theme; }
	$plugin = plugin_dir_path( __FILE__ ) . 'templates/single-aosars_event.php';
	if ( file_exists( $plugin ) ) { return $plugin; }
	return $template;
}
add_action( 'wp_head', aosev_guard( 'aosev_schema' ) );
function aosev_schema() {
	if ( ! is_singular( 'aosars_event' ) ) { return; }
	$id = get_the_ID(); if ( ! $id ) { return; }
	$g = function ( $k ) use ( $id ) { return get_post_meta( $id, '_aosev_' . $k, true ); };
	$start = $g( 'start' ) ? strtotime( $g( 'start' ) ) : 0;
	$end   = $g( 'end' ) ? strtotime( $g( 'end' ) ) : 0;
	$mode  = $g( 'mode' ) ? $g( 'mode' ) : 'Online';
	$data  = array(
		'@context' => 'https://schema.org', '@type' => 'Event', 'name' => get_the_title( $id ),
		'startDate' => $start ? gmdate( 'c', $start ) : '', 'endDate' => $end ? gmdate( 'c', $end ) : '',
		'eventAttendanceMode' => ( 'Online' === $mode ) ? 'https://schema.org/OnlineEventAttendanceMode' : 'https://schema.org/OfflineEventAttendanceMode',
		'description' => wp_strip_all_tags( $g( 'lead' ) ? $g( 'lead' ) : $g( 'summary' ) ), 'url' => get_permalink( $id ),
	);
	if ( $g( 'venue' ) ) { $data['location'] = ( 'Online' === $mode ) ? array( '@type' => 'VirtualLocation', 'url' => $g( 'url' ) ? $g( 'url' ) : get_permalink( $id ) ) : array( '@type' => 'Place', 'name' => $g( 'venue' ), 'address' => $g( 'address' ) ); }
	echo "\n" . '<script type="application/ld+json">' . wp_json_encode( array_filter( $data ) ) . '</script>' . "\n";
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
	foreach ( array( 'AOSEV_El_Portal', 'AOSEV_El_Single' ) as $c ) {
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
}

/* ---- 8. SETTINGS + SITE HEALTH ---- */
if ( is_admin() ) {
	add_action( 'admin_menu', aosev_guard( 'aosev_settings_menu' ) );
	add_action( 'admin_init', aosev_guard( 'aosev_settings_register' ) );
}
function aosev_settings_menu() { add_submenu_page( 'edit.php?post_type=aosars_event', __( 'Events Settings', 'aosars-events' ), __( 'Settings', 'aosars-events' ), 'manage_options', 'aosev-settings', 'aosev_settings_page' ); }
function aosev_settings_register() { register_setting( 'aosev_settings_group', AOSEV_OPTION, 'aosev_settings_sanitize' ); }
function aosev_settings_sanitize( $in ) { $in = is_array( $in ) ? $in : array(); return array( 'currency' => isset( $in['currency'] ) ? sanitize_text_field( $in['currency'] ) : 'KES', 'all_url' => isset( $in['all_url'] ) ? esc_url_raw( $in['all_url'] ) : '', 'auto_append' => empty( $in['auto_append'] ) ? 0 : 1 ); }
function aosev_settings_page() {
	$s = aosev_settings();
	echo '<div class="wrap"><h1>' . esc_html__( 'AOSARS Events Settings', 'aosars-events' ) . '</h1><form method="post" action="options.php">';
	settings_fields( 'aosev_settings_group' );
	echo '<table class="form-table"><tbody>';
	echo '<tr><th>' . esc_html__( 'Default currency', 'aosars-events' ) . '</th><td><input type="text" name="' . esc_attr( AOSEV_OPTION ) . '[currency]" value="' . esc_attr( $s['currency'] ) . '" class="regular-text"></td></tr>';
	echo '<tr><th>' . esc_html__( 'View all events URL', 'aosars-events' ) . '</th><td><input type="url" name="' . esc_attr( AOSEV_OPTION ) . '[all_url]" value="' . esc_attr( $s['all_url'] ) . '" class="regular-text"></td></tr>';
	echo '<tr><th>' . esc_html__( 'Auto-show event layout', 'aosars-events' ) . '</th><td><label><input type="checkbox" name="' . esc_attr( AOSEV_OPTION ) . '[auto_append]" value="1" ' . checked( ! empty( $s['auto_append'] ), true, false ) . '> ' . esc_html__( 'Append the AOSARS layout on single event pages. Turn off to design events entirely in Elementor or the block editor.', 'aosars-events' ) . '</label></td></tr>';
	echo '</tbody></table>';
	submit_button();
	echo '</form><h2>' . esc_html__( 'How to place events', 'aosars-events' ) . '</h2>';
	echo '<p><code>[aosars_events_portal]</code> ' . esc_html__( 'or the Elementor "AOSARS Events Portal" widget shows the full portal.', 'aosars-events' ) . '</p>';
	echo '<p><code>[aosars_event id="123"]</code> ' . esc_html__( 'shows one event. Each event also has its own page.', 'aosars-events' ) . '</p>';
	echo '<p>' . esc_html__( 'Events are edited like posts and can be opened with Edit with Elementor.', 'aosars-events' ) . '</p></div>';
}
add_filter( 'site_status_tests', aosev_guard( 'aosev_sh_register' ) );
function aosev_sh_register( $t ) { $t['direct']['aosev_events'] = array( 'label' => __( 'AOSARS Events', 'aosars-events' ), 'test' => 'aosev_sh' ); return $t; }
function aosev_sh() {
	$i = array();
	if ( ! post_type_exists( 'aosars_event' ) ) { $i[] = __( 'the event post type is not registered', 'aosars-events' ); }
	foreach ( array( 'aosars_events_portal', 'aosars_event' ) as $sc ) { if ( ! shortcode_exists( $sc ) ) { $i[] = sprintf( __( 'shortcode [%s] missing', 'aosars-events' ), $sc ); } }
	$ok = empty( $i );
	return array(
		'label' => $ok ? __( 'AOSARS Events is ready', 'aosars-events' ) : __( 'AOSARS Events needs attention', 'aosars-events' ),
		'status' => $ok ? 'good' : 'recommended', 'badge' => array( 'label' => __( 'AOSARS', 'aosars-events' ), 'color' => 'blue' ),
		'description' => '<p>' . esc_html( $ok ? __( 'The event type and shortcodes are active.', 'aosars-events' ) : implode( '; ', array_map( 'sanitize_text_field', $i ) ) ) . '</p>', 'test' => 'aosev_events',
	);
}
