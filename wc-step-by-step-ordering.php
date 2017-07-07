<?php
/*
Plugin Name: Step-By-Step Ordering System for WooCommerce
Plugin URI:  http://stepbystepsys.com
Description: Guide customers through your customized ordering process. Requires WooCommerce.
Version:     1.0.6
Author:      Trevor Pham, Andrew Lambros, The Dream Builders Company
Author URI:  http://dreambuilders.co/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: wporg
Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'StepByStepSystem' ) ):

final class StepByStepSystem {

	public $version = '1.0.6';

	public function __construct() {
    $this->define_constants();
		$this->includes();
		$this->initialize();
	}

  private function define_constants() {
    define( 'SBS_PLUGIN_FILE', __FILE__ );
    define( 'SBS_ABSPATH', dirname( __FILE__ ) . '/' );
    define( 'SBS_VERSION', $this->version );
  }

	private function includes() {
		// Include helper functions
		include_once( SBS_ABSPATH . 'includes/helper-functions/helper-functions.php' );

    if ( !is_admin() ) {
      // Include SBS Ordering Shortcode
      include_once( SBS_ABSPATH . 'includes/shortcodes/sbs-select-package.php' );
      include_once( SBS_ABSPATH . 'includes/shortcodes/sbs-woocommerce-step-by-step-ordering.php' );

      // Include WooCommerce template and action overrides
      include_once( SBS_ABSPATH . 'woocommerce/plugin-template-override.php' );

      // Include additions to WooCommerce actions
      include_once( SBS_ABSPATH . 'includes/woocommerce-actions/add-to-cart-loop.php' );

      // Include additional AJAX Add To Cart functions
      include_once( SBS_ABSPATH . 'includes/woocommerce-actions/add-to-cart-ajax.php' );
    }

    include_once( SBS_ABSPATH . 'includes/woocommerce-actions/additional-actions.php' );

		// Include SBS Cart Totals Widget
		include_once( SBS_ABSPATH . 'includes/widgets/sbs-cart-totals.php' );

		// Include WP Admin Options page
		if ( is_admin() ) {
			include_once( SBS_ABSPATH . 'options.php' );
		}
	}

	private function initialize() {
		register_activation_hook( __FILE__, array( $this, 'plugin_activation' ) );
		register_deactivation_hook( __FILE__, array( $this, 'plugin_deactivation' ) );

		add_action( 'wp_head', array( $this, 'sbs_define_ajax_url' ) );
		add_action( 'admin_head', array( $this, 'sbs_define_ajax_url' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'sbs_enqueue_client_style_scripts' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'sbs_dequeue_third_party_scripts'), 20 );
	}

	public function plugin_activation() {
		include_once( SBS_ABSPATH . 'includes/plugin/activation.php' );
	}

	public function plugin_deactivation() {
		include_once( SBS_ABSPATH . 'includes/plugin/deactivation.php' );
	}

	public function sbs_enqueue_client_style_scripts() {
		// Enqueue custom stylesheets
		include_once( SBS_ABSPATH . 'includes/themes/main-style.php' );
		include_once( SBS_ABSPATH . 'includes/themes/common-theme-styles.php' );
		include_once( SBS_ABSPATH . 'includes/themes/theme-selector.php' );

    // Scripts to be enqueued only on SBS pages (or sitewide with setting override).
    if ( is_sbs() || get_option('sbs_general')['ui-outside-sbs'] === 'yes' ) {
      // Enqueue custom scripts
      wp_enqueue_script( 'sbs-add-to-cart', plugins_url( '/js/frontend/sbs-add-to-cart-ajax.js', __FILE__ ), array( 'jquery', 'accountingjs' ) );
      wp_enqueue_script( 'sbs-init-magnific-popup', plugins_url( '/js/frontend/sbs-init-magnific-popup.js', __FILE__ ), array( 'jquery', 'magnific-popupjs' ) );
      wp_enqueue_script( 'sbs-init-zoom', plugins_url( '/js/frontend/sbs-init-zoom.js', __FILE__ ), array( 'jquery', 'zoom' ) );
      wp_enqueue_script( 'sbs-add-to-cart-variation', plugins_url( '/js/frontend/add-to-cart-variation.js', __FILE__ ), array( 'jquery' ) );
      // Enqueue libraries
      wp_enqueue_style( 'magnific-popup-style', plugins_url( '/css/frontend/magnific-popup.css', __FILE__ ) );
      wp_enqueue_script( 'accountingjs', plugins_url( '/js/frontend/accounting.min.js', __FILE__ ) );
      wp_enqueue_script( 'magnific-popupjs', plugins_url( '/js/frontend/magnific-popup.min.js', __FILE__ ), array( 'jquery' ) );
      wp_enqueue_script( 'zoom', plugins_url( '/js/frontend/zoom.min.js', __FILE__ ), array( 'jquery' ) );
    }

    // Scripts to be enqueued only on SBS pages
    if ( is_sbs() ) {
      wp_enqueue_Script( 'disable-cart-widget-links', plugins_url( '/js/frontend/disable-cart-widget-links.js', __FILE__ ), array( 'jquery' ) );
    }

    if ( ! (is_cart() || is_checkout()) ) {
      wp_enqueue_script( 'sbs-fix-quantity-input', plugins_url( '/js/frontend/fix-quantity-input.js', __FILE__ ), array( 'jquery' ) );
    }
	}

	public function sbs_dequeue_third_party_scripts() {
		include_once( SBS_ABSPATH . 'includes/themes/common-theme-styles-dequeue.php' );
	}

	public function sbs_enqueue_preset_themes() {
		include_once( SBS_ABSPATH . 'includes/themes/theme-selector.php' );
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
