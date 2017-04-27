<?php
/*
Plugin Name: WooCommerce Step By Step Ordering
Plugin URI:  http://stepbystepsys.com
Description: Guide customers through your customized ordering process. Requires WooCommerce.
Version:     0.0.1
Author:      Trevor Pham, Andrew Lambros, The Dream Builders Company
Author URI:  http://stepbystepsys.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if ( !class_exists( 'StepByStepSystem' ) ):

final class StepByStepSystem {

	public function __construct() {
		$this->includes();
		$this->initialize();
	}

	private function includes() {
		// Include helper functions
		include_once( plugin_dir_path( __FILE__ ) . 'includes/helper-functions/helper-functions.php' );

		// Include WP Admin Options page
		include_once( plugin_dir_path( __FILE__ ) . 'options.php' );

		// Include SBS Ordering Shortcode
		include_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes/sbs-select-package.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes/sbs-options-and-fees.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/shortcodes/sbs-woocommerce-step-by-step-ordering.php' );

		// Include WooCommerce template and action overrides
		include_once( plugin_dir_path( __FILE__ ) . 'woocommerce/plugin-template-override.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'woocommerce/plugin-action-override.php' );

		// Include additions to WooCommerce actions
		include_once( plugin_dir_path( __FILE__ ) . 'includes/woocommerce-actions/additional-actions.php' );

		// Include SBS Cart Totals Widget
		include_once( plugin_dir_path( __FILE__) . 'includes/widgets/sbs-cart-totals.php' );

		// Include additional AJAX Add To Cart functions
		include_once( plugin_dir_path( __FILE__ ) . 'includes/woocommerce-actions/add-to-cart-ajax.php' );
	}


	private function initialize() {

		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
		add_action( 'wp_head', array( $this, 'sbs_define_ajax_url' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'sbs_enqueue_client_style_scripts' ) );

	}


	public function plugin_activation() {

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


	public function sbs_enqueue_client_style_scripts() {

		// Enqueue libraries
		wp_enqueue_style( 'magnific-popup-style', plugins_url( '/css/frontend/magnific-popup.css', __FILE__ ) );

		wp_enqueue_script( 'accountingjs', plugins_url( '/js/frontend/accounting.min.js', __FILE__ ) );
		wp_enqueue_script( 'magnific-popupjs', plugins_url( '/js/frontend/magnific-popup.min.js', __FILE__ ), array( 'jquery' ) );

		// Enqueue custom stylesheets
		wp_enqueue_style( 'sbs-style', plugins_url( '/css/frontend/sbs-style.css', __FILE__ ), array( 'woocommerce-general', 'woocommerce-layout', 'woocommerce-smallscreen' ) );
		include_once( plugin_dir_path( __FILE__ ) . 'includes/themes/theme-selector.php' );

		// Enqueue custom scripts
		wp_enqueue_script( 'sbs-add-to-cart', plugins_url( '/js/frontend/sbs-add-to-cart-ajax.js', __FILE__ ), array( 'jquery', 'accountingjs' ) );
		wp_enqueue_script( 'sbs-use-magnific-popup', plugins_url( '/js/frontend/sbs-use-magnific-popup.js', __FILE__ ), array( 'jquery', 'magnific-popupjs' ) );

	}


	public function sbs_enqueue_preset_themes() {

		include_once( plugin_dir_path( __FILE__ ) . 'includes/themes/theme-selector.php' );

	}


	public function sbs_define_ajax_url() {
	  ob_start();
	  ?>
	  <script type="text/javascript">
	    var sbsAjaxUrl = "<?php echo admin_url('admin-ajax.php') ?>";
	  </script>
	  <?php
	  echo ob_get_clean();
	}

}

endif;

new StepByStepSystem();
