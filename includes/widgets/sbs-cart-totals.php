<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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

    $categories = sbs_get_step_order();

    $totals = array_map( array( $this, 'map_categories_to_widget_array_callback' ), $categories );

    // Prepend Package to $totals
    $package = sbs_get_package_from_cart();

    if ( isset( $package ) ) {
      array_unshift( $totals, array(
        'cat_name' => $package['item']['data']->get_name() . '<br /><a class="sbs-change-package-btn" href="' . get_permalink( get_the_ID() ) . '">Change Package</a>',
        'cat_total' => wc_price( $package['item']['line_total'] ),
        'css_class' => 'sbs-widget-sidebar-package'
      ) );
    }

    // Append Sales Tax, Merchandise Credit, and Grand Total to $totals
    // You must call the calculate_fees() function since by default taxes are
    // calculated only on checkout
    $woocommerce->cart->calculate_fees();

		if ( !isset( get_option('sbs_onf')['disabled'] ) && isset( get_option('sbs_onf')['category'] ) ) {

			$totals[] = array(
				'cat_name' => get_the_category_by_ID( get_option('sbs_onf')['category'] ),
				'cat_total' => wc_price( sbs_get_cart_total_of_category( get_option('sbs_onf')['category'] ) ),
				'css_class' => 'sbs-widget-sidebar-category'
			);

		}

		$totals[] = array(
			'cat_name' => 'SUBTOTAL',
			'cat_total' => wc_price( $woocommerce->cart->subtotal - $woocommerce->cart->get_taxes_total() ),
			'css_class' => 'sbs-widget-sidebar-subtotal'
		);

    $totals[] = array(
      'cat_name' => 'Sales Tax',
      'cat_total' => wc_price( $woocommerce->cart->get_taxes_total() ),
      'css_class' => 'sbs-widget-sidebar-category'
    );

    if ( isset( $package['credit'] ) ) {
      $totals[] = array(
        'cat_name' => 'Merchandise Credit',
        'cat_total' => wc_price( $package['credit'] ),
        'css_class' => 'sbs-widget-sidebar-merch-credit'
      );
    }

    $totals[] = array(
      'cat_name' => 'GRAND TOTAL',
      'cat_total' => 	wc_price( max( 0, apply_filters( 'woocommerce_calculated_total', round( $woocommerce->cart->cart_contents_total + $woocommerce->cart->tax_total + $woocommerce->cart->shipping_tax_total + $woocommerce->cart->shipping_total + $woocommerce->cart->fee_total, $woocommerce->cart->dp ), $woocommerce->cart ) ) ),
      'css_class' => 'sbs-widget-sidebar-grand-total'
    );

		// Generate Previous/Next Step Buttons
		$current_step = isset( $_GET['step'] ) && is_numeric( $_GET['step'] ) ? (int) $_GET['step'] : 0;

	  $all_categories = sbs_get_all_wc_categories();

	  // Generate the Steps array
	  $steps = sbs_get_step_order();
	  foreach( $steps as $step ) {
	    $step->name = get_the_category_by_ID( $step->catid );
	  }
	  $steps_package = new stdClass();
	  $steps_package->name = 'Packages';
	  $steps_checkout = new stdClass();
	  $steps_checkout->name = 'Checkout';
	  array_unshift( $steps, $steps_package );

		if ( !isset( get_option('sbs_onf')['disabled'] ) || get_option('sbs_onf')['disabled'] != 1 ) {

			$steps_onf = new stdClass();
			$steps_onf->name = get_the_category_by_ID( get_option('sbs_onf')['category'] );
			array_push( $steps, $steps_onf );

		}

	  array_push( $steps, $steps_checkout );

	  // Default to step 0 if an invalid step was requested
	  if ( !array_key_exists( $current_step, $steps ) ) {
	    $current_step = 0;
	  }

    ?>
    <table id="sbs-widget-sidebar-cart-totals">

      <?php
      foreach($totals as $cat_info)
      {
      ?>
        <tr class="<?php echo esc_attr( $cat_info['css_class'] ) ?>">
          <td class="sbs-widget-sidebar-cat-name">
            <strong><?php echo $cat_info['cat_name'] ?></strong>
          </td>
          <td data-cat="<?php echo esc_attr( $cat_info['cat_name'] ) ?>" class="sbs-widget-sidebar-total-column">
            <?php echo $cat_info['cat_total'] ?>
          </td>
        </tr>
      <?php
      }
      ?>

    </table>

		<div id="sbs-widget-sidebar-back-forward-buttons-container">
	    <div class="sbs-store-back-forward-buttons">
	      <?php echo sbs_previous_step_button( $current_step, count($steps) ) ?>
	    </div>
	    <div class="sbs-store-back-forward-buttons">
	      <?php echo sbs_next_step_button( $current_step, count($steps) ) ?>
	    </div>
	  </div>

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
      'cat_total' => wc_price( sbs_get_cart_total_of_category( $category->catid ) ),
      'css_class' => 'sbs-widget-sidebar-category'
    );

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
