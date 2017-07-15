<?php

/**
 *
 * Step-By-Step Plugin Uninstall Script
 *
 */

if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove cron jobs
wp_clear_scheduled_hook( 'sbs_daily_event' );

/**
 * Delete all data if a SBS_REMOVE_ALL_DATA constant is set to true in
 * wp-config.php.
 *
 * Does not delete: Products, Categories
 */

if ( defined( 'SBS_REMOVE_ALL_DATA' ) && SBS_REMOVE_ALL_DATA === true ) {
	// Delete all settings
	delete_option( 'sbs_version' );
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

	// Remove all SBS posts
	$sbs_ordering_page = get_option('sbs_general')['page-name'];
	$sbs_package_page = get_option('sbs_package')['page-name'];

	wp_delete_post( $sbs_ordering_page, true );
	wp_delete_post( $sbs_package_page, true );
}
