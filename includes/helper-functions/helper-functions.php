<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Gets the parent category of a specified product.
 *
 * Get all WooCommerce product categories for the specified product, then looks
 * through the parent property of each of them. A parent property with value 0
 * means the category is top-level.
 *
 *
 * @param int $product_id
 *
 *
 * @return WC_Category object $category
 */

function sbs_get_product_parent_category( $product_id ) {

  $categories = wp_get_post_terms($product_id, 'product_cat');

  foreach ($categories as $category) {
    if ($category->parent === 0)
      return $category;
  }

}

/**
 * Gets the parent category of a specified product.
 *
 * Get all WooCommerce product categories for the specified product, then looks
 * through the parent property of each of them. A parent property with value 0
 * means the category is top-level.
 *
 *
 * @param int $product_id
 *
 *
 * @return WC_Category object $category
 */



/**
 * Gets all WooCommerce product-type posts of a specified category
 *
 *
 * @param int $category_id
 *
 * @return array of WP_Post objects $products
 */

function sbs_get_wc_products_by_category( $category_id ) {

  sbs_get_all_wc_categories();

  $args = array(
    'post_type' => 'product',
    'product_cat' => get_term_by('id', $category_id, 'product_cat')->slug
  );

  $products = get_posts( $args );

  return $products;

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

function sbs_get_cart_total_of_category( $category_id ) {

	global $woocommerce;
	$cart = $woocommerce->cart->get_cart();
	$category_total = 0;

	foreach($cart as $key => $cart_item) {
		if (sbs_get_product_parent_category( $cart_item['product_id'] )->term_id === $category_id)
			$category_total += $cart_item['line_total'];
	}

	return $category_total;

}

// Get the amount of store credit currently applied
function sbs_get_merchandise_credit_to_apply() {

	global $woocommerce;
	$cart = $woocommerce->cart->get_cart();
	// Get total value of all items in cart, except the package
	$package = sbs_get_package_from_cart();

	if ( isset( $package ) && isset( $package['credit'] ) ) {

		$cart_total = $woocommerce->cart->cart_contents_total - $package['item']['line_total'];

		// The amount of credit applied caps at some specified value.
		// It should be negative since we are adding a negative fee to the total
		$credit = min( $package['credit'], $cart_total );

	}

	return isset( $credit ) ? $credit : false;

}

/**
 *	Retrieve the selected package from the cart
 *
 *	@return array
 *						item => WooCommerce Cart item
 *						credit => float Merchandise Credit assigned to package
 */

function sbs_get_package_from_cart() {

	global $woocommerce;
	$result = array();
	$package_cat_id = (int) get_option('sbs_package')['category'];
	$cart = $woocommerce->cart->get_cart();

	foreach ( $cart as $key => $item ) {

		$product_parent = sbs_get_product_parent_category( $item['product_id'] )->term_id;
		if ( $product_parent === $package_cat_id ) {

			$merch_credit = get_post_meta( $item['product_id'], '_merch_credit', true );

			if ( !empty( $merch_credit ) && is_numeric( $merch_credit ) && floatval( $merch_credit ) > 0 ) {
				$merch_credit = floatval( $merch_credit );
			}

			return array(
				'key' => $key,
				'item' => $item,
				'credit' => isset( $merch_credit ) ? $merch_credit : null
			);

		}

	}

}

/**
 *	Check if the specified item is in the cart
 *
 *	@param int $product_id
 *
 *	@return string $key || bool false
 *
 */

function sbs_get_cart_key( $product_id ) {
  global $woocommerce;
  $cart = $woocommerce->cart->get_cart();

  foreach( $cart as $key => $cart_item ) {
    if ( $product_id === $cart_item['product_id'] )
      return array( 'key' => $key, 'cart_item' => $cart_item );
  }

  return false;
}


/**
 *	Get a list of all WooCommerce product categories
 *
 *	@return array WC_Category
 *
 */

function sbs_get_all_wc_categories() {

  $taxonomy     = 'product_cat';
  $orderby      = 'name';
  $show_count   = 0;      // 1 for yes, 0 for no
  $pad_counts   = 0;      // 1 for yes, 0 for no
  $hierarchical = 1;      // 1 for yes, 0 for no
  $title        = '';
  $empty        = 0;

  $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
  );
  $all_categories = get_categories( $args );

  return $all_categories;

}


/**
 *	Get a list of all product subcategories of a specified parent category
 *
 *	@param int $parent_id Parent Category ID
 *
 *	@return array WC_Category
 *
 */

function sbs_get_subcategories_from_parent( $parent_id ) {

	$args = array(
		'hierarchical' => 1,
		'show_option_none' => '',
		'hide_empty' => 0,
		'parent' => $parent_id,
		'taxonomy' => 'product_cat'
	);

	$subcats = get_categories( $args );

	return $subcats;

}

function sbs_get_full_step_order() {

  $steps = sbs_get_step_order();
  foreach( $steps as $step ) {
    $step->name = get_the_category_by_ID( $step->catid );
		$step->type = 'main';
  }
  $steps_package = new stdClass();
  $steps_package->name = 'Packages';
	$steps_package->catid = get_option('sbs_package')['category'];
	$steps_package->type = 'package';
  $steps_checkout = new stdClass();
  $steps_checkout->name = 'Checkout';
	$steps_checkout->type = 'checkout';
  array_unshift( $steps, $steps_package );

  if ( !isset( get_option('sbs_onf')['disabled'] ) || get_option('sbs_onf')['disabled'] != 1 ) {

    $steps_onf = new stdClass();
    $steps_onf->name = get_the_category_by_ID( get_option('sbs_onf')['category'] );
		$steps_onf->catid = (string) get_option('sbs_onf')['category'];
		$steps_onf->type = 'options';
    array_push( $steps, $steps_onf );

  }

  array_push( $steps, $steps_checkout );

  return $steps;

}
