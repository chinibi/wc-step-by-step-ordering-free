<?php

/**
 *  Either-Or Product Categories allow up to one product in the cart per order.
 *
 *  Adds custom fields in Product Category creation or editing.
 *  Adds a woocommerce_check_cart_items action that swaps out cart items when
 *  the user attempts to add another item of the same either-or category.
 *
 */

if ( !defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

add_action( 'product_cat_add_form_fields', 'sbs_add_product_cat_custom_fields', 20 );
add_action( 'product_cat_edit_form_fields', 'sbs_edit_product_cat_custom_fields', 30 );
add_action( 'create_product_cat', 'sbs_save_product_cat_custom_meta', 10 );
add_action( 'edited_product_cat', 'sbs_save_product_cat_custom_meta', 10 );

function sbs_add_product_cat_custom_fields() {

  $license = sbs_check_license_cache();

  ?>
  <div class="form-field">
    <h2>Step-By-Step Additional Fields (Premium)</h2>
    <label for="sbs_either_or">Either-Or Category</label>
    <input type="checkbox" name="sbs_either_or" id="sbs_either_or" <?php disabled( false, $license ) ?>>
    <p class="description">Customers may only have up to one product from this category per order.</p>
  </div>
  <?php
}

function sbs_edit_product_cat_custom_fields( $term ) {

  $license = sbs_check_license_cache();
  $term_id = $term->term_id;
  $checked = get_term_meta( $term_id, 'sbs_either_or', true );

  ?>
  <tr class="form-field">
    <th scope="row" valign="top"><h2>Step-By-Step Additional Fields (Premium)</h2></th>
  </tr>
  <tr class="form-field">
    <th scope="row" valign="top"><label for="sbs_either_or">Either-Or Category</label></th>
    <td>
      <input type="checkbox" name="sbs_either_or" id="sbs_either_or" <?php checked('on', $checked) ?> <?php disabled( false, $license ) ?>>
      <p class="description">Customers may only have up to one product from this category per order.</p>
    </td>
  </tr>
  <?php
}

function sbs_save_product_cat_custom_meta( $term_id ) {

  $license = sbs_check_license_cache();

  if ( !$license ) {
    return;
  }

  $checked = filter_input( INPUT_POST, 'sbs_either_or' );
  update_term_meta( $term_id, 'sbs_either_or', $checked );

}


add_action( 'woocommerce_add_to_cart_validation', 'sbs_validate_either_or_product', 10, 2 );

function sbs_validate_either_or_product( $passed, $product_id ) {

  $license = sbs_check_license_cache();

  if ( !$license ) {
    return $passed;
  }

  global $woocommerce;
  $categories = wp_get_post_terms( $product_id, 'product_cat' );

  $belongs_in_eitheror_cat = false;
  $eitheror_cat = false;
  foreach( $categories as $category ) {

    $is_eitheror = get_term_meta( $category->term_id, 'sbs_either_or', true );
    if ( $is_eitheror === 'on' ) {
      $belongs_in_eitheror_cat = true;
      $eitheror_cat = $category->term_id;
      break;
    }

  }

  if ( $belongs_in_eitheror_cat === true ) {
    $cart = $woocommerce->cart->get_cart();

    foreach( $cart as $cart_key => $cart_item ) {

      $cart_item_categories = wp_get_post_terms( $cart_item['product_id'], 'product_cat' );
      foreach( $cart_item_categories as $cart_item_category ) {
        if ( $cart_item_category->term_id == $eitheror_cat ) {
          $woocommerce->cart->remove_cart_item( $cart_key );
          break;
        }
      }

    }

  }

  return $passed;

}
