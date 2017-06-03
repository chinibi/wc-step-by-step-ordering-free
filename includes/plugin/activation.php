<?php

/**
 *  Step-By-Step Plugin Activation Script
 */
if ( $this->version != get_option('sbs_version') ) {
  update_option( 'sbs_version', $this->version );
}

// Schedule a daily WP cron job of all actions hooked to sbs_daily_event
if ( ! wp_next_scheduled( 'sbs_daily_event' ) ) {
  wp_schedule_event( time(), 'daily', 'sbs_daily_event' );
}

// Create the Main Step-By-Step Page
if ( !post_exists( 'Step-By-Step Ordering' ) ) {
  $page_data = array(
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_author' => 1,
    'post_name'   => 'ordering',
    'post_title'  => 'Step-By-Step Ordering',
    'comment_status' => 'closed',
    'post_content'     => '[sbs_woocommerce_step_by_step_ordering]'
  );

  wp_insert_post( $page_data );
}

// Create the Choose Package Page
if ( !post_exists( 'Choose Package' ) ) {
  $page_data = array(
    'post_status' => 'publish',
    'post_type'   => 'page',
    'post_author' => 1,
    'post_name'   => 'choose-package',
    'post_title'  => 'Choose Package',
    'comment_status' => 'closed',
    'post_content'     => '[sbs_select_package]'
  );

  wp_insert_post( $page_data );
}

/**
 * Set default settings if none exist
 *
 */
