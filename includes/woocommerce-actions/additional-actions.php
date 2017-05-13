<?php

/**
 *	Additional actions hooked into WooCommerce
 *
 *	This file is for miscellaneous snippet-sized pieces of additional
 *	functionality to be added into WooCommerce.
 *
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

  if ( $product_parent_cat === $package_cat_id  ) {
    $woocommerce->cart->empty_cart();
  }

  return true;

}
add_action( 'woocommerce_add_to_cart_validation', 'sbs_select_package_and_clear_cart', 1, 3 );


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
		$remove_url = $woocommerce->cart->get_remove_url( sbs_get_cart_key( $product->get_id() )['key'] );

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

function sbs_render_checkout_sbs_navbar() {

  $all_categories = sbs_get_all_wc_categories();

  $steps = sbs_get_full_step_order();

  $current_step = count( $steps ) - 1;

  ob_start();
  ?>
  <div id="sbs-navbar">
    <?php foreach( $steps as $key => $step ):
            if ($key === 0) continue;
    ?>

            <span class="step-span-container">
              <div class="step-div-container">
                <div class="step-index">
									<span class="step-number-before <?php echo $key === $current_step ? 'active' : 'inactive' ?>"></span>
                  <span class="step-number <?php echo $key === $current_step ? 'active' : 'inactive' ?>">
                    <?php echo $key ?>
                  </span>
									<span class="step-number-after"></span>
                </div>
                <div class="step-title <?php echo $key === $current_step ? 'active' : 'inactive' ?>">
									<span class="step-title-text">
                    <?php

											if ( sbs_generate_navbar_url( $key, $current_step, count( $steps ) ) !== false )
											{
											?>
												<a href="<?php echo esc_url( sbs_generate_navbar_url( $key, $current_step, count($steps) ) ) ?>"><?php echo $step->name ?></a>
											<?php
											}
											else
											{
											?>
												<?php echo $step->name ?>
											<?php
											}

                    ?>
									</span>
                </div>
								<div class="clearfix"></div>
              </div>
            </span>

    <?php endforeach; ?>
  </div>
	<?php

	echo ob_get_clean();

}
add_action( 'woocommerce_before_checkout_notice', 'sbs_render_checkout_sbs_navbar', 10 );


function sbs_render_checkout_goback_button() {

	$all_categories = sbs_get_all_wc_categories();
	$steps = sbs_get_full_step_order();

	$current_step = count($steps) - 1;
	echo '<a class="button alt" href="' . sbs_previous_step_url( $current_step, count($steps) ) . '">&#171; Return to Ordering</a>';

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
