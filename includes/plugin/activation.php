<?php

/**
 *  Step-By-Step Plugin Activation Script
 */
update_option( 'sbs_version', $this->version );

// Create the Main Step-By-Step Page
if ( !post_exists( 'Step-By-Step Ordering' ) ) {
	$page_data = array(
		'post_status' => 'publish',
		'post_type'   => 'page',
		'post_author' => 1,
		'post_name'   => 'ordering',
		'post_title'  => 'Step-By-Step Ordering',
		'comment_status' => 'closed',
		'post_content'     => '[sbs_woocommerce_step_by_step_ordering]'
	);

	wp_insert_post( $page_data );
}

// Create the Choose Package Page
if ( !post_exists( 'Choose Package' ) ) {
	$page_data = array(
		'post_status' => 'publish',
		'post_type'   => 'page',
		'post_author' => 1,
		'post_name'   => 'choose-package',
		'post_title'  => 'Choose Package',
		'comment_status' => 'closed',
		'post_content'     => '[sbs_select_package]'
	);

	wp_insert_post( $page_data );
}

// Create default categories for Packages and OnF pages
if ( get_option('sbs_package') === false && !term_exists( 'Packages', 'product_cat' ) ) {
	$package_cat_id = wp_insert_term(
		'Packages',
		'product_cat',
		array(
			'description' => 'Begin your ordering process by choosing a package. You may choose one per order.',
			'slug' => 'packages'
		)
	);
}

/**
 * Set default settings if none exist
 *
 */
if ( get_option('sbs_general') === false ) {
	$sbs_general_defaults = array(
		'page-name'               => get_page_by_title( 'Step-By-Step Ordering' )->ID,
		'ui-outside-sbs'          => 'no',
		'hide-placeholder-images' => 'no',
		'featured-items-position' => '2',
		'featured-label'          => 'Featured Items',
		'req-label-before'        => 'Select',
		'req-label-after'         => '(Required)',
		'opt-label-before'        => '',
		'opt-label-after'         => '(Addons)'
	);
	add_option( 'sbs_general', $sbs_general_defaults );
}

if ( get_option('step_order') === false ) {
	add_option( 'step_order', '' );
}

if ( get_option('sbs_navbar') === false ) {
	$sbs_navbar_defaults = array(
		'throttle-nav' => '2'
	);
	add_option( 'sbs_navbar', $sbs_navbar_defaults );
}

if ( get_option('sbs_package') === false ) {
	$sbs_package_defaults = array(
		'enabled'          => '1',
		'label'            => 'Step-By-Step Ordering',
		'page-name'        => get_page_by_title( 'Choose Package' )->ID,
		'category'         => '',
		'active'           => '',
		'clear-cart'       => '1',
		'per-row'          => '1',
		'add-to-cart-text' => 'Select Package',
		'image-height'     => '',
		'image-width'      => ''
	);

	if ( isset( $package_cat_id ) ) {
		$sbs_package_defaults['category'] = $package_cat_id['term_id'];
	}
	add_option( 'sbs_package', $sbs_package_defaults );
}

if ( get_option('sbs_onf') === false ) {
	$sbs_onf_defaults = array(
		'category' => '',
		'order' => ''
	);

	add_option( 'sbs_onf', $sbs_onf_defaults );
}
