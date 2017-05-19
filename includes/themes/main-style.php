<?php

function sbs_theme_or_template_is( $theme_name, $template_name ) {

  return wp_get_theme()->get('Name') == $theme_name || wp_get_theme()->get('Template') == $template_name;

}

if ( sbs_theme_or_template_is('Twenty Seventeen', 'twentyseventeen') ) {
  wp_enqueue_style( 'sbs-style', plugins_url( '/wc-step-by-step-ordering/css/frontend/sbs-style.css' ), array( 'woocommerce-twenty-seventeen', 'woocommerce-layout', 'woocommerce-smallscreen' ) );
}
else {
  wp_enqueue_style( 'sbs-style', plugins_url( '/css/frontend/sbs-style.css', __FILE__ ), array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' ) );
}
