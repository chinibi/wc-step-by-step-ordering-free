<?php

/**
 *
 *  Products can be marked as 'Auto-Add', which will be automatically added
 *  to the cart when the customer begins the ordering process by selecting
 *  a package.
 *
 *
 */

if ( !defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function sbs_autoadd_products_to_cart( $passed, $product_id ) {

  $license = sbs_check_license_cache();

  if ( !$license ) {
    return $passed;
  }

  global $woocommerce;

  $package_cat = isset( get_option('sbs_package')['category'] ) ? (int) get_option('sbs_package')['category'] : null;
  $product_parent_cat = sbs_get_product_parent_category( $product_id )->term_id;

  if ( empty( $package_cat ) || $package_cat !== $product_parent_cat ) {
    return $passed;
  }

  $args = array(
    'post_type' => 'product',
    'post_status' => 'publish',
    'posts_per_page' => -1,
    'meta_key' => '_autoadd_product',
    'meta_value' => 'yes'
  );

  $products = get_posts( $args );

  foreach( $products as $product ) {
    if ( !sbs_get_cart_key( $product->ID ) )
      $woocommerce->cart->add_to_cart( $product->ID );
  }

  return $passed;

}
add_action( 'woocommerce_add_to_cart_validation', 'sbs_autoadd_products_to_cart', 10, 2 );
