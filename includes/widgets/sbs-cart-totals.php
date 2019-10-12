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

		// Generate the Steps array
		$steps = sbs_get_step_order( true );
		$all_steps = sbs_get_full_step_order( true );

		$step_by_step_page = (int) get_option('sbs_general')['page-name'];

		// render only on the Step-By-Step Ordering page
		if ( !is_page( $step_by_step_page ) || empty( $steps ) ) {
			return;
		}

		// get woocommerce properties and methods
		global $woocommerce;

		// TODO: Refactor for use with sbs_get_full_step_order() instead.

		if ( !empty( $steps ) ) {
			$totals = array_map( array( $this, 'map_categories_to_widget_array_callback' ), $steps );
		}

		// Prepend Package to $totals
		$package = sbs_get_package_from_cart();
		$package_page = isset( get_option('sbs_package')['page-name'] ) ? get_option('sbs_package')['page-name'] : get_page_by_title( 'Choose Package' )->ID;
		$package_enabled = sbs_is_package_section_active();

		if ( isset( $package ) && $package_enabled ) {
			array_unshift( $totals, array(
				'cat_name' => $package['item']['data']->get_name() . '<br /><a class="sbs-change-package-btn" href="' . get_permalink($package_page) . '">' . __('Change Package', 'wc-step-by-step-ordering') . '</a>',
				'cat_total' => wc_price( $package['item']['line_total'] ),
				'css_class' => 'sbs-widget-sidebar-package'
				) );
			}

		// Append Sales Tax, Merchandise Credit, and Grand Total to $totals
		// You must call the calculate_fees() function since by default taxes are
		// calculated only on checkout
		$woocommerce->cart->calculate_fees();

		$totals[] = array(
			'cat_name' => __('SUBTOTAL', 'wc-step-by-step-ordering'),
			'cat_total' => wc_price( $woocommerce->cart->subtotal - $woocommerce->cart->get_taxes_total() ),
			'css_class' => 'sbs-widget-sidebar-subtotal'
		);

		$totals[] = array(
			'cat_name' => __('Sales Tax', 'wc-step-by-step-ordering'),
			'cat_total' => wc_price( $woocommerce->cart->get_taxes_total() ),
			'css_class' => 'sbs-widget-sidebar-category'
		);

		$totals[] = array(
			'cat_name' => __('TOTAL', 'wc-step-by-step-ordering'),
			'cat_total' => 	wc_price( max( 0, apply_filters( 'woocommerce_calculated_total', round( $woocommerce->cart->cart_contents_total + $woocommerce->cart->tax_total + $woocommerce->cart->shipping_tax_total + $woocommerce->cart->shipping_total + $woocommerce->cart->fee_total, $woocommerce->cart->dp ), $woocommerce->cart ) ) ),
			'css_class' => 'sbs-widget-sidebar-grand-total'
		);

		// Generate Previous/Next Step Buttons
		$current_step = isset( $_GET['step'] ) && is_numeric( $_GET['step'] ) ? (int) $_GET['step'] : 1;

		?>
		<table id="sbs-widget-sidebar-cart-totals">
			<?php
			$calc_title = isset( get_option('sbs_package')['label'] ) ? get_option('sbs_package')['label'] : 'Step-By-Step Ordering';
			if ( !sbs_is_package_section_active() ) {
			?>
				<tr class="sbs-widget-sidebar-package">
					<td colspan="2" class="sbs-widget-sidebar-cat-name center-text">
						<strong><?php echo esc_html( $calc_title ) ?></strong>
					</td>
				</tr>
			<?php
			}
			?>
			<?php
			foreach($totals as $key => $cat_info):
				?>
				<tr class="<?php echo esc_attr( $cat_info['css_class'] ) ?>">
					<td
						colspan="<?php echo isset( $cat_info['cat_name_colspan'] ) ? esc_attr( $cat_info['cat_name_colspan'] ) : null ?>"
						class="sbs-widget-sidebar-cat-name"
						style="<?php echo isset( $cat_info['cat_name_style'] ) ? $cat_info['cat_name_style'] : null ?>"
						>
						<strong>
							<?php echo isset( $cat_info['cat_name'] ) ? $cat_info['cat_name'] : null ?>
						</strong>
					</td>
					<td
						colspan="<?php echo isset( $cat_info['cat_total_colspan'] ) ? esc_attr( $cat_info['cat_total_colspan'] ) : null ?>"
						data-cat="<?php echo esc_attr( $cat_info['cat_name'] ) ?>" class="sbs-widget-sidebar-total-column"
						style="<?php echo isset( $cat_info['cat_total_style'] ) ? $cat_info['cat_total_style'] : null ?>"
						>
							<?php echo isset( $cat_info['cat_total'] ) ? $cat_info['cat_total'] : null ?>
					</td>
				</tr>
			<?php
			endforeach;
			?>

		</table>

		<div id="sbs-widget-sidebar-back-forward-buttons-container">
			<?php echo sbs_previous_step_button( $current_step, count($all_steps) ) ?>
			<?php echo sbs_next_step_button( $current_step, count($all_steps) ) ?>
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

		$steps = sbs_get_full_step_order();

		foreach( $steps as $key => $step ) {
			if ( empty( $step->catid ) ) continue;
			if ( $category->catid == $step->catid ) {
				$step_number = "${key}. ";
				break;
			}
		}

		return array(
			'cat_name' => $step_number . get_term( $category->catid )->name,
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
