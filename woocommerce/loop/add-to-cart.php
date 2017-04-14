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
global $post;
global $product;

if ( $product->is_sold_individually() && is_item_in_cart( $product->get_id() ) ) {

  echo apply_filters( 'woocommerce_loop_add_to_cart_link',
  	sprintf( '<a rel="nofollow" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a><br>',
  		esc_attr( isset( $quantity ) ? $quantity : 1 ),
  		esc_attr( $product->get_id() ),
  		esc_attr( $product->get_sku() ),
  		esc_attr( (isset( $class ) ? $class : 'button') . ' nolink' ),
  		esc_html( "In Cart" )
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
echo '<div id="modal-product-' . $product->get_id() . '" class="white-popup mfp-hide">';
echo    '<div class="modal-left-side">';
echo        '<div class="modal-image">' . $product->get_image('post-thumbnail') . '</div>';
echo    '</div>';
echo    '<div class="modal-right-side">';
echo        '<div class="modal-title"><h3>' . $product->get_title() . '</h3></div>';
echo        '<div class="modal-price"><strong>' . wc_price($product->get_price()) . '</strong></div>';
echo        '<p>' . the_content() . '</p>';
echo        '<div class="modal-add-to-cart">';

if ( !$product->is_sold_individually() ) {
  echo            '<strong class="modal-add-to-cart-qty-label">Qty.</strong>';
  echo            '<input type="number" value="1" min="1" class="modal-select-quantity" id="modal-quantity-' . $product->get_id() . '">';
} else {
  echo            '<input type="number" value="1" min="1" class="modal-select-quantity hidden" id="modal-quantity-' . $product->get_id() . '">';
}

echo            '<a class="add-prod-link add-prod-link-modal" onclick=addToCartWithQuantity(' . $product->get_id() . ',"#modal-quantity-' . $product->get_id() . '") >Add To Cart</a>';
echo    '</div>';
echo '</div>';
