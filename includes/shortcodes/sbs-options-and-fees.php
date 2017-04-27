<?php

function sbs_options_and_fees_shortcode() {

  $onf_categories = sbs_get_onf_order();

  ob_start();
  ?>

  <table>
  <?php
  foreach ( $onf_categories as $category )
  {
  ?>
    <tr>
      <th colspan="3" class="sbs-subcat-name"><?php echo get_the_category_by_ID( $category->catid ) ?></th>
    </tr>

    <?php
    $products = sbs_get_wc_products_by_category( $category->catid );

    foreach ( $products as $product )
    {
    $wc_product = wc_get_product( $product->ID );
    ?>

    <tr>
      <td>
        <?php echo apply_filters( 'woocommerce_loop_add_to_cart_link',
        	sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s">%s</a><br>',
        		esc_url( $wc_product->add_to_cart_url() ),
        		esc_attr( isset( $quantity ) ? $quantity : 1 ),
        		esc_attr( $wc_product->get_id() ),
        		esc_attr( $wc_product->get_sku() ),
        		esc_attr( isset( $class ) ? $class : 'button' ),
        		esc_html( $wc_product->add_to_cart_text() )
        	),
        $wc_product ) ?>
      </td>
      <td>
        <div><?php echo $wc_product->get_name() ?></div>
        <div><?php echo $wc_product->get_description() ?></div>
      </td>
      <td>
        <?php echo wc_price( $wc_product->get_price() ) ?>
      </td>
    </tr>

    <?php
    }
    ?>

  <?php
  }
  ?>
  </table>

  <?php

  echo ob_get_clean();
}

add_shortcode( 'sbs_options_and_fees', 'sbs_options_and_fees_shortcode' );
