<?php

/**
 *  Step-By-Step Plugin Activation Script
 */
update_option( 'sbs_version', $this->version );

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

$sbs_general_defaults = array(
  'page-name'               => get_page_by_title( 'Step-By-Step Ordering' )->ID,
  'featured-items-position' => '2',
  'featured-label'          => 'Featured Items',
  'req-label-before'        => 'Select',
  'req-label-after'         => '(Required)',
  'opt-label-before'        => '',
  'opt-label-after'         => '(Addons)'
);
add_option( 'sbs_general', $sbs_general_defaults );

add_option( 'step_order', '' );

$sbs_navbar_defaults = array(
  'throttle-nav' => '2'
);
add_option( 'sbs_navbar', $sbs_navbar_defaults );

$sbs_package_defaults = array(
  'enabled'          => '1',
  'label'            => 'Step-By-Step Ordering',
  'page-name'        => get_page_by_title( 'Choose Package' )->ID,
  'category'         => '',
  'clear-cart'       => '1',
  'per-row'          => '3',
  'add-to-cart-text' => 'Select Package',
  'image-height'     => '',
  'image-width'      => ''
);
add_option( 'sbs_package', $sbs_package_defaults );
