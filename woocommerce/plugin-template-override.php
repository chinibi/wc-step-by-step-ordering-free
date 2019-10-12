<?php

/**
 *    WooCommerce templates can be overridden by placing replacements in the
 *    theme directory.
 *    This function allows template files in plugins to override too.
 *    The new priority order is:
 *    1. your theme / template path / template name
 *    2. your theme / template name
 *    (New) 3. your plugin / template name
 *    4. default path / template name
 *
 *    See https://www.skyverge.com/blog/override-woocommerce-template-file-within-a-plugin/
 *    for original source code.
 */

add_filter( 'woocommerce_locate_template', 'sbs_woocommerce_locate_template', 10, 3 );

function sbs_woocommerce_locate_template( $template, $template_name, $template_path ) {

	global $woocommerce;

	$_template = $template;

	if ( ! $template_path ) $template_path = $woocommerce->template_url;

	$plugin_path = plugin_dir_path( __FILE__ );

	// Look within passed path within the theme - this is priority

	$template = locate_template(
		array(
			$template_path . $template_name,
			$template_name
		)
	);

	// Modification: Get the template from this plugin, if it exists
	if ( ! $template && file_exists( $plugin_path . $template_name ) )
		$template = $plugin_path . $template_name;

	// Use default template
	if ( ! $template )
		$template = $_template;

	// Return what we found
	return $template;

}
