<?php

/**
 *
 *  Enqueue optional CSS, selected from admin options
 *
 */

$license = sbs_check_license_cache();
$themes_style_dir = 'css/frontend/themes/';

// SBS preset color schemes
if ( $license ):

  switch( get_option('sbs_display')['color-scheme'] ) {

    case 1: // No preset theme, unstyled
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-default.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-default.css' ) );
      break;
    case 2: // Grayscale "Noir 1"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-noir-1.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-noir-1.css' ) );
      break;
    case 3: // Blue "Royal 1"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-royal-1.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-royal-1.css' ) );
    case 4: // Green 1 "Spring Green"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-green-1.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-green-1.css' ) );
      break;
    case 5: // Green 1 "Aqua Green"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-green-2.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-green-2.css' ) );
      break;
    case 6: // Autumn 1 "Autumn 1"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-autumn-1.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-autumn-1.css' ) );
      break;
    case 7: // Autumn 2 "Autumn 2"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-autumn-2.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-autumn-2.css' ) );
      break;
    case 8: // Neon "Neon"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-neon.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-neon.css' ) );
      break;
    case 9: // Neon Gradient "Neon Gradient"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-neon-gradient.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-default.css' ) );
      break;
    case 10: // Grayscale "Noir 2"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-noir-2.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-noir-2.css' ) );
      break;
    case 11: // Royal 2
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-royal-2.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-royal-2.css' ) );
      break;
    default:
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-default.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-default.css' ) );
      break;

  }

