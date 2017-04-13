<?php

/**
	* When the AJAX add to cart button is pushed we need to update the calculator
  * widget by AJAX as well.
  *
  * Use the form data sent to get the category and price to be added to the widget
	*/
  add_action( 'wp_ajax_nopriv_sbs_adding_to_cart', 'sbs_ajax_adding_to_cart' );
  add_action( 'wp_ajax_sbs_adding_to_cart', 'sbs_ajax_adding_to_cart' );

  function sbs_ajax_adding_to_cart()  {

    $product_id = absint( $_POST['product_id'] );
    $product = wc_get_product( $product_id );

    $unit_price = $product->get_price();
    $quantity = empty( $_POST['quantity'] ) ? 1 : wc_stock_amount( $_POST['quantity'] );

    $total_price_with_tax = wc_get_price_including_tax( $product, array( 'qty' => $quantity, 'price' => '' ) );
    $total_price_without_tax = wc_get_price_excluding_tax( $product, array( 'qty' => $quantity, 'price' => '' ) );

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

// add_action( 'woocommerce_ajax_added_to_cart', 'sbs_ajax_add_to_cart_handler', 10, 1);
//
// function sbs_get_product_parent_category( $product_id ) {
//
//   $categories = wp_get_post_terms($product_id, 'product_cat');
//
//   foreach ($categories as $key => $category) {
//     if ($category->parent === 0)
//       return $category;
//   }
//
// }
//
// function sbs_ajax_add_to_cart_handler( $product_id ) {
//
//   $product = wc_get_product( $product_id );
//
//   $category = sbs_get_product_parent_category( $product_id )->name;
//   $price = $product->get_price();
//
//   if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
//     wc_add_to_cart_message( array( $product_id => $quantity ), true );
//   }
//
//   ob_start();
//
//   woocommerce_mini_cart();
//
//   $mini_cart = ob_get_clean();
//
//   $data = array(
//     'product' => array( 'category' => $category, 'price' => $price ),
//     'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
//         'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
//       )
//     ),
//     'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() ),
//   );
//
//   wp_send_json( $data );
//
// }
