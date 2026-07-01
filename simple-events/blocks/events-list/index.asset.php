<?php
/**
 * Asset dependencies for the Events List block editor script.
 *
 * Hand-authored because the block ships without a build step. WordPress reads
 * this file (matched by filename to index.js) to enqueue the correct script
 * dependencies and version.
 *
 * @package SimpleEvents
 */

return array(
	'dependencies' => array(
		'wp-blocks',
		'wp-block-editor',
		'wp-components',
		'wp-element',
		'wp-i18n',
		'wp-server-side-render',
	),
	'version'      => '1.0.0',
);
