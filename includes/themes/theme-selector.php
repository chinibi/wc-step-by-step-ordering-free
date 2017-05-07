<?php

/**
 *
 *  Enqueue optional CSS, selected from admin options
 *
 */

// SBS preset color schemes
switch( get_option('sbs_display')['color-scheme'] ) {

  case 1: // No preset theme, unstyled
    break;
  case 2: // Green 1 "Spring Green"
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-green-1.css' ), array( 'sbs-style' ) );
    break;
  case 3: // Green 1 "Aqua Green"
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-green-2.css' ), array( 'sbs-style' ) );
    break;
  case 4: // Autumn 1 "Autumn 1"
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-autumn-1.css' ), array( 'sbs-style' ) );
    break;
  case 5: // Autumn 2 "Autumn 2"
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-autumn-2.css' ), array( 'sbs-style' ) );
  case 6: // Neon "Neon"
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-neon.css' ), array( 'sbs-style' ) );
  case 7: // Neon Gradient "Neon Gradient"
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-neon-gradient.css' ), array( 'sbs-style' ) );
  case 8: // Grayscale "Noir 1"
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-noir-1.css' ), array( 'sbs-style' ) );
  case 9: // Grayscale "Noir 2"
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-noir-2.css' ), array( 'sbs-style' ) );
  case 10: // Blue "Royal 1"
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-royal-1.css' ), array( 'sbs-style' ) );
  case 11:
    wp_enqueue_style( 'sbs-theme-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-theme-royal-2.css' ), array( 'sbs-style' ) );
  default:
    break;

}

