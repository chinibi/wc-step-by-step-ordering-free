<?php

class SBS_WC_Cart_Totals extends WP_Widget {
  public function __construct() {
    $widget_options = array(
      'classname'   => 'sbs_wc_cart_totals',
      'description' => 'Shows the total price of the items in the cart.'
    );
    parent::__construct( 'sbs_wc_cart_totals', 'WooCommerce Cart Totals', $widget_options);
  }

  public function widget( $args, $instance ) {
    // render only on WooCommerce shop pages and not on Cart and Checkout pages
    if ( is_cart() || is_checkout() ) {
      return;
    }

    // retrieve session data
    // session_start();

    // get woocommerce properties and methods
    global $woocommerce;

    // get the items currently in the cart
    // $cart = $woocommerce->cart->get_cart();
    // $package = get_package_information();
    // make an associative array that will track value of items in cart by category
    // $package_name = ($_SESSION['plan_type'] === 'pp' ? 'Pre-Plan ' : '') . str_replace('Package-', '', $package['package_tier']);


    $categories = sbs_get_step_order();
    $totals = array_map( array( $this, 'map_categories_to_widget_array_callback' ), $categories );

    // Append Sales Tax and Grand Total to $totals
    // You must call the calculate_fees() function since by default taxes are
    // calculated only on checkout
    $woocommerce->cart->calculate_fees();
    $totals[] = array(
      'cat_name' => 'Sales Tax',
      'cat_total' => wc_price( $woocommerce->cart->get_taxes_total() ),
      'css_class' => 'widget-sidebar-subtotal'
    );
    $totals[] = array(
      'cat_name' => 'GRAND TOTAL',
      'cat_total' => 	wc_price( max( 0, apply_filters( 'woocommerce_calculated_total', round( $woocommerce->cart->cart_contents_total + $woocommerce->cart->tax_total + $woocommerce->cart->shipping_tax_total + $woocommerce->cart->shipping_total + $woocommerce->cart->fee_total, $woocommerce->cart->dp ), $woocommerce->cart ) ) ),
      'css_class' => 'widget-sidebar-grand-total'
    );

    ?>
    <table id="sbs-widget-sidebar-cart-totals">
      <?php
      foreach($totals as $cat_info)
      {
      ?>
        <tr class="<?php echo esc_attr( $cat_info['css_class'] ) ?>">
          <td class="sbs-widget-sidebar-cat-name">
            <strong><?php echo esc_html( $cat_info['cat_name'] ) ?></strong>
          </td>
          <td data-cat="<?php echo esc_attr( $cat_info['cat_name'] ) ?>" class="sbs-widget-sidebar-total-column">
            <?php echo $cat_info['cat_total'] ?>
          </td>
        </tr>
      <?php
      }
      ?>
    </table>

  <?php
  }

  /**
   * Adds a filter to the wc_price return value
   *
   * Wrap the price numeric value in a span tag to make it easily addressable
   * by jQuery
   */
  public function filter_wc_price_add_span_tag( $return, $price, $args ) {

    extract( apply_filters( 'wc_price_args', wp_parse_args( $args, array(
    'ex_tax_label'       => false,
    'currency'           => '',
    'decimal_separator'  => wc_get_price_decimal_separator(),
    'thousand_separator' => wc_get_price_thousand_separator(),
    'decimals'           => wc_get_price_decimals(),
    'price_format'       => get_woocommerce_price_format(),
    ) ) ) );

    $negative        = $price < 0;
    $formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, '<span class="woocomerce-Price-numeric">' . get_woocommerce_currency_symbol( $currency ), $price . '</span>' );

    $return = '<span class="woocommerce-Price-amount amount">' . $formatted_price . '</span>';

    return $return;

  }


/**
 * Gets the total value of cart items of a specific category, given its ID
 *
 * Get the WooCommerce cart object, then loop through each item and check
 * if the parent category of that item matches the specified category; if so
 * add the product's value to the running total.
 *
 * Returns a float value.  You must convert to a currency format afterwards.
 *
 * @param int $category_id : The ID of the product
 *
 *
 * @return float $category_total : The total value of matched cart items in float format
 */
  private function get_cart_total_of_category( $category_id ) {

    global $woocommerce;
    $cart = $woocommerce->cart->get_cart();
    $category_total = 0;
    foreach($cart as $key => $cart_item) {
      if (sbs_get_product_parent_category( $cart_item['product_id'] )->term_id === $category_id)
        $category_total += $cart_item['line_total'];
    }
    return $category_total;

  }

/**
 * This is a array_map callback invoked by $this->widget().
 * Maps the categories array returned by get_option('step_order') to a format
 * required by the widget, so that names and total prices can be displayed.
 *
 *
 * @param int $category : An object containing a category id.  Provided by
 *                        get_option('step_order')
 *
 *
 * @return array
 *           string 'cat_name'  : The name of the product category
 *           string 'cat_total' : The total value of cart items of the category,
 *                              in currency format
 *           string 'css_class' : A string that will be assigned as the CSS class
 *                              of the containing element
 */
  private function map_categories_to_widget_array_callback( $category ) {

    sbs_get_all_wc_categories();

    add_filter( 'wc_price', array( $this, 'filter_wc_price_add_span_tag' ), 10, 3 );

    return array(
      'cat_name' => get_the_category_by_ID( $category->catid ),
      'cat_total' => wc_price( $this->get_cart_total_of_category( $category->catid ) ),
      'css_class' => 'sbs-widget-sidebar-category'
    );

  }

  public function render_change_package_link($package_type, $county) {
    $base_url = '';
    switch($package_type) {
      case 'pp':
        $base_url = '/pre-plan-cremation';
        break;
      case 'dho':
        $base_url = '/a-death-has-occurred';
        break;
    }
    $county_suffix = '';
    switch($county) {
      case 'Los Angeles':
        $county_suffix = '-la';
        break;
      case 'Ventura':
        $county_suffix = '-vc';
        break;
      case 'San Bernardino':
        $county_suffix = '-sbc';
        break;
      case 'San Diego':
        $county_suffix = '-sd';
        break;
      case 'Riverside':
        $county_suffix = '-ri';
        break;
      case 'Orange':
        $county_suffix = '-oc';
        break;
      case 'Santa Barbara':
        $county_suffix = '-sb';
        break;

      default:
        $county_suffix = '-la';
        break;
    }

    return '
      <span id="change-pkg-btn">
        <a href="' . $base_url . $county_suffix . '/">
          <small>Change Package</small>
        </a>
      </span>
    ';
  }

  public function form( $instance ) {
    $title = ! empty( $instance['title'] ) ? $instance['title'] : ''; ?>
    <p>
      <label for="<?php echo $this->get_field_id( 'title' ); ?>">Title:</label>
      <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo esc_attr( $title ); ?>" />
    </p>
    <?php
  }

  public function update( $new_instance, $old_instance ) {
    $instance = $old_instance;
    $instance[ 'title' ] = strip_tags( $new_instance[ 'title' ] );
    return $instance;
  }
}

function sbs_register_wc_cart_totals_widget() {
  register_widget( 'sbs_wc_cart_totals' );
}
add_action( 'widgets_init', 'sbs_register_wc_cart_totals_widget' );
