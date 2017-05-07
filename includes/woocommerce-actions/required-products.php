<?php

/**
 *  Products can be marked as 'required'. Customers cannot checkout if all
 *  required products are not present in the cart.
 *
 *  Required products have the 'Required' attribute with value 'Required'.
 *
 *  Customers will be redirected to the latest valid step if they attempt
 *  to navigate further without having added required products along the way.
 *
 */


/**
 *  Get all required products of specified categories
 *  @param int|string Category ID or comma-separated list of IDs
 *
 */
function sbs_req_get_required_products( $categories ) {

	$args = array(
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'pa_required',
				'field' => 'slug',
				'terms' => 'required'
			),
      array(
        'taxonomy' => 'product_cat',
        'field' => 'term_id',
        'terms' => $categories
      )
		)
	);

	$posts = get_posts( $args );

	$results = array_map( function( $post ) {
		return wc_get_product( $post->ID );
	}, $posts);

	return $results;

}


function sbs_req_get_optional_products( $categories ) {

	$args = array(
		'post_type' => 'product',
		'tax_query' => array(
			array(
				'taxonomy' => 'pa_required',
				'field' => 'slug',
				'terms' => 'required',
				'operator' => 'NOT IN'
			),
			array(
				'taxonomy' => 'product_cat',
				'field' => 'term_id',
				'terms' => $categories
			)
		)
	);

	$posts = get_posts( $args );

	$results = array_map( function( $post ) {
		return wc_get_product( $post->ID );
	}, $posts);

	return $results;

}


function sbs_req_are_required_products_in_cart( $categories ) {

  $products = sbs_get_required_products( $categories );

	if ( !empty( $products ) ) {
		foreach( $products as $product ) {
			if ( !sbs_get_cart_key( $product->get_id() ) )
				return false;
		}
	}

  return true;

}


// @param int $step_number
function sbs_req_required_products_requirement_met_so_far() {

  if ( !isset( $_GET['step'] ) ) return;

  $steps = sbs_get_full_step_order();

  $current_step = array_key_exists( $_GET['step'], $steps ) ? (int) $_GET['step'] : 0;

  $subcats_so_far = array();

  for ( $i=0; $i < $current_step; $i++ ) {

    if ( isset( $steps[$i]->catid ) )
      $subcats_so_far[] = (int) $steps[$i]->catid;

  }

  $required_products = sbs_req_get_required_products( $subcats_so_far );

  $success = true;
  if ( !empty( $required_products ) ) {
		foreach( $required_products as $product ) {

			if ( !sbs_get_cart_key( $product->get_id() ) ) {

        $success = false;
        $earliest_cat_failed = isset( $earliest_cat_failed ) ? $earliest_cat_failed : sbs_get_product_parent_category( $product->get_id() )->term_id;
        foreach( $steps as $key => $step ) {
          if ( $step->catid == $earliest_cat_failed ) {
            $earliest_step_failed = (string) $key;
            break;
          }
        }

      }

		}
	}

  if ( !$success ) {
    wc_add_notice( 'You must add required items to your cart before proceeding', 'error' );
    wp_redirect( get_permalink( get_the_ID() ) . '?step=' . $earliest_step_failed );
    exit;
  }

}

function sbs_req_all_required_products_in_cart() {

	$steps = sbs_get_full_step_order();

	$current_step = count( $steps ) - 1;

	$subcats_so_far = array();

	for ( $i=0; $i < $current_step; $i++ ) {

		if ( isset( $steps[$i]->catid ) )
			$subcats_so_far[] = (int) $steps[$i]->catid;

	}

	$required_products = sbs_req_get_required_products( $subcats_so_far );

	$success = true;
	if ( !empty( $required_products ) ):
		foreach( $required_products as $product ):

			if ( !sbs_get_cart_key( $product->get_id() ) ):

				$success = false;
				$earliest_cat_failed = isset( $earliest_cat_failed ) ? $earliest_cat_failed : sbs_get_product_parent_category( $product->get_id() )->term_id;
				foreach( $steps as $key => $step ) {
					if ( $step->catid == $earliest_cat_failed ) {
						$earliest_step_failed = (string) $key;
						break;
					}
				}

			endif;

		endforeach;
	endif;

	if ( !$success ):

		$base_url = get_permalink( isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID );
		$redirect_url = $base_url . '?step=' . $earliest_step_failed;

		wc_add_notice(
			sprintf('<strong>You must add required products to the cart before you can checkout.  <a href="%s">Click here</a> to pick required products.',
				esc_url( $redirect_url )
			),
			'error'
		);

	endif;

}

add_action( 'wp_loaded', 'sbs_req_required_products_requirement_met_so_far' );
add_action( 'woocommerce_check_cart_items', 'sbs_req_all_required_products_in_cart', 10 );
