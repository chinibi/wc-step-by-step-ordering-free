<?php

// Apply any store credit assigned to the package in the cart

if ( !defined( 'ABSPATH' ) ) {
  exit; // Exit if accessed directly
}

function sbs_apply_merchandise_credit() {

	global $woocommerce;
	$cart = $woocommerce->cart->get_cart();
	// Get total value of all items in cart, except the package
	$package = sbs_get_package_from_cart();

  $license = sbs_check_license_cache();

  if ( !$license ) {
    return;
  }

	if ( isset( $package ) && $package['credit'] > 0 ) {

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