// SBS Navbar Step Number shape
switch( (int) get_option('sbs_display')['navbar-style'] ) {

  case 1: // Default shape (Square)
    break;
  case 2: // Circles
    wp_enqueue_style( 'sbs-nav-step-circle', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-circle-step-no.css' ), array( 'sbs-theme-color' ));
    break;
  case 3: // Downward-pointing Triangles
    wp_enqueue_style( 'sbs-nav-step-triangle-down', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-triangledown-step-no.css' ), array( 'sbs-theme-color' ));
    break;
  case 4: // Upward Pointing Triangles
    wp_enqueue_style( 'sbs-nav-step-triangle-up', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-triangleup-step-no.css' ), array( 'sbs-theme-color' ));
    break;
  case 5: // Hearts
    wp_enqueue_style( 'sbs-nav-step-heart', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-heart-step-no.css' ), array( 'sbs-theme-color' ));
    break;
  case 6: // 12-pointed starburst
    wp_enqueue_style( 'sbs-nav-step-12star', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-12star-step-no.css' ), array( 'sbs-theme-color' ));
    break;
  case 7: // Kite
    wp_enqueue_style( 'sbs-nav-step-kite', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-kite-step-no.css' ), array( 'sbs-theme-color' ));
    break;
  case 8: // Badge Ribbon
    wp_enqueue_style( 'sbs-nav-step-kite', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-ribbon-step-no.css' ), array( 'sbs-theme-color' ));  
  default:
    break;

}

// SBS Calculator Widget font
switch( get_option('sbs_display')['calc-font'] ) {

  case 1: // Default (determined by Wordpress Theme)
    break;
  case 2: // Helvetica
    wp_enqueue_style( 'sbs-calc-theme-helvetica', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/calculator/helvetica.css' ), array( 'sbs-style' ));
    break;
  case 3: // Arial
    wp_enqueue_style( 'sbs-calc-theme-arial', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/calculator/arial.css' ), array( 'sbs-style' ));
    break;
  case 4: // Verdana
    wp_enqueue_style( 'sbs-calc-theme-verdana', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/calculator/verdana.css' ), array( 'sbs-style' ));
    break;
  case 5: // Georgia
    wp_enqueue_style( 'sbs-calc-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/calculator/georgia.css' ), array( 'sbs-style' ));
    break;
  case 6: // Lucida
    wp_enqueue_style( 'sbs-calc-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/calculator/lucida.css' ), array( 'sbs-style' ));
    break;
  case 7: // Palatino
    wp_enqueue_style( 'sbs-calc-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/calculator/palatino.css' ), array( 'sbs-style' ));
    break;
  default:
    break;

}

switch( get_option('sbs_display')['category-font'] ) {

  case 1: // Default (determined by Wordpress Theme)
    break;
  case 2: // Helvetica
    wp_enqueue_style( 'sbs-cat-theme-helvetica', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/categories/helvetica.css' ), array( 'sbs-style' ));
    break;
  case 3: // Arial
    wp_enqueue_style( 'sbs-cat-theme-arial', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/categories/arial.css' ), array( 'sbs-style' ));
    break;
  case 4: // Verdana
    wp_enqueue_style( 'sbs-cat-theme-verdana', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/categories/verdana.css' ), array( 'sbs-style' ));
    break;
  case 5: // Georgia
    wp_enqueue_style( 'sbs-cat-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/categories/georgia.css' ), array( 'sbs-style' ));
    break;
  case 6: // Lucida
    wp_enqueue_style( 'sbs-cat-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/categories/lucida.css' ), array( 'sbs-style' ));
    break;
  case 7: // Palatino
    wp_enqueue_style( 'sbs-cat-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/categories/palatino.css' ), array( 'sbs-style' ));
    break;
  default:
    break;

}

switch( get_option('sbs_display')['category-desc-font'] ) {

  case 1: // Default (determined by Wordpress Theme)
    break;
  case 2: // Helvetica
    wp_enqueue_style( 'sbs-catdesc-theme-helvetica', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/category-descriptions/helvetica.css' ), array( 'sbs-style' ));
    break;
  case 3: // Arial
    wp_enqueue_style( 'sbs-catdesc-theme-arial', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/category-descriptions/arial.css' ), array( 'sbs-style' ));
    break;
  case 4: // Verdana
    wp_enqueue_style( 'sbs-catdesc-theme-verdana', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/category-descriptions/verdana.css' ), array( 'sbs-style' ));
    break;
  case 5: // Georgia
    wp_enqueue_style( 'sbs-catdesc-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/category-descriptions/georgia.css' ), array( 'sbs-style' ));
    break;
  case 6: // Lucida
    wp_enqueue_style( 'sbs-catdesc-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/category-descriptions/lucida.css' ), array( 'sbs-style' ));
    break;
  case 7: // Palatino
    wp_enqueue_style( 'sbs-catdesc-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/category-descriptions/palatino.css' ), array( 'sbs-style' ));
    break;
  default:
    break;

}

switch( get_option('sbs_display')['nav-button-font'] ) {

  case 1: // Default (determined by Wordpress Theme)
    break;
  case 2: // Helvetica
    wp_enqueue_style( 'sbs-navbutton-theme-helvetica', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/nav-buttons/helvetica.css' ), array( 'sbs-style' ));
    break;
  case 3: // Arial
    wp_enqueue_style( 'sbs-navbutton-theme-arial', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/nav-buttons/arial.css' ), array( 'sbs-style' ));
    break;
  case 4: // Verdana
    wp_enqueue_style( 'sbs-navbutton-theme-verdana', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/nav-buttons/verdana.css' ), array( 'sbs-style' ));
    break;
  case 5: // Georgia
    wp_enqueue_style( 'sbs-navbutton-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/nav-buttons/georgia.css' ), array( 'sbs-style' ));
    break;
  case 6: // Lucida
    wp_enqueue_style( 'sbs-navbutton-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/nav-buttons/lucida.css' ), array( 'sbs-style' ));
    break;
  case 7: // Palatino
    wp_enqueue_style( 'sbs-navbutton-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/nav-buttons/palatino.css' ), array( 'sbs-style' ));
    break;
  default:
    break;

}

switch( get_option('sbs_display')['navbar-font'] ) {

  case 1: // Default (determined by Wordpress Theme)
    break;
  case 2: // Helvetica
    wp_enqueue_style( 'sbs-nav-theme-helvetica', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/navbar/helvetica.css' ), array( 'sbs-style' ));
    break;
  case 3: // Arial
    wp_enqueue_style( 'sbs-nav-theme-arial', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/navbar/arial.css' ), array( 'sbs-style' ));
    break;
  case 4: // Verdana
    wp_enqueue_style( 'sbs-nav-theme-verdana', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/navbar/verdana.css' ), array( 'sbs-style' ));
    break;
  case 5: // Georgia
    wp_enqueue_style( 'sbs-nav-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/navbar/georgia.css' ), array( 'sbs-style' ));
    break;
  case 6: // Lucida
    wp_enqueue_style( 'sbs-nav-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/navbar/lucida.css' ), array( 'sbs-style' ));
    break;
  case 7: // Palatino
    wp_enqueue_style( 'sbs-nav-theme-georgia', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/fonts/navbar/palatino.css' ), array( 'sbs-style' ));
    break;
  default:
    break;

}

// Calculator widget column borders
if ( isset( get_option('sbs_display')['calc-borders'] ) ) {
  wp_enqueue_style( 'sbs-calc-theme-withborder', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/sbs-calc-theme-withborder.css' ), array( 'sbs-style' ) );
}


// Anchor tag hover color effect
if ( isset(get_option('sbs_display')['hover-effect'] ) && get_option('sbs_display')['hover-effect'] == '1' ) {

  switch( get_option('sbs_display')['color-scheme'] ) {

    case 1: // No preset theme, unstyled
      break;
    case 2: // Green 1 "Spring Green"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( 'wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-green-1.css' ), array( 'sbs-theme-color' ) );
      break;
    case 3: // Green 1 "Aqua Green"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( 'wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-green-1.css' ), array( 'sbs-theme-color' ) );
      break;
    case 4: // Autumn 1 "Autumn 1"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( 'wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-autumn-1.css' ), array( 'sbs-theme-color' ) );
      break;
    case 5: // Autumn 2 "Autumn 2"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( 'wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-autumn-2.css' ), array( 'sbs-theme-color' ) );
      break;
    case 6: // Neon "Neon"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-neon.css' ), array( 'sbs-theme-color' ) );
      break;
    case 7: // Neon Gradient "Neon Gradient"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-neon.css' ), array( 'sbs-theme-color' ) );
      break;
    case 8: // Grayscale "Noir 1"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-noir-1.css' ), array( 'sbs-theme-color' ) );
      break;
    case 9: // Grayscale "Noir 2"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-noir-2.css' ), array( 'sbs-theme-color' ) );
      break;
    case 10: // Blue "Royal 1"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-royal.css' ), array( 'sbs-theme-color' ) );
      break;
    case 11:
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/color-scheme-hover/sbs-ahover-royal.css' ), array( 'sbs-theme-color' ) );
      break;
    default:
      break;

  }

}

// Navbar Title/Name Container Shape
switch( get_option('sbs_display')['nav-title-style'] ) {

  case 1: // Default (Rectangular)
    break;
  case 2: // Capsule
    wp_enqueue_style( 'sbs-theme-nav-title-capsule', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-capsule-navbar.css' ), array( 'sbs-theme-color' ) );
    break;
  case 3: // Arrows
    wp_enqueue_style( 'sbs-theme-nav-title-arrow', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-arrow-navbar.css' ), array( 'sbs-theme-color' ) );
    break;
  case 4: // TV Screen
    wp_enqueue_style( 'sbs-theme-nav-title-tv', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-tv-navbar.css' ), array( 'sbs-theme-color' ) );
    break;
  case 5: // Parallelogram
  wp_enqueue_style( 'sbs-theme-nav-title-tv', plugins_url( '/wc-step-by-step-ordering/css/frontend/themes/navbar-shapes/sbs-para-navbar.css' ), array( 'sbs-theme-color' ) );
  break;
  default:
    break;

}
