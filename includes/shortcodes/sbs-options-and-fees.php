<?php

function sbs_options_and_fees_add_to_cart( $product ) {

  global $woocommerce;
  global $quantity;
  global $class;

  if ( sbs_get_cart_key( $product->get_id() ) ) {
    echo '<div class="product-loop-in-cart">';
    echo '<span class="product-loop-in-cart-text">';
    echo esc_html( sbs_get_cart_key( $product->get_id() )['cart_item']['quantity'] ) . ' In Cart';
    echo '<small class="product-loop-remove"><a href="' . esc_url( $woocommerce->cart->get_remove_url( sbs_get_cart_key( $product->get_id() )['key'] ) ) . '">Remove</a></small>';
    echo '</span></div>';
  }

  if ( ! $product->is_type( 'simple' ) ) {

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

}

function sbs_options_and_fees_shortcode() {

  global $woocommerce;

  $onf_categories = sbs_get_onf_order();

  ob_start();
  ?>

  <table class="woocommerce sbs-options-and-fees">
  <?php
  foreach ( $onf_categories as $category )
  {
  ?>
    <tr>
      <th colspan="3" class="sbs-subcat-name"><?php echo get_the_category_by_ID( $category->catid ) ?></th>
    </tr>
    <tr>
      <th colspan="1" class="sbs-subcat-name"><?php echo get_the_category_by_ID( $category->catid ) ?></th>
    </tr>

    <?php

    $args = array(
      'post_type' => 'product',
      'post_status' => 'publish',
      'posts_per_page' => -1,
      'orderby' => 'menu_order',
      'tax_query' => array(
        array(
          'taxonomy' => 'product_cat',
  				'field' => 'id',
  				'terms' => $category->catid
        )
      )
    );

    $query = new WP_Query( $args );

    if ( $query->have_posts() ):
      while( $query->have_posts() ):
        $query->the_post();
        $product = wc_get_product( $query->post->ID );
        $is_required = get_post_meta( $product->get_id(), '_required_product', true ) === 'yes';
        ?>
        <tr class="<?php echo $is_required ? 'required' : null ?>">
          <td class="sbs-onf-desktop-add-to-cart">
            <?php sbs_options_and_fees_add_to_cart( $product ) ?>
          </td>
          <td class="sbs-onf-product-name">
            <div><?php echo $product->get_name() ?></div>
            <div><?php echo $product->get_description() ?></div>
            <div>
              <span class="danger"><?php echo $is_required ? '(Required)' : null ?></span>
              <a data-mfp-src="#modal-product-<?php echo $product->get_id() ?>" class="open-popup-link"><small>Learn More</small></a>
            </div>
          </td>
          <td class="sbs-onf-price-column">
            <div class="sbs-onf-price"><?php echo $product->get_price_html() ?></div>
            <div class="sbs-onf-mobile-add-to-cart"><?php sbs_options_and_fees_add_to_cart( $product ) ?></div>
          </td>
          <td style="display:none;">
            <?php
            echo '<div id="modal-product-' . $product->get_id() . '" class="woocommerce white-popup mfp-hide">';
            echo    '<div class="modal-left-side">';
            echo      '<div class="modal-image">' . $product->get_image('post-thumbnail') . '</div>';
            echo    '</div>';
            echo    '<div class="modal-right-side single-product">';

            do_action( 'woocommerce_single_product_summary' );

            echo    '</div>';
            echo '</div>';
            ?>
          </td>
        </tr>
        <?php
      endwhile;
    endif;
    wp_reset_postdata();
  }
  ?>
  </table>

  <?php

  echo ob_get_clean();
}

add_shortcode( 'sbs_options_and_fees', 'sbs_options_and_fees_shortcode' );
