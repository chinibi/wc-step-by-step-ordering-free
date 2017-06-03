<?php

$common_themes_dir = dirname( dirname( dirname( __FILE__ ) ) ) . '/css/frontend/common-themes/';

if ( wp_get_theme()->get('Name') == 'Twenty Sixteen' || wp_get_theme()->get('Template') == 'twentysixteen' ) {

  wp_enqueue_style( 'sbs-twentysixteen', plugins_url('/wc-step-by-step-ordering/css/frontend/common-themes/twentysixteen.css'), array( 'sbs-style', 'twentysixteen-fonts', 'twentysixteen-style', 'twentysixteen-ie', 'twentysixteen-ie8', 'twentysixteen-ie7' ) );

}

if (wp_get_theme()->get('Name') == 'Twenty Seventeen' || wp_get_theme()->get('Template') == 'twentyseventeen') {

  wp_enqueue_style( 'sbs-twentyseventeen', plugins_url('/wc-step-by-step-ordering/css/frontend/common-themes/twentyseventeen.css'), array( 'sbs-style' ), filemtime( $common_themes_dir . 'twentyseventeen.css' ) );

}

if ( wp_get_theme()->get('Name') == 'Storefront' || wp_get_theme()->get('Template') == 'storefront' ) {

  wp_enqueue_style( 'sbs-storefront', plugins_url('/wc-step-by-step-ordering/css/frontend/common-themes/storefront.css'), array( 'sbs-style' ), filemtime( $common_themes_dir . 'storefront.css' ) );

}

if ( wp_get_theme()->get('Name') == 'Divi' || wp_get_theme()->get('Template') == 'Divi' ) {

  wp_enqueue_style( 'sbs-divi', plugins_url( '/wc-step-by-step-ordering/css/frontend/common-themes/divi.css' ), array( 'sbs-style', 'divi-fonts', 'divi-style', 'et-shortcodes-css', 'et-shortcodes-responsive-css' ), filemtime( $common_themes_dir . 'divi.css' ) );

}
