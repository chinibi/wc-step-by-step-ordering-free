<?php

/**
 *  Add custom fields to the product page.
 *
 *  Includes Auto-Add, Merchandise Credit, and Required products.
 *
 */

if ( !defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

// Display Fields
add_action( 'woocommerce_product_options_general_product_data', 'sbs_add_custom_general_fields' );

// Save Fields
add_action( 'woocommerce_process_product_meta', 'sbs_add_custom_general_fields_save' );

function sbs_add_custom_general_fields() {

  global $woocommerce, $post;

  $product = wc_get_product( $post->ID );

  echo '<div class="options_group">';

  echo '<p><strong>Step-By-Step Fields</strong></p>';

  $package_cat = isset( get_option('sbs_package')['category'] ) ? (int) get_option('sbs_package')['category'] : null;

  $product_parent_cat = sbs_get_product_parent_category( $post->ID );
  if ( !empty( $product_parent_cat ) && $product_parent_cat->term_id === $package_cat ) {

    woocommerce_wp_text_input(
      array(
        'id'                => '_merch_credit',
        'wrapper_class'     => 'show_if_simple show_if_grouped show_if_variable show_if_external',
        'label'             => __( 'Store Credit (' . get_woocommerce_currency_symbol() . ')', 'woocommerce' ),
        'type'              => 'number',
        'custom_attributes' => array( 'step' => 'any', 'min' => '0' ),
        'desc_tip'          => true,
        'description'       => 'Only works on products of the Package type category.  You can see which category is used in the Step-By-Step settings.'
      )
    );

  }

  woocommerce_wp_checkbox(
    array(
    	'id'            => '_required_product',
    	'wrapper_class' => 'show_if_simple show_if_grouped show_if_variable show_if_external',
    	'label'         => __( 'Required', 'woocommerce' ),
    	'description'   => __( 'Product must be in cart in order to check out.', 'woocommerce' )
    )
  );

  woocommerce_wp_checkbox(
    array(
      'id'            => '_autoadd_product',
      'wrapper_class' => 'show_if_simple show_if_grouped show_if_variable show_if_external',
      'label'         => __( 'Auto Add To Cart', 'woocommerce' ),
      'description'   => __( 'Automatically add to cart when customer navigates to containing page.', 'woocommerce' )
    )
  );

  echo '</div>';

}

function sbs_add_custom_general_fields_save( $post_id ) {

  if ( ! ( isset( $_POST['woocommerce_meta_nonce'] ) || wp_verify_nonce( sanitize_key( $_POST['woocommerce_meta_nonce'] ), 'woocommerce_save_data' ) ) ) {
    return false;
  }

  $package_cat = isset( get_option('sbs_package')['category'] ) ? (int) get_option('sbs_package')['category'] : null;

  $product_parent_cat = sbs_get_product_parent_category( $post_id );
  if ( !empty( $product_parent_cat ) && $product_parent_cat->term_id === $package_cat ) {

    $merch_cred_field = $_POST['_merch_credit'];
    if ( isset( $merch_cred_field ) )
      update_post_meta( $post_id, '_merch_credit', $merch_cred_field );

  }

  $required_product_field = isset( $_POST['_required_product'] ) ? 'yes' : 'no';
  update_post_meta( $post_id, '_required_product', $required_product_field );

  $autoadd_product_field = isset( $_POST['_autoadd_product'] ) ? 'yes' : 'no';
  update_post_meta( $post_id, '_autoadd_product', $autoadd_product_field );

}
