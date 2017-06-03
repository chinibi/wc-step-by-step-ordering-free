<?php

/**
 * Set dependencies for main CSS file depending on current theme.
 *
 */

$frontend_style_dir = dirname( dirname( dirname( __FILE__ ) ) ) . '/css/frontend/';

function sbs_theme_or_template_is( $theme_name, $template_name ) {

  return wp_get_theme()->get('Name') == $theme_name || wp_get_theme()->get('Template') == $template_name;

}

do_action( 'sbs_enqueue_main_style' );

if ( sbs_theme_or_template_is('Twenty Seventeen', 'twentyseventeen') ) {
  wp_enqueue_style( 'sbs-style', plugins_url( '/wc-step-by-step-ordering/css/frontend/sbs-style.css' ), array( 'woocommerce-twenty-seventeen', 'woocommerce-layout', 'woocommerce-smallscreen' ), filemtime( $frontend_style_dir . 'sbs-style.css' ) );
}
elseif ( sbs_theme_or_template_is('Storefront', 'storefront') ) {
  wp_enqueue_style( 'sbs-style', plugins_url( '/wc-step-by-step-ordering/css/frontend/sbs-style.css' ), array( 'storefront-style', 'storefront-fonts', 'storefront-woocommerce-style' ), filemtime( $frontend_style_dir . 'sbs-style.css' ) );
}
else {
  wp_enqueue_style( 'sbs-style', plugins_url( '/wc-step-by-step-ordering/css/frontend/sbs-style.css' ), array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' ), filemtime( $frontend_style_dir . 'sbs-style.css' ) );
}
