<?php

/**
 * When the AJAX add to cart button is pushed we need to update the calculator
 * widget by AJAX as well.
 *
 * Because this function is hooked onto adding_to_cart, the calculations must
 * be done manually until the WooComerce developers make this data available
 * in added_to_cart
 *
 * Use the form data sent to get the category and price to be added to the widget
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'wp_ajax_nopriv_sbs_adding_to_cart', 'sbs_ajax_adding_to_cart' );
add_action( 'wp_ajax_sbs_adding_to_cart', 'sbs_ajax_adding_to_cart' );

add_action( 'wp_ajax_nopriv_sbs_query_grand_total', 'sbs_ajax_query_grand_total' );
add_action( 'wp_ajax_sbs_query_grand_total', 'sbs_ajax_query_grand_total' );

function sbs_ajax_adding_to_cart()  {

	$product_id = absint( $_POST['product_id'] );
	$product = wc_get_product( $product_id );

	// Get the price of the product times quantity
	$unit_price = $product->get_price();
	$quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );

	// Calculate Tax
	$total_price_with_tax = wc_get_price_including_tax( $product, array( 'qty' => $quantity, 'price' => '' ) );
	$total_price_without_tax = wc_get_price_excluding_tax( $product, array( 'qty' => $quantity, 'price' => '' ) );

	// Calculate store credit to apply.  This will be subtracted from the Grand Total
	// on the calculator

	$category = sbs_get_product_parent_category( $product_id )->name;
	$total_price = $unit_price * $quantity;
	$tax = $total_price_with_tax - $total_price_without_tax;
	$currency_symbol = get_woocommerce_currency_symbol();

	$data = array(
		'format' => array(
			'currency' => html_entity_decode( $currency_symbol ),
			'decimal_separator' => wc_get_price_decimal_separator(),
			'thousand_separator' => wc_get_price_thousand_separator(),
			'decimal_places' => wc_get_price_decimals()
		),
		'category' => $category,
		'total_price' => $total_price,
		'tax' => $tax
	);

	wp_send_json( $data );

}


function sbs_ajax_query_grand_total() {

	global $woocommerce;

	// You must call the calculate_fees() function since by default taxes are
	// calculated only on checkout
	$woocommerce->cart->calculate_fees();

	$sub_total = $woocommerce->cart->subtotal;
	$grand_total = max( 0, apply_filters( 'woocommerce_calculated_total', round( $woocommerce->cart->cart_contents_total + $woocommerce->cart->tax_total + $woocommerce->cart->shipping_tax_total + $woocommerce->cart->shipping_total + $woocommerce->cart->fee_total, $woocommerce->cart->dp ), $woocommerce->cart ) );

	$data = array(
		'format' => array(
			'currency' => html_entity_decode( get_woocommerce_currency_symbol() ),
			'decimal_separator' => wc_get_price_decimal_separator(),
			'thousand_separator' => wc_get_price_thousand_separator(),
			'decimal_places' => wc_get_price_decimals()
		),
		'sub_total' => $sub_total,
		'grand_total' => $grand_total
	);

	wp_send_json( $data );

}
