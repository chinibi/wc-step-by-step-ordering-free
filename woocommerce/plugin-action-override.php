<?php

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
