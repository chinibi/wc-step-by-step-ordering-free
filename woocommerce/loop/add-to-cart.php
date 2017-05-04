<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.5.0
 */

 /**
  * Loop Add to Cart
  *
  * This is a custom template built off of WooCommerce's Loop Add to Cart.
  * Adds a 'Learn More' button at the bottom of each product that opens
  * a modal showing details about the product and allowing the customer
  * to select a quantity of the item to be added to the cart.
  *
  *
  * @author 		Trevor Pham
  * @created    Apr 13, 2017
  *
  */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $woocommerce;
global $product;

// echo apply_filters( 'woocommerce_loop_add_to_cart_link',
//   sprintf( '<a rel="nofollow" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a><br>',
//     esc_attr( isset( $quantity ) ? $quantity : 1 ),
//     esc_attr( $product->get_id() ),
//     esc_attr( $product->get_sku() ),
//     esc_attr( (isset( $class ) ? $class : 'button') . ' nolink' ),
//     esc_html( "In Cart" )
//   ),
// $product );

if ( sbs_get_cart_key( $product->get_id() ) ) {
  echo '<div class="product-loop-in-cart">';
  echo '<span class="product-loop-in-cart-text">';
  echo esc_html( sbs_get_cart_key( $product->get_id() )['cart_item']['quantity'] ) . ' In Cart';
  echo '<small class="product-loop-remove"><a href="' . esc_url( $woocommerce->cart->get_remove_url( sbs_get_cart_key( $product->get_id() )['key'] ) ) . '">Remove</a></small>';
  echo '</span></div>';
}

if ( $product->is_type( 'variable' ) ) {

  echo apply_filters( 'woocommerce_loop_add_to_cart_link',
  	sprintf( '<a rel="nofollow" data-quantity="%s" data-product_id="%s" data-product_sku="%s" data-mfp-src="#modal-product-%s" class="%s open-popup-link">%s</a><br>',
  		esc_attr( isset( $quantity ) ? $quantity : 1 ),
  		esc_attr( $product->get_id() ),
  		esc_attr( $product->get_sku() ),
      esc_attr( $product->get_id() ),
  		esc_attr( isset( $class ) ? $class : 'button' ),
  		esc_html( $product->add_to_cart_text() )
  	),
  $product );

}

else {

  echo apply_filters( 'woocommerce_loop_add_to_cart_link',
  	sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a><br>',
  		esc_url( $product->add_to_cart_url() ),
  		esc_attr( isset( $quantity ) ? $quantity : 1 ),
  		esc_attr( $product->get_id() ),
  		esc_attr( $product->get_sku() ),
  		esc_attr( isset( $class ) ? $class : 'button' ),
  		esc_html( $product->add_to_cart_text() )
  	),
  $product );

}

echo '<a data-mfp-src="#modal-product-' . $product->get_id() . '" class="open-popup-link">Learn More</a>';
echo '<div id="modal-product-' . $product->get_id() . '" class="woocommerce white-popup mfp-hide">';
echo    '<div class="modal-left-side">';
echo      '<div class="modal-image">' . $product->get_image('post-thumbnail') . '</div>';
echo    '</div>';
echo    '<div class="modal-right-side">';

do_action( 'woocommerce_single_product_summary' );

echo    '</div>';
echo '</div>';
