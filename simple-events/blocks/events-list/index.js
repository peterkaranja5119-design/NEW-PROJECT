/**
 * Editor script for the Simple Events "Events List" block.
 *
 * Written without JSX so it runs directly in the browser with no build step.
 * Uses ServerSideRender for the editor preview so the markup always matches
 * the front end.
 */
( function ( wp ) {
	'use strict';

	var el = wp.element.createElement;
	var __ = wp.i18n.__;
	var registerBlockType = wp.blocks.registerBlockType;
	var InspectorControls = wp.blockEditor.InspectorControls;
	var useBlockProps = wp.blockEditor.useBlockProps;
	var PanelBody = wp.components.PanelBody;
	var RangeControl = wp.components.RangeControl;
	var SelectControl = wp.components.SelectControl;
	var TextControl = wp.components.TextControl;
	var ToggleControl = wp.components.ToggleControl;
	var ServerSideRender = wp.serverSideRender;

	registerBlockType( 'simple-events/events-list', {
		edit: function ( props ) {
			var attributes = props.attributes;
			var setAttributes = props.setAttributes;

			var inspector = el(
				InspectorControls,
				{ key: 'inspector' },
				el(
					PanelBody,
					{ title: __( 'Events Settings', 'simple-events' ), initialOpen: true },
					el( RangeControl, {
						label: __( 'Number of events', 'simple-events' ),
						value: attributes.limit,
						min: 1,
						max: 20,
						onChange: function ( value ) {
							setAttributes( { limit: value } );
						},
					} ),
					el( SelectControl, {
						label: __( 'Scope', 'simple-events' ),
						value: attributes.scope,
						options: [
							{ label: __( 'Upcoming', 'simple-events' ), value: 'upcoming' },
							{ label: __( 'Past', 'simple-events' ), value: 'past' },
							{ label: __( 'All', 'simple-events' ), value: 'all' },
						],
						onChange: function ( value ) {
							setAttributes( { scope: value } );
						},
					} ),
					el( TextControl, {
						label: __( 'Category slug', 'simple-events' ),
						help: __( 'Leave empty to show events from all categories.', 'simple-events' ),
						value: attributes.category,
						onChange: function ( value ) {
							setAttributes( { category: value } );
						},
					} ),
					el( ToggleControl, {
						label: __( 'Show excerpt', 'simple-events' ),
						checked: attributes.showExcerpt,
						onChange: function ( value ) {
							setAttributes( { showExcerpt: value } );
						},
					} )
				)
			);

			var preview = el( ServerSideRender, {
				block: 'simple-events/events-list',
				attributes: attributes,
			} );

			return el(
				'div',
				useBlockProps(),
				inspector,
				preview
			);
		},

		save: function () {
			// Server-side rendered.
			return null;
		},
	} );
} )( window.wp );
