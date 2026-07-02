<?php
// Runs on delete. Remove only our settings option; event posts are user content.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }
delete_option( 'aosev_settings' );
