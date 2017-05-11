<?php

/**
 *
 *  Products can be marked as 'Auto-Add', which will be automatically added
 *  to the cart when the customer navigates to a step in the ordering process
 *  that contains such items.
 *
 *
 */

function sbs_autoadd_products_to_cart( $current_step, $steps ) {

  if ( !isset( $steps[$current_step]->catid ) ) {
    return;
  }

  global $woocommerce;

  $current_category = $steps[$current_step]->catid;

  $args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'tax_query' => array(
      array(
        'taxonomy' => 'pa_autoadd',
        'field' => 'slug',
        'terms' => 'autoadd'
      ),
      array(
        'taxonomy' => 'product_cat',
        'field' => 'id',
        'terms' => $current_category
      )
    )
  );

  $products = get_posts( $args );

  foreach( $products as $product ) {
    if ( !sbs_get_cart_key( $product->ID ) )
      $woocommerce->cart->add_to_cart( $product->ID );
  }

}
add_action( 'sbs_before_sbs_content', 'sbs_autoadd_products_to_cart', 10, 2 );
