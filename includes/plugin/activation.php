<?php

/**
 *  Step-By-Step Plugin Activation Script
 *
 *
 */

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