else:

  switch( get_option('sbs_display')['color-scheme'] ) {

    case 1: // No preset theme, unstyled
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-default.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-default.css' ) );
      break;
    case 2: // Grayscale "Noir 1"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-noir-1.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-noir-1.css' ) );
      break;
    case 3: // Blue "Royal 1"
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-royal-1.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-royal-1.css' ) );
      break;
    default:
      wp_enqueue_style( 'sbs-theme-color', plugins_url( $themes_style_dir . 'sbs-theme-default.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'sbs-theme-default.css' ) );
      break;

  }

endif;


// SBS Navbar Step Number shape
if ( $license ):

  switch( get_option('sbs_display')['navbar-style'] ) {

    case 1: // Default shape (Square)
      break;
    case 2: // Circles
      wp_enqueue_style( 'sbs-nav-step-circle', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-circle-step-no.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-circle-step-no.css' ) );
      break;
    case 3: // Upward Pointing Triangles
      wp_enqueue_style( 'sbs-nav-step-triangle-up', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-triangleup-step-no.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-triangleup-step-no.css' ) );
      break;
    case 4: // Downward-pointing Triangles
      wp_enqueue_style( 'sbs-nav-step-triangle-down', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-triangledown-step-no.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-triangledown-step-no.css' ) );
      break;
    case 5: // Hearts
      wp_enqueue_style( 'sbs-nav-step-heart', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-heart-step-no.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-heart-step-no.css' ) );
      break;
    case 6: // 12-pointed starburst
      wp_enqueue_style( 'sbs-nav-step-12star', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-12star-step-no.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-12star-step-no.css' ) );
      break;
    case 7: // Kite
      wp_enqueue_style( 'sbs-nav-step-kite', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-kite-step-no.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-kite-step-no.css' ) );
      break;
    case 8: // Badge Ribbon
      wp_enqueue_style( 'sbs-nav-step-kite', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-ribbon-step-no.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-ribbon-step-no.css' ) );
    default:
      break;

  }

else:

  switch( get_option('sbs_display')['navbar-style'] ) {

    case 1: // Default shape (Square)
      break;
    case 2: // Circles
      wp_enqueue_style( 'sbs-nav-step-circle', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-circle-step-no.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-circle-step-no.css' ) );
      break;
    case 3: // Upward Pointing Triangles
      wp_enqueue_style( 'sbs-nav-step-triangle-up', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-triangleup-step-no.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-triangleup-step-no.css' ) );
      break;
    default:
      break;

  }

endif;


// SBS Calculator Widget font
if ( $license ):

  switch( get_option('sbs_display')['calc-font'] ) {

    case 1: // Default (determined by Wordpress Theme)
      break;
    case 2: // Helvetica
      wp_enqueue_style( 'sbs-calc-theme-helvetica', plugins_url( $themes_style_dir . 'fonts/calculator/helvetica.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/calculator/helvetica.css' ) );
      break;
    case 3: // Arial
      wp_enqueue_style( 'sbs-calc-theme-arial', plugins_url( $themes_style_dir . 'fonts/calculator/arial.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/calculator/arial.css' ) );
      break;
    case 4: // Verdana
      wp_enqueue_style( 'sbs-calc-theme-verdana', plugins_url( $themes_style_dir . 'fonts/calculator/verdana.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/calculator/verdana.css' ) );
      break;
    case 5: // Georgia
      wp_enqueue_style( 'sbs-calc-theme-georgia', plugins_url( $themes_style_dir . 'fonts/calculator/georgia.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/calculator/georgia.css' ) );
      break;
    case 6: // Lucida
      wp_enqueue_style( 'sbs-calc-theme-georgia', plugins_url( $themes_style_dir . 'fonts/calculator/lucida.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/calculator/lucida.css' ) );
      break;
    case 7: // Palatino
      wp_enqueue_style( 'sbs-calc-theme-georgia', plugins_url( $themes_style_dir . 'fonts/calculator/palatino.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/calculator/palatino.css' ) );
      break;
    default:
      break;

  }

endif;


if ( $license ):
  switch( get_option('sbs_display')['category-font'] ) {

    case 1: // Default (determined by Wordpress Theme)
      break;
    case 2: // Helvetica
      wp_enqueue_style( 'sbs-cat-theme-helvetica', plugins_url( $themes_style_dir . 'fonts/categories/helvetica.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/categories/helvetica.css' ) );
      break;
    case 3: // Arial
      wp_enqueue_style( 'sbs-cat-theme-arial', plugins_url( $themes_style_dir . 'fonts/categories/arial.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/categories/arial.css' ) );
      break;
    case 4: // Verdana
      wp_enqueue_style( 'sbs-cat-theme-verdana', plugins_url( $themes_style_dir . 'fonts/categories/verdana.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/categories/verdana.css' ) );
      break;
    case 5: // Georgia
      wp_enqueue_style( 'sbs-cat-theme-georgia', plugins_url( $themes_style_dir . 'fonts/categories/georgia.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/categories/georgia.css' ) );
      break;
    case 6: // Lucida
      wp_enqueue_style( 'sbs-cat-theme-lucida', plugins_url( $themes_style_dir . 'fonts/categories/lucida.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/categories/lucida.css' ) );
      break;
    case 7: // Palatino
      wp_enqueue_style( 'sbs-cat-theme-palatino', plugins_url( $themes_style_dir . 'fonts/categories/palatino.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/categories/palatino.css' ) );
      break;
    default:
      break;

  }

endif;

if ( $license ):
  switch( get_option('sbs_display')['category-desc-font'] ) {

    case 1: // Default (determined by Wordpress Theme)
      break;
    case 2: // Helvetica
      wp_enqueue_style( 'sbs-catdesc-theme-helvetica', plugins_url( $themes_style_dir . 'fonts/category-descriptions/helvetica.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/category-descriptions/helvetica.css' ) );
      break;
    case 3: // Arial
      wp_enqueue_style( 'sbs-catdesc-theme-arial', plugins_url( $themes_style_dir . 'fonts/category-descriptions/arial.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/category-descriptions/arial.css' ) );
      break;
    case 4: // Verdana
      wp_enqueue_style( 'sbs-catdesc-theme-verdana', plugins_url( $themes_style_dir . 'fonts/category-descriptions/verdana.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/category-descriptions/verdana.css' ) );
      break;
    case 5: // Georgia
      wp_enqueue_style( 'sbs-catdesc-theme-georgia', plugins_url( $themes_style_dir . 'fonts/category-descriptions/georgia.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/category-descriptions/georgia.css' ) );
      break;
    case 6: // Lucida
      wp_enqueue_style( 'sbs-catdesc-theme-georgia', plugins_url( $themes_style_dir . 'fonts/category-descriptions/lucida.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/category-descriptions/lucida.css' ) );
      break;
    case 7: // Palatino
      wp_enqueue_style( 'sbs-catdesc-theme-georgia', plugins_url( $themes_style_dir . 'fonts/category-descriptions/palatino.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/category-descriptions/palatino.css' ) );
      break;
    default:
      break;

  }

endif;

if ( $license ):

  switch( get_option('sbs_display')['nav-button-font'] ) {

    case 1: // Default (determined by Wordpress Theme)
      break;
    case 2: // Helvetica
      wp_enqueue_style( 'sbs-navbutton-theme-helvetica', plugins_url( $themes_style_dir . 'fonts/nav-buttons/helvetica.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/nav-buttons/helvetica.css' ) );
      break;
    case 3: // Arial
      wp_enqueue_style( 'sbs-navbutton-theme-arial', plugins_url( $themes_style_dir . 'fonts/nav-buttons/arial.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/nav-buttons/arial.css' ) );
      break;
    case 4: // Verdana
      wp_enqueue_style( 'sbs-navbutton-theme-verdana', plugins_url( $themes_style_dir . 'fonts/nav-buttons/verdana.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/nav-buttons/verdana.css' ) );
      break;
    case 5: // Georgia
      wp_enqueue_style( 'sbs-navbutton-theme-georgia', plugins_url( $themes_style_dir . 'fonts/nav-buttons/georgia.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/nav-buttons/georgia.css' ) );
      break;
    case 6: // Lucida
      wp_enqueue_style( 'sbs-navbutton-theme-georgia', plugins_url( $themes_style_dir . 'fonts/nav-buttons/lucida.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/nav-buttons/lucida.css' ) );
      break;
    case 7: // Palatino
      wp_enqueue_style( 'sbs-navbutton-theme-georgia', plugins_url( $themes_style_dir . 'fonts/nav-buttons/palatino.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/nav-buttons/palatino.css' ) );
      break;
    default:
      break;

  }

endif;

if ( $license ):
  switch( get_option('sbs_display')['navbar-font'] ) {

    case 1: // Default (determined by Wordpress Theme)
      break;
    case 2: // Helvetica
      wp_enqueue_style( 'sbs-nav-theme-helvetica', plugins_url( $themes_style_dir . 'fonts/navbar/helvetica.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/navbar/helvetica.css' ) );
      break;
    case 3: // Arial
      wp_enqueue_style( 'sbs-nav-theme-arial', plugins_url( $themes_style_dir . 'fonts/navbar/arial.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/navbar/arial.css' ) );
      break;
    case 4: // Verdana
      wp_enqueue_style( 'sbs-nav-theme-verdana', plugins_url( $themes_style_dir . 'fonts/navbar/verdana.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/navbar/verdana.css' ) );
      break;
    case 5: // Georgia
      wp_enqueue_style( 'sbs-nav-theme-georgia', plugins_url( $themes_style_dir . 'fonts/navbar/georgia.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/navbar/georgia.css' ) );
      break;
    case 6: // Lucida
      wp_enqueue_style( 'sbs-nav-theme-georgia', plugins_url( $themes_style_dir . 'fonts/navbar/lucida.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/navbar/lucida.css' ) );
      break;
    case 7: // Palatino
      wp_enqueue_style( 'sbs-nav-theme-georgia', plugins_url( $themes_style_dir . 'fonts/navbar/palatino.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'fonts/navbar/palatino.css' ) );
      break;
    default:
      break;

  }

endif;

// Calculator widget column borders
if ( isset( get_option('sbs_display')['calc-borders'] ) ) {
  wp_enqueue_style( 'sbs-calc-theme-withborder', plugins_url( $themes_style_dir . 'sbs-calc-theme-withborder.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ) );
}


// Anchor tag hover color effect
if ( isset(get_option('sbs_display')['hover-effect'] ) && get_option('sbs_display')['hover-effect'] == '1' ) {

  switch( get_option('sbs_display')['color-scheme'] ) {

    case 1: // No preset theme, unstyled
      break;
    case 2: // Green 1 "Spring Green"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-green-1.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-green-1.css' ) );
      break;
    case 3: // Green 1 "Aqua Green"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-green-1.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-green-1.css' ) );
      break;
    case 4: // Autumn 1 "Autumn 1"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-autumn-1.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-autumn-1.css' ) );
      break;
    case 5: // Autumn 2 "Autumn 2"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-autumn-2.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-autumn-2.css' ) );
      break;
    case 6: // Neon "Neon"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-neon.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-neon.css' ) );
      break;
    case 7: // Neon Gradient "Neon Gradient"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-neon.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-neon.css' ) );
      break;
    case 8: // Grayscale "Noir 1"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-noir-1.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-noir-1.css' ) );
      break;
    case 9: // Grayscale "Noir 2"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-noir-2.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-noir-2.css' ) );
      break;
    case 10: // Blue "Royal 1"
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-royal.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-royal.css' ) );
      break;
    case 11:
      wp_enqueue_style( 'sbs-theme-hover-color', plugins_url( $themes_style_dir . 'color-scheme-hover/sbs-ahover-royal.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'color-scheme-hover/sbs-ahover-royal.css' ) );
      break;
    default:
      break;

  }

}

if ( isset(get_option('sbs_display')['drop-shadow'] ) && get_option('sbs_display')['drop-shadow'] == '1' && $license ) {
  wp_enqueue_style( 'sbs-drop-shadow', plugins_url( $themes_style_dir . 'drop-shadows/sbs-drop-shadow.css', SBS_PLUGIN_FILE ), array( 'sbs-style' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'drop-shadows/sbs-drop-shadow.css' ) );
}

// Navbar Title/Name Container Shape
if ( $license ):
  switch( get_option('sbs_display')['nav-title-style'] ) {

    case 1: // Default (Rectangular)
      break;
    case 2: // Capsule
      wp_enqueue_style( 'sbs-theme-nav-title-capsule', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-capsule-navbar.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-capsule-navbar.css' ) );
      break;
    case 3: // Arrows
      wp_enqueue_style( 'sbs-theme-nav-title-arrow', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-arrow-navbar.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-arrow-navbar.css' ) );
      break;
    case 4: // TV Screen
      wp_enqueue_style( 'sbs-theme-nav-title-tv', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-tv-navbar.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-tv-navbar.css' ) );
      break;
    case 5: // Parallelogram
    wp_enqueue_style( 'sbs-theme-nav-title-tv', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-para-navbar.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-para-navbar.css' ) );
    break;
    default:
      break;

  }
else:
  switch( get_option('sbs_display')['nav-title-style'] ) {

    case 1: // Default (Rectangular)
      break;
    case 2: // Capsule
      wp_enqueue_style( 'sbs-theme-nav-title-capsule', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-capsule-navbar.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-capsule-navbar.css' ) );
      break;
    default:
      break;

  }
endif;
