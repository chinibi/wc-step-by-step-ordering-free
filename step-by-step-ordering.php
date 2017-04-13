<?php
/*
Plugin Name: WooCommerce Step By Step Ordering
Plugin URI:  http://stepbystepsys.com
Description: Guide customers through your customized ordering process. Requires WooCommerce.
Version:     0.0.1
Author:      Trevor Pham, The Dream Builders Company
Author URI:  http://stepbystepsys.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Include helper functions
include_once( plugin_dir_path( __FILE__ ) . 'includes/helper-functions/helper-functions.php' );

// Include WP Admin Options page
include_once( plugin_dir_path( __FILE__ ) . 'options.php' );

// Include SBS Ordering Shortcode
include_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes/sbs-woocommerce-step-by-step-ordering.php' );

// Include SBS Cart Totals Widget
include_once( plugin_dir_path( __FILE__) . 'includes/widgets/sbs-cart-totals.php' );

// Include additional AJAX Add To Cart functions
include_once( plugin_dir_path( __FILE__ ) . 'includes/woocommerce-actions/add-to-cart-ajax.php' );

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

function sbs_enqueue_client_style_scripts() {
	wp_enqueue_style( 'sbs-style', plugins_url( '/css/sbs-style.css', __FILE__ ) );
	wp_enqueue_script( 'accountingjs', plugins_url( '/js/frontend/accounting.min.js', __FILE__ ) );
	wp_enqueue_script( 'sbs-add-to-cart', plugins_url( '/js/frontend/sbs-add-to-cart.js', __FILE__ ), array('jquery', 'accountingjs') );
}
add_action( 'wp_enqueue_scripts', 'sbs_enqueue_client_style_scripts' );

function sbs_define_ajax_url() {
  ob_start();
  ?>
  <script type="text/javascript">
    var sbsAjaxUrl = "<?php echo admin_url('admin-ajax.php') ?>";
  </script>
  <?php
  echo ob_get_clean();
}
add_action( 'wp_head', 'sbs_define_ajax_url' );
