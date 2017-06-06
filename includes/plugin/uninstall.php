<?php

/**
 *
 * Step-By-Step Plugin Uninstall Script
 *
 */

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
  exit;
}

// Delete all settings
delete_option( 'sbs_general' );
delete_option( 'step_order' );
delete_option( 'sbs_navbar' );
delete_option( 'sbs_package' );
delete_option( 'sbs_onf' );
delete_option( 'sbs_display' );
delete_option( 'sbs_premium_key' );
delete_site_transient( 'sbs_premium_key_valid' );

// Delete all custom post meta keys
delete_post_meta_by_key( '_autoadd_product' );
delete_post_meta_by_key( '_required_product' );
delete_post_meta_by_key( '_merch_credit' );
