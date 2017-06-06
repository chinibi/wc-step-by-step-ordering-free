<?php

/**
 *	Additional actions hooked into WooCommerce
 *
 *	This file is for miscellaneous snippet-sized pieces of additional
 *	functionality to be added into WooCommerce.
 *
 *	Any functionality of significant code length should be in their own
 *	separate files in this directory.
 *
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Selecting a package will clear the cart, restarting the user's session

function sbs_select_package_and_clear_cart( $passed, $product_id, $quantity ) {

  global $woocommerce;

  $package_cat_id = (int) get_option('sbs_package')['category'];
  $product_parent_cat = sbs_get_product_parent_category( $product_id )->term_id;

	$empty_cart_option = isset( get_option('sbs_package')['clear-cart'] ) ? get_option('sbs_package')['clear-cart'] : '1';

  if ( $product_parent_cat === $package_cat_id ) {

		if ( $empty_cart_option === '1' ) { // Empty Cart on package select

	    $woocommerce->cart->empty_cart();

		}
		elseif ( $empty_cart_option === '2' ) { // Do not empty cart, only swap packages

			$cart = $woocommerce->cart->get_cart();
			foreach( $cart as $cart_key => $cart_item ) {

				$cart_item_categories = wp_get_post_terms( $cart_item['product_id'], 'product_cat' );
				foreach( $cart_item_categories as $cart_item_category ) {
					if ( $cart_item_category->term_id == $package_cat_id ) {
						$woocommerce->cart->remove_cart_item( $cart_key );
						break;
					}
				}

			}

		}
  }

  return true;

}
add_action( 'woocommerce_add_to_cart_validation', 'sbs_select_package_and_clear_cart', 1, 3 );


function sbs_render_checkout_sbs_navbar() {

  $all_categories = sbs_get_all_wc_categories();

  $steps = sbs_get_full_step_order( true );

  $current_step = count( $steps ) - 1;

  ob_start();
  ?>
  <div id="sbs-navbar">
		<?php sbs_render_sbs_navbar( $current_step, $steps ) ?>
  </div>
	<?php

	echo ob_get_clean();

}
add_action( 'woocommerce_before_checkout_notice', 'sbs_render_checkout_sbs_navbar', 10 );


function sbs_render_checkout_goback_button() {

	$all_categories = sbs_get_all_wc_categories();
	$steps = sbs_get_full_step_order();

	$current_step = count($steps) - 1;
	echo '<a class="button alt sbs-checkout-return" href="' . sbs_previous_step_url( $current_step, count($steps) ) . '">&#171; Return to Ordering</a>';

}
add_action( 'woocommerce_review_order_before_submit', 'sbs_render_checkout_goback_button', 10 );


function sbs_highlight_package_checkout( $class_name, $cart_item ) {

	if ( isset( get_option('sbs_package')['category'] ) ) {

		$package_cat = (int) get_option('sbs_package')['category'];

		$cart_item_cat = sbs_get_product_parent_category( $cart_item['product_id'] );

		if ( $package_cat === $cart_item_cat->term_id ) {
			$class_name .= ' checkout-package';
		}

	}

	return $class_name;

}
add_filter( 'woocommerce_cart_item_class', 'sbs_highlight_package_checkout', 10, 2 );


function sbs_add_cart_shortcode_to_checkout() {

	echo '<h2>Review Your Order</h2>';
	echo do_shortcode( '[woocommerce_cart]' );

}
add_action( 'woocommerce_before_checkout_form', 'sbs_add_cart_shortcode_to_checkout', 10 );


// Fix for actions being called when the WooCommerce $product global is unavailable.
function sbs_reprioritize_single_product_actions() {

	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 11 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 20 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 25 );

}
add_action( 'woocommerce_loaded', 'sbs_reprioritize_single_product_actions' );


// Move the package product to the top of the cart list. Fixes autoadd-type
// products being listed above packages.
function sbs_move_package_to_top_of_cart_list() {

	global $woocommerce;
	$cart = $woocommerce->cart->cart_contents;
	$package = sbs_get_package_from_cart();

	if ( empty( $package ) ) {
		return;
	}

	$package_in_cart = array();
	foreach( $cart as $key => $cart_item ) {
		if ( $key == $package['key'] ) {
			$package_in_cart = array( $key => $cart_item );
			$woocommerce->cart->cart_contents = $package_in_cart + $woocommerce->cart->cart_contents;
			break;
		}
	}

}
add_action( 'woocommerce_cart_loaded_from_session', 'sbs_move_package_to_top_of_cart_list', 100 );


// Attach required attributes and CSS classes to the product loop link wrapper
function woocommerce_template_loop_product_link_open_custom() {
  global $product;
	echo '<a href="' . get_the_permalink() . '" data-mfp-src="#modal-product-' . $product->get_id() .'" class="woocommerce-LoopProduct-link open-popup-link">';
}
function sbs_replace_woocommerce_template_loop_product_link_open() {

  remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
  add_action ( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open_custom', 10 );

}

add_action( 'plugins_loaded', 'sbs_replace_woocommerce_template_loop_product_link_open' );


function sbs_query_key_verification_server() {

  $server_key = 'sb4Oj2baaBvIp7gP67lNq370';
  $server_url = 'http://stepbystepsys.com';
  $item_reference = 'SBS Premium License';

  $license_key = get_option('sbs_premium_key');

  if ( empty( $license_key ) ) {
    return array(
      'verify_success' => false,
      'response_success' => true,
      'message' => 'No key provided.',
			'data' => null
    );
  }

  $check_api_params = array(
    'slm_action' => 'slm_check',
    'secret_key' => $server_key,
    'license_key' => $license_key
  );

  $check_response = wp_remote_get( add_query_arg( $check_api_params, $server_url ), array( 'timeout' => 15 ) );

  if ( is_wp_error( $check_response ) ) {
    return array(
      'verify_success' => true,
      'response_success' => false,
      'message' => 'The verification server was unable to respond.',
			'data' => null
    );
  }

  $check_response = json_decode( wp_remote_retrieve_body( $check_response ) );

  if ( $check_response->status === 'active' ) {
    return array(
      'verify_success' => true,
      'response_success' => true,
      'message' => 'Key verified.',
			'data' => $check_response
    );
  }
  else {
    return array(
      'verify_success' => false,
      'response_success' => true,
      'message' => 'Key invalid or expired.',
			'data' => $check_response
    );
  }

}


function sbs_has_valid_premium_key() {

  $response = sbs_query_key_verification_server();
  return $response['verify_success'] && $response['response_success'];

}


function sbs_daily_premium_key_check() {

  if ( sbs_has_valid_premium_key() ) {
    set_site_transient( 'sbs_premium_key_valid', 'true', 2 * DAY_IN_SECONDS );
  } else {
    set_site_transient( 'sbs_premium_key_valid', 'false', 2 * DAY_IN_SECONDS );
  }

}

// Hook the premium key check to a WP daily cron job
add_action( 'sbs_daily_event', 'sbs_daily_premium_key_check' );
