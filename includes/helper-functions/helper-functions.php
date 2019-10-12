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

	return (object) array( 'term_id' => 0 );

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
		'product_cat' => get_term_by('id', $category_id, 'product_cat')->slug,
		'posts_per_page' => -1
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

/**
 * Get a list of packages to be displayed on the package selection page
 *
 *	@param bool $slice (default false)
 *           If true return only the first n package if no license is active where
 *           n is the maximum number of steps allowed.
 *
 */

function sbs_get_active_packages( $slice = false ) {

	$package_order = isset( get_option('sbs_package')['active'] ) ? get_option('sbs_package')['active'] : null;

	if ( empty( $package_order ) ) return null;

	$package_order = json_decode( $package_order );
	$package_order = $package_order[0];

	// Filter out deleted products
	$package_order = array_filter( $package_order, function( $package ) {
		return wc_get_product( $package->catid );
	} );
	$package_order = array_values( $package_order );

	if ( $slice ) {
		$package_order = array_slice( $package_order, 0, 1 );
	}

	return $package_order;

}

/**
 *	Check if the package selection page is active
 *	@return bool
 */

function sbs_is_package_section_active() {

	$packages = sbs_get_active_packages();
	$enabled = isset( get_option('sbs_package')['enabled'] ) ? get_option('sbs_package')['enabled'] : '1';

	return !empty( $packages ) && $enabled === '1';

}

/**
 *	Get the main body step-by-step order, comprised of product categories to be
 *	shown between package selection and checkout.
 *
 *	@param bool $slice (default false)
 *           If true return only the first n steps if no license is active where
 *           n is the maximum number of steps allowed.
 *
 *	@return Array of Step objects
 *			(string) category_id of parent
 *      (array) category_id of each child
 */

function sbs_get_step_order( $slice = false ) {
	$step_order = get_option('step_order');

	if ( empty( $step_order ) ) {
		return;
	}

	$step_order = json_decode( $step_order );

	// Clean up this array because the nesting library did some weird stuff when serializing
	$step_order = $step_order[0];
	foreach( $step_order as $step ) {
		$step->children = $step->children[0];
	}

	// Filter out any deleted categories
	$step_order = array_filter( $step_order, function( $step ) {
		return term_exists( $step->catid, 'product_cat' );
	});
	foreach( $step_order as $step ) {
		if ( isset( $step->children ) ) {
			$step->children = array_filter( $step->children, function( $child ) {
				return term_exists( $child->catid, 'product_cat' );
			});
		}
	}

	if ( $slice ) {
		$step_order = array_slice( $step_order, 0, 2 );
	}

	return $step_order;
}


/**
 *	Get the complete step-by-step order, from package selection to checkout.
 *
 *	@param bool $slice (default false)
 *           If true return only the first n steps if no license is active where
 *           n is the maximum number of steps allowed.
 *
 *	@return Array of Step objects
 *			(string) name
 *			(string) category_id
 *			(string) type, Identifies the step type so the step-by-step shortcode knows how to correctly render that step.
 */

function sbs_get_full_step_order( $slice = false ) {

	$steps = sbs_get_step_order( $slice );

	if ( empty( $steps ) ) {
		return;
	}

	foreach( $steps as $step ) {
		$step->name = get_term( $step->catid )->name;
		$step->type = 'main';
	}
	$steps_package = new stdClass();

	//if ( isset( get_option('sbs_package')['category'] ) ) {
	$steps_package->name = __('Packages', 'wc-step-by-step-ordering');
	$steps_package->catid = get_option('sbs_package')['category'];
	$steps_package->type = 'package';
	//}

	$steps_checkout = new stdClass();
	$steps_checkout->name = __('Checkout', 'wc-step-by-step-ordering');
	$steps_checkout->type = 'checkout';

	array_unshift( $steps, $steps_package );
	array_push( $steps, $steps_checkout );

	return $steps;

}

/**
 * Checks if the current page is an SBS Page, which is either the Choose Package
 * or the Ordering page.
 *
 * @return bool
 */
function is_sbs() {
	$package_page = get_option('sbs_package')['page-name'];
	$main_pages = get_option('sbs_general')['page-name'];
	$sbs_pages = array(
		$package_page,
		$main_pages
	);

	foreach( $sbs_pages as $sbs_page ) {
		if ( is_page( $sbs_page ) ) {
			return true;
		}
	}
	return false;
}

function sbs_get_begin_url() {

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;
	$package_page = isset( get_option('sbs_package')['page-name'] ) ? get_option('sbs_package')['page-name'] : get_page_by_title( 'Choose Package' )->ID;

	$sbs_base_url = get_permalink( $sbs_page );
	$package_base_url = get_permalink( $package_page );

	return sbs_is_package_section_active() ? $package_base_url : $sbs_base_url . '?step=1';

}
