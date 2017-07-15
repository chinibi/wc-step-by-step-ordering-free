<?php

// Dequeue Divi stylesheets to make them compatible with SBS and WooCommerce styles
if ( wp_get_theme()->get('Name') == 'Divi' || wp_get_theme()->get('Template') == 'Divi' ) {
	wp_dequeue_style( 'magnific-popup' );
}
