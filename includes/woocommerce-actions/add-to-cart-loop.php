<?php

/**
 *  WooCommerce Product Loop Actions and Filters
 *
 *
 *
 */

add_action( 'woocommerce_after_shop_loop_item', 'sbs_product_loop_quantity_in_cart', 8 );
add_action( 'woocommerce_after_shop_loop_item', 'sbs_product_loop_mfp_modal', 25 );
add_filter( 'woocommerce_loop_add_to_cart_link', 'sbs_product_loop_add_to_cart_link', 10, 2 );
add_filter( 'woocommerce_loop_add_to_cart_link', 'sbs_woocommerce_loop_add_to_cart_link', 15, 2 );

if ( wp_get_theme()->get('Name') == 'Divi' || wp_get_theme()->get('Template') == 'Divi' ) {
	add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 20 );
}


function sbs_product_loop_quantity_in_cart() {

	if ( !is_sbs() && get_option('sbs_general')['ui-outside-sbs'] === 'no' ) {
		return;
	}

	global $woocommerce;
	global $product;

	$quantity = (int) sbs_get_cart_key( $product->get_id() )['cart_item']['quantity'];

	if ( $quantity > 0 ) {

		echo '<div class="product-loop-in-cart">';
		echo '<span class="product-loop-in-cart-text">';
		echo esc_html( sbs_get_cart_key( $product->get_id() )['cart_item']['quantity'] ) . ' ' . __('In Cart', 'wc-step-by-step-ordering');
		echo '<small class="product-loop-remove"><a href="' . esc_url( $woocommerce->cart->get_remove_url( sbs_get_cart_key( $product->get_id() )['key'] ) ) . '">' . __('Remove', 'wc-step-by-step-ordering') . '</a></small>';
		echo '</span></div>';

	}

}


function sbs_product_loop_mfp_modal() {

	if ( !is_sbs() && get_option('sbs_general')['ui-outside-sbs'] === 'no' ) {
		return;
	}

	global $product;

	if ( $product->is_sold_individually() && sbs_get_cart_key( $product->get_id() ) ) {
		$open_popup_text = __('Change Selection', 'wc-step-by-step-ordering');
	}
	else {
		$open_popup_text = __('Learn More', 'wc-step-by-step-ordering');
	}

	if ( $product->get_image( 'shop_thumbnail', array(), false ) ) {
		echo '<a data-mfp-src="#modal-product-' . $product->get_id() . '" class="open-popup-link learn-more-link">' . $open_popup_text . '</a>';
		echo '<div id="modal-product-' . $product->get_id() . '" class="woocommerce white-popup mfp-hide">';
		echo    '<div class="modal-left-side">';
		echo      '<div class="modal-image">' . $product->get_image('post-thumbnail') . '</div>';
		echo    '</div>';
		echo    '<div class="modal-right-side single-product product">';
		do_action( 'woocommerce_single_product_summary' );
		echo    '</div>';
		echo '</div>';
	}
	else {
		echo '<a data-mfp-src="#modal-product-' . $product->get_id() . '" class="open-popup-link learn-more-link">' . $open_popup_text . '</a>';
		echo '<div id="modal-product-' . $product->get_id() . '" class="woocommerce white-popup mfp-hide">';
		echo    '<div class="modal-right-side modal-right-side-full single-product product">';
		do_action( 'woocommerce_single_product_summary' );
		echo    '</div>';
		echo '</div>';
	}

}


function sbs_woocommerce_loop_add_to_cart_link( $html, $product ) {

	if ( !is_sbs() && get_option('sbs_general')['ui-outside-sbs'] === 'no' ) {
		return $html;
	}

	global $woocommerce;

	if ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() && ! $product->is_sold_individually() ) {
		$html = '<form action="' . esc_url( remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $product->get_id() ) ) ) . '" class="cart" method="post" enctype="multipart/form-data">';
		$html .= '<div class="loop-quantity-input">';
		$html .= __('Qty.', 'wc-step-by-step-ordering') . woocommerce_quantity_input(array(), $product, false);
		$html .= '</div>';
		$html .= '<button type="submit" class="button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
		$html .= '</form>';

		return $html;
	}

	elseif ( $product && $product->is_sold_individually() && sbs_get_cart_key( $product->get_id() ) ) {
		$remove_url = $woocommerce->cart->get_remove_url( sbs_get_cart_key( $product->get_id() )['key'] );

		$html = '<form action="' . esc_url( $remove_url ) . '" class="cart" method="post" enctype="multipart/form-data">';
		$html .= '<button type="submit" class="button alt">' . __('Remove', 'wc-step-by-step-ordering') . '</button>';
		$html .= '</form>';

		return $html;
	}

	elseif ( $product && $product->is_type( 'simple' ) && $product->is_purchasable() && $product->is_in_stock() ) {
		$html = '<form action="' . esc_url( remove_query_arg( 'added-to-cart', add_query_arg( 'add-to-cart', $product->get_id() ) ) ) . '" class="cart" method="post" enctype="multipart/form-data">';
		$html .= '<button type="submit" class="button alt">' . esc_html( $product->add_to_cart_text() ) . '</button>';
		$html .= '</form>';

		return $html;
	}

	return $html;

}


function sbs_product_loop_add_to_cart_link( $html, $product ) {

	if ( !is_sbs() && get_option('sbs_general')['ui-outside-sbs'] === 'no' ) {
		return $html;
	}

	if ( ! $product->is_type('simple') ) {
		return sprintf( '<a rel="nofollow" data-quantity="%s" data-product_id="%s" data-product_sku="%s" data-mfp-src="#modal-product-%s" class="%s open-popup-link">%s</a><br>',
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_attr( $product->get_id() ),
			esc_attr( isset( $class ) ? $class : 'button' ),
			esc_html( $product->add_to_cart_text() )
		);
	}
	else {
		return sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a><br>',
			esc_url( $product->add_to_cart_url() ),
			esc_attr( isset( $quantity ) ? $quantity : 1 ),
			esc_attr( $product->get_id() ),
			esc_attr( $product->get_sku() ),
			esc_attr( isset( $class ) ? $class : 'button' ),
			esc_html( $product->add_to_cart_text() )
		);
	}

}
