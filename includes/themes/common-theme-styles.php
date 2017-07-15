<?php

$common_themes_dir = 'css/frontend/common-themes/';

if ( wp_get_theme()->get('Name') == 'Twenty Sixteen' || wp_get_theme()->get('Template') == 'twentysixteen' ) {

	wp_enqueue_style(
		'sbs-twentysixteen',
		plugins_url( $common_themes_dir . 'twentysixteen.css', SBS_PLUGIN_FILE ),
		array( 'sbs-style', 'twentysixteen-fonts', 'twentysixteen-style', 'twentysixteen-ie', 'twentysixteen-ie8', 'twentysixteen-ie7' ),
		filemtime( SBS_ABSPATH . $common_themes_dir . 'twentysixteen.css' )
	);

}

if (wp_get_theme()->get('Name') == 'Twenty Seventeen' || wp_get_theme()->get('Template') == 'twentyseventeen') {

	wp_enqueue_style(
		'sbs-twentyseventeen',
		plugins_url( $common_themes_dir . 'twentyseventeen.css', SBS_PLUGIN_FILE ),
		array( 'sbs-style' ),
		filemtime( SBS_ABSPATH . $common_themes_dir . 'twentyseventeen.css' )
	);

}

if ( wp_get_theme()->get('Name') == 'Storefront' || wp_get_theme()->get('Template') == 'storefront' ) {

	wp_enqueue_style(
		'sbs-storefront',
		plugins_url( $common_themes_dir . 'storefront.css', SBS_PLUGIN_FILE ),
		array( 'sbs-style' ),
		filemtime( SBS_ABSPATH . $common_themes_dir . 'storefront.css' )
	);

}

if ( wp_get_theme()->get('Name') == 'Divi' || wp_get_theme()->get('Template') == 'Divi' ) {

	wp_enqueue_style(
		'sbs-divi',
		plugins_url( $common_themes_dir . 'divi.css', SBS_PLUGIN_FILE ),
		array( 'sbs-style', 'divi-fonts', 'divi-style', 'et-shortcodes-css', 'et-shortcodes-responsive-css' ),
		filemtime( SBS_ABSPATH . $common_themes_dir . 'divi.css' )
	);

}

if ( wp_get_theme()->get('Name') == 'Evolution' || wp_get_theme()->get('Template') == 'Evolution' ) {

	wp_enqueue_style(
		'sbs-evolution',
		plugins_url( $common_themes_dir . 'evolution.css', SBS_PLUGIN_FILE ),
		array( 'sbs-style', 'ual-style-css', 'et-gf-', 'et-shortcodes-css', 'et_page_templates' ),
		filemtime( SBS_ABSPATH . $common_themes_dir . 'divi.css' )
	);

}
