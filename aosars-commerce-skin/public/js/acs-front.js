/* AOSARS Commerce Skin — front-end behaviour.
   Mini-cart drawer that hooks WooCommerce's own AJAX add-to-cart event.
   No forced redirect; degrades gracefully if elements are absent. */
( function ( $ ) {
	'use strict';
	if ( typeof window.ACS_DATA === 'undefined' ) { return; }

	var reduce = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
	var drawer = document.getElementById( 'acsDrawer' );
	var scrim  = document.getElementById( 'acsScrim' );
	var body   = document.getElementById( 'acsDrawerBody' );
	var lastFocus = null;

	function open() {
		if ( ! drawer || ! scrim ) { return; }
		lastFocus = document.activeElement;
		scrim.classList.add( 'open' );
		drawer.classList.add( 'open' );
		drawer.setAttribute( 'aria-hidden', 'false' );
		var closeBtn = document.getElementById( 'acsDrawerClose' );
		if ( closeBtn ) { closeBtn.focus(); }
	}
	function close() {
		if ( ! drawer || ! scrim ) { return; }
		scrim.classList.remove( 'open' );
		drawer.classList.remove( 'open' );
		drawer.setAttribute( 'aria-hidden', 'true' );
		if ( lastFocus && lastFocus.focus ) { lastFocus.focus(); }
	}

	// Wire the drawer chrome.
	$( document ).on( 'click', '#acsDrawerClose, #acsContinue', function ( e ) {
		e.preventDefault(); close();
	} );
	if ( scrim ) { scrim.addEventListener( 'click', close ); }
	$( document ).on( 'keydown', function ( e ) {
		if ( 'Escape' === e.key && drawer && drawer.classList.contains( 'open' ) ) { close(); }
	} );

	// Only run the drawer if the module is enabled.
	if ( ! ACS_DATA.drawer ) { return; }

	/* WooCommerce fires `added_to_cart` on the body after a successful AJAX add:
	   ( event, fragments, cart_hash, $button ). Build a confirmation from the
	   product card the button belongs to. */
	$( document.body ).on( 'added_to_cart', function ( event, fragments, cart_hash, $button ) {
		try {
			var title = '';
			var img   = '';
			var price = '';
			if ( $button && $button.length ) {
				var $card = $button.closest( 'li.product' );
				if ( $card.length ) {
					title = $card.find( '.acs-pc-title, .woocommerce-loop-product__title, h2, h3' ).first().text();
					img   = $card.find( 'img' ).first().attr( 'src' ) || '';
					price = $card.find( '.acs-pc-price, .price' ).first().text();
				}
				if ( ! title ) { title = $button.attr( 'aria-label' ) || $button.data( 'product_id' ) || ''; }
			}
			title = ( title || '' ).toString().trim();
			price = ( price || '' ).toString().trim();

			if ( body ) {
				var html = '<div class="acs-di">';
				if ( img ) { html += '<img src="' + img + '" alt="" />'; }
				html += '<span class="acs-di-t">' + escapeHtml( title || 'Item' ) + '</span>';
				if ( price ) { html += '<span class="acs-di-p">' + escapeHtml( price ) + '</span>'; }
				html += '</div>';
				// Cart total from WooCommerce fragments if present.
				var totalTxt = extractCartTotal( fragments );
				if ( totalTxt ) {
					html += '<div class="acs-di" style="border-bottom:0"><span class="acs-di-t">Cart total</span><span class="acs-di-p">' + escapeHtml( totalTxt ) + '</span></div>';
				}
				body.innerHTML = html;
			}
			open();
		} catch ( err ) {
			if ( window.console && window.console.warn ) { window.console.warn( 'ACS drawer:', err ); }
		}
	} );

	function extractCartTotal( fragments ) {
		try {
			if ( ! fragments ) { return ''; }
			for ( var key in fragments ) {
				if ( ! fragments.hasOwnProperty( key ) ) { continue; }
				var frag = $( '<div>' ).html( fragments[ key ] );
				var amt = frag.find( '.woocommerce-Price-amount, .amount' ).last();
				if ( amt.length ) { return amt.text(); }
			}
		} catch ( e ) {}
		return '';
	}

	function escapeHtml( s ) {
		return String( s ).replace( /[&<>"']/g, function ( c ) {
			return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[ c ];
		} );
	}
}( window.jQuery ) );
