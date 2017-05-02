<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}



// Selecting a package will clear the cart, restarting the user's session

function sbs_select_package_and_clear_cart( $passed, $product_id, $quantity ) {

  global $woocommerce;

  $package_cat_id = (int) get_option('sbs_package')['category'];
  $product_parent_cat = sbs_get_product_parent_category( $product_id )->term_id;

  if ( $product_parent_cat === $package_cat_id  ) {
    $woocommerce->cart->empty_cart();
  }

  return true;

}
add_action( 'woocommerce_add_to_cart_validation', 'sbs_select_package_and_clear_cart', 1, 3 );


// Apply any store credit assigned to the package in the cart

function sbs_apply_merchandise_credit() {

	global $woocommerce;
	$cart = $woocommerce->cart->get_cart();
	// Get total value of all items in cart, except the package
	$package = sbs_get_package_from_cart();

	if ( isset( $package ) && isset( $package['credit'] ) ) {

		$cart_total = $woocommerce->cart->cart_contents_total - $package['item']['line_total'];

		// The amount of credit applied caps at some specified value.
		// It should be negative since we are adding a negative fee to the total
		$credit = -1 * min( $package['credit'], $cart_total );
		$credit_title = 'Merchandise Credit Applied (up to $' .
										$package['credit'] .
										')';

		// Then apply the credit
		$woocommerce->cart->add_fee( $credit_title, $credit, false );

	}

}
add_action( 'woocommerce_cart_calculate_fees', 'sbs_apply_merchandise_credit' );


function sbs_woocommerce_loop_add_to_cart_link( $html, $product ) {

	global $woocommerce;

	if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
		$html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
		$html .= 'Qty.' . woocommerce_quantity_input( array(), $product, false );
		$html .= '<button type="submit" class="button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
		$html .= '</form>';

		return $html;
	}

	elseif ( $product && $product->is_sold_individually() && sbs_get_cart_key( $product->get_id() ) ) {
		$remove_url = $woocommerce->cart->get_remove_url( sbs_get_cart_key( $product->get_id() ) );

		$html = '<form action="' . esc_url( $remove_url ) . '" class="cart" method="post" enctype="multipart/form-data">';
		$html .= '<button type="submit" class="button alt">' . 'Remove' . '</button>';
		$html .= '</form>';

		return $html;
	}

	elseif ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
		$html = '<form action="' . esc_url( $product->add_to_cart_url() ) . '" class="cart" method="post" enctype="multipart/form-data">';
		$html .= '<button type="submit" class="button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
		$html .= '</form>';

		return $html;
	}

	return $html;

}

add_filter( 'woocommerce_loop_add_to_cart_link', 'sbs_woocommerce_loop_add_to_cart_link', 10, 2 );
