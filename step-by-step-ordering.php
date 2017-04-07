<?php
/*
Plugin Name: WooCommerce Step By Step Ordering
Plugin URI:  http://stepbystepsys.com
Description: Guide customers through your customized ordering process. Requires WooCommerce.
Version:     0.0.1
Author:      Author
Author URI:  https://developer.wordpress.org/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

include_once( plugin_dir_path( __FILE__ ) . 'options.php' );
include_once( plugin_dir_path( __FILE__ ) . 'shortcodes/sbs-woocommerce-step-by-step-ordering.php' );

function sbs_plugin_activation() {

  if ( !post_exists( 'ordering' ) ) {
    $page_data = array(
      'post_status' => 'publish',
      'post_type'   => 'page',
      'post_author' => 1,
      'post_name'   => 'ordering',
      'post_title'  => 'Step-By-Step Ordering',
      'comment_status' => 'closed',
      'post_content'     => '[sbs_woocommerce_step_by_step_ordering]'
    );

    wp_insert_post( $page_data );
  }

}

register_activation_hook( __FILE__, 'sbs_plugin_activation' );
