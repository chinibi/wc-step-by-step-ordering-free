<?php

switch( get_option('sbs_display')['color-scheme'] ) {

  case 1: // No preset theme, unstyled
    break;
  case 2: // Green 1 "Spring Green"
    wp_enqueue_style( 'sbs-theme-color-green-1', plugins_url( '/css/frontend/themes/sbs-theme-green-1.css', dirname( __FILE__, 2 ) ), array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' )  );
    break;
  case 3: // Green 1 "Aqua Green"
    wp_enqueue_style( 'sbs-theme-color-green-2', plugins_url( '/css/frontend/themes/sbs-theme-green-2.css', dirname( __FILE__, 2 ) ), array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' )  );
    break;
  case 4: // Autumn 1 "Autumn 1"
    wp_enqueue_style( 'sbs-theme-color-autumn-1', plugins_url( '/css/frontend/themes/sbs-theme-autumn-1.css', dirname( __FILE__, 2 ) ), array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' )  );
    break;
  case 5: // Autumn 2 "Autumn 2"
    wp_enqueue_style( 'sbs-theme-color-autumn-2', plugins_url( '/css/frontend/themes/sbs-theme-autumn-2.css', dirname( __FILE__, 2 ) ), array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' )  );
  default:
    break;

}

switch( get_option('sbs_display')['navbar-style'] ) {

  case 1: // Default shape (Square)
    break;
  case 2: // Circles
    wp_enqueue_style( 'sbs-nav-theme-circle', plugins_url( '/css/frontend/themes/sbs-nav-theme-circle.css', dirname( __FILE__, 2 ) ), array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' ));
    break;
  default:
    break;
    
}
