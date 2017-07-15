<?php

/**
 *
 *  Enqueue optional CSS, selected from admin options
 *
 */
$themes_style_dir = 'css/frontend/themes/';
// SBS preset color schemes
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

// SBS Navbar Step Number shape
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

// Navbar Title/Name Container Shape
switch( get_option('sbs_display')['nav-title-style'] ) {
	case 1: // Default (Rectangular)
		break;
	case 2: // Capsule
		wp_enqueue_style( 'sbs-theme-nav-title-capsule', plugins_url( $themes_style_dir . 'navbar-shapes/sbs-capsule-navbar.css', SBS_PLUGIN_FILE ), array( 'sbs-theme-color' ), filemtime( SBS_ABSPATH . $themes_style_dir . 'navbar-shapes/sbs-capsule-navbar.css' ) );
		break;
	default:
		break;
}
