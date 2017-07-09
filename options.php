<?php

// Create a WP-Admin menu item
// This is a WooCommerce submenu item, indicating it's an extension of WooCommerce

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

add_action( 'admin_menu', 'sbs_plugin_admin_add_page' );

function sbs_plugin_admin_add_page() {
  add_submenu_page(
    'woocommerce', // The slug name for the parent menu (or the file name of a standard WordPress admin page).
    'Step-By-Step Ordering', // The text to be displayed in the title tags of the page when the menu is selected.
    'Step-By-Step Ordering', //  The text to be used for the menu.
    'manage_options', // The capability required for this menu to be displayed to the user.
    'stepbystepsys', // The slug name to refer to this menu by (should be unique for this menu).
    'sbs_plugin_options_page' // The function to be called to output the content for this page.
  );
}

function sbs_load_custom_wp_admin_style() {

	global $pagenow;

	if ( $pagenow === 'admin.php' && $_GET['page'] === 'stepbystepsys' ) {
		// load custom jQuery UI scripts and styles
		wp_enqueue_script( 'johnny-jquery-sortable', plugin_dir_url( __FILE__ ) . 'js/admin/johnny-jquery-sortable.js', array( 'jquery' ) );
		wp_enqueue_script( 'use-jquery-sortable', plugin_dir_url( __FILE__ ) . 'js/admin/use-jquery-sortable.js', array( 'johnny-jquery-sortable' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/admin/use-jquery-sortable.js' ) );
    wp_enqueue_style( 'bootstrap', plugin_dir_url( __FILE__ ) . 'css/admin/bootstrap.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/admin/bootstrap.css' ) );
		wp_enqueue_style( 'sbs_admin_style', plugin_dir_url( __FILE__ ) . 'css/admin/style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/admin/style.css' ) );
	}

}
add_action( 'admin_enqueue_scripts', 'sbs_load_custom_wp_admin_style' );

function sbs_admin_dashboard_notice() {

	global $pagenow;

	if ( $pagenow === 'index.php' ) {
		echo '<div class="notice notice-info is-dismissible">';
		echo '<p class="sbs-buy-notice">Thank you for trying out the <strong>Step-By-Step Ordering System For WooCommerce</strong>.  Please support us by <strong><a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">Going Pro!</a></strong> SBS Premium offers additional features like unlimited steps, navigation options, required products, either-or products, package store credit, preset themes, and much more!  You will also have access to our support team.  You can even try SBS Premium for FREE! <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">Visit our website</a> for more info.</p>';
		echo '</div>';
	}

}
add_action( 'admin_notices', 'sbs_admin_dashboard_notice' );

function sbs_admin_settings_notices() {

	global $pagenow;
	$is_woocommerce_or_front_page = false;

	if ( ( isset( $_GET['page'] ) && $_GET['page'] === 'stepbystepsys' ) ||
			 ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'product' ) ||
			 ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'shop_order' ) ||
			 ( isset( $_GET['post_type'] ) && $_GET['post_type'] === 'shop_coupon' ) ||
			 ( isset( $_GET['page'] ) && $_GET['page'] === 'wc-reports' ) ||
			 ( isset( $_GET['page'] ) && $_GET['page'] === 'wc-settings' ) ||
			 ( isset( $_GET['page'] ) && $_GET['page'] === 'wc-status' ) ||
       ( isset( $_GET['page'] ) && $_GET['page'] === 'wc-addons' )
		 ) {
		$is_woocommerce_or_front_page = true;
	}

	$current_admin_page = isset( $_GET['page'] ) ? $_GET['page'] : false;

	if ( !$is_woocommerce_or_front_page ) {
		return;
	}

	echo '<div class="notice notice-info is-dismissible">';
	echo '<p class="sbs-buy-notice">Thank you for using the <strong>Step-By-Step Ordering System for WooCommerce</strong>.  Although this is a fully functional plugin that will enhance your customer\'s shopping experience, our premium version of this plugin offers so much more!  Please support us by <strong><a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">Going Pro!</a></strong>. SBS Premium offers additional features like unlimited steps, navigation options, required products, either-or products, package store credit, preset themes, and much more! You will also have access to our <strong>support team</strong>! <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">Click here</a> for more info!</p>';
	echo '</div>';

}
add_action( 'admin_notices', 'sbs_admin_settings_notices' );


function sbs_plugin_options_page() {
  $banner_image_src = plugin_dir_url( SBS_PLUGIN_FILE ) . 'assets/admin/side-banner.png';
  $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options';
  ?>

  <div class="wrap">
    <h2>Step-By-Step Ordering Options</h2>
    <?php settings_errors(); ?>
    <h2 class="nav-tab-wrapper">
      <a href="?page=stepbystepsys&tab=general_options" class="nav-tab <?php echo $active_tab === 'general_options' ? 'nav-tab-active' : null ?>">General</a>
			<a href="?page=stepbystepsys&tab=package_options" class="nav-tab <?php echo $active_tab === 'package_options' ? 'nav-tab-active' : null ?>">Packages</a>
      <a href="?page=stepbystepsys&tab=sbs_options" class="nav-tab <?php echo $active_tab === 'sbs_options' ? 'nav-tab-active' : null ?>">Step-By-Step</a>
      <a href="?page=stepbystepsys&tab=display_options" class="nav-tab <?php echo $active_tab === 'display_options' ? 'nav-tab-active' : null ?>">Display</a>
			<a href="?page=stepbystepsys&tab=sbs_premium" class="nav-tab <?php echo $active_tab === 'sbs_premium' ? 'nav-tab-active' : null ?>">Premium</a>
			<a href="?page=stepbystepsys&tab=help" class="nav-tab <?php echo $active_tab === 'help' ? 'nav-tab-active' : null ?>">Help</a>
    </h2>

    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-9">
          <?php if ( $active_tab !== 'sbs_premium' ): ?>
            <form action="<?php echo esc_url('options.php') ?>" method="post">
              <?php sbs_render_active_tab($active_tab) ?>
            </form>
          <?php else: ?>
            <?php sbs_render_active_tab($active_tab) ?>
          <?php endif ?>
        </div>
        <div class="col-sm-3">
          <div style="text-align: center;">
            <a class="sidebar-banner-link" rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">
              <img class="sidebar-banner" src="<?php echo esc_url( $banner_image_src ) ?>" />
            </a><br>
            <section>
              Want to remove this ad?<br>
              <strong><a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">Get Premium</a></strong><br>
            </section>
            <section style="margin-top: 3em;">
              <strong style="margin-bottom: 2em;">If you like working with the<br>Step-By-Step Ordering System For WooCommerce,<br>your donations are greatly appreciated!</strong>
              <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                <input type="hidden" name="cmd" value="_s-xclick">
                <input type="hidden" name="hosted_button_id" value="4KHCRZMQXFU3Q">
                <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
                <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
              </form>
            </section>
          </div>
        </div>
      </div>
    </div>

  </div>

	<?php add_filter( 'admin_footer_text', 'sbs_render_wp_admin_footer' ) ?>
	<?php add_filter( 'update_footer', 'sbs_render_wp_admin_version', 100 ) ?>
  <?php
}

function sbs_render_active_tab($active_tab) {
  switch($active_tab) {
    case 'general_options':
      echo sbs_render_general_options();
      break;
    case 'sbs_options':
      echo sbs_render_sbs_options();
      break;
		case 'package_options':
			echo sbs_render_package_options();
			break;
    case 'display_options':
      echo sbs_render_display_options();
      break;
		case 'sbs_premium':
			echo sbs_render_premium_page();
			break;
		case 'help':
			echo sbs_render_admin_help_page();
			break;
  }

}


function sbs_render_general_options() {
  ob_start();
  ?>
    <?php settings_fields('sbs_general') ?>
    <?php do_settings_sections('sbs_general') ?>
    <?php submit_button() ?>
  <?php

  return ob_get_clean();
}

function sbs_render_sbs_options() {
  ob_start();
  ?>
    <?php settings_fields('sbs_order_settings') ?>
    <?php do_settings_sections('sbs_order_settings') ?>
		<?php submit_button() ?>
  <?php

  return ob_get_clean();
}

function sbs_render_package_options() {
	ob_start();
	?>
		<?php settings_fields('sbs_package_settings') ?>
		<?php do_settings_sections('sbs_package_settings') ?>
		<?php submit_button() ?>
	<?php

	return ob_get_clean();
}

function sbs_render_display_options() {
  ob_start();
  ?>
		<?php add_thickbox() ?>
    <?php settings_fields('sbs_display') ?>
    <?php do_settings_sections('sbs_display') ?>
		<?php submit_button() ?>
  <?php

  return ob_get_clean();
}

function sbs_render_premium_key_page() {
	ob_start();
	?>
		<?php settings_fields('sbs_premium') ?>
		<?php do_settings_sections('sbs_premium') ?>
	<?php

	return ob_get_clean();
}

function sbs_render_wp_admin_footer() {
	ob_start();
	?>
	<p class="alignleft description">If you like the <strong>Step-By-Step Ordering System for WooCommerce</strong> please leave us a <a rel="noopener noreferrer" target="_blank" href="https://wordpress.org/plugins/step-by-step-ordering-system-for-woocommerce">5-star</a> rating.  Thanks for your support!</p>
	<?php

	return ob_get_clean();
}

function sbs_render_wp_admin_version() {
	$version = get_option('sbs_version');
	return '<p id="footer-upgrade" class="alignright">SBS Version ' . $version . '</p>';
}

function sbs_admin_help_tooltip( $direction = 'top', $html = '' ) {
	// Valid directions are 'top' and 'right'
	ob_start();
	?>
	<div class="sbs-tooltip">
		<span class="sbs-tooltip-icon"></span>
		<span class="sbs-tooltiptext sbs-tooltip-<?php echo esc_attr($direction) ?>">
			<?php echo $html ?>
		</span>
	</div>
	<?php
	return ob_get_clean();
}

/* ------------------------------------------------------------------------ *
 * Setting Registration
 * ------------------------------------------------------------------------ */

/**
 * Initializes the theme options page by registering the Sections,
 * Fields, and Settings.
 *
 * This function is registered with the 'admin_init' hook.
 */

add_action('admin_init', 'sbs_plugin_settings_init');
function sbs_plugin_settings_init() {

  add_settings_section(
    'sbs_general', // String for use in the 'id' attribute of tags.
    'General Settings', // Title of the section.
    'sbs_general_description', // Function that fills the section with the desired content. The function should echo its output.
    'sbs_general' // The menu page on which to display this section. Should match $menu_slug from Function Reference/add theme page
  );
  add_settings_section(
    'sbs_order_settings',
    'Step-By-Step Settings',
    'sbs_sbs_description',
    'sbs_order_settings'
  );
	add_settings_section(
		'sbs_package_settings',
		'Package Settings',
		'sbs_package_description',
		'sbs_package_settings'
	);
  add_settings_section(
    'sbs_display',
    'Display Settings',
    'sbs_display_description',
    'sbs_display'
  );
	add_settings_section(
		'sbs_premium',
		'Step-By-Step Premium Version',
		'sbs_premium_description',
		'sbs_premium'
	);

	add_settings_field(
		'sbs_page_name', // String for use in the 'id' attribute of tags.
		'Step-By-Step Page', // Title of the field.
		'sbs_page_name_callback', //  Function that fills the field with the desired inputs as part of the larger form. Passed a single argument, the $args array. Name and id of the input should match the $id given to this function. The function should echo its output.
		'sbs_general', //  The menu page on which to display this field. Should match $menu_slug from add_theme_page() or from do_settings_sections().
		'sbs_general' // The section of the settings page in which to show the box (default or a section you added with add_settings_section(), look at the page in the source to see what the existing ones are.)
	);
	add_settings_field(
		'sbs_widget_link',
		'Widgets',
		'sbs_widgets_callback',
		'sbs_general',
		'sbs_general'
	);
  add_settings_field(
    'sbs_ui_outside_sbs',
    'Enable Catalog Pop-Ups Outside of Step-By-Step',
    'sbs_apply_sbs_ui_outside_sbs_pages_callback',
    'sbs_general',
    'sbs_general'
  );
  add_settings_field(
    'sbs_hide_product_placeholder_image',
    'Hide Product Placeholder Images',
    'sbs_hide_product_placeholder_image_callback',
    'sbs_general',
    'sbs_general'
  );
	add_settings_field(
		'sbs_featured_position',
		'Featured Items Position ' . sbs_premium_site_link() . sbs_admin_help_tooltip( 'right', 'Display featured items at the beginning or end of pages.' ),
		'sbs_featured_items_pos_callback',
		'sbs_general',
		'sbs_general'
	);
	add_settings_field(
		'sbs_featured_visibility',
		'Featured Items Visibility' . sbs_admin_help_tooltip( 'right', 'Display featured products separately in their own section.' ),
		'sbs_featured_items_vis_callback',
		'sbs_general',
		'sbs_general'
	);
	add_settings_field(
		'sbs_required_featured_label',
		'Featured and Required Section Labels ' . sbs_premium_site_link(),
		'sbs_req_feat_label_callback',
		'sbs_general',
		'sbs_general'
	);

  // SBS Step-By-Step Settings Fields
  add_settings_field(
    'step_order',
    'Step-By-Step Builder' . sbs_admin_help_tooltip('right', 'Determines the page order of the ordering process.'),
    'sbs_sbs_table_callback',
    'sbs_order_settings',
    'sbs_order_settings'
  );
	add_settings_field(
		'sbs_navbar_navigation',
		'Navbar Navigation' . sbs_admin_help_tooltip('right', 'The step navbar contains navigable links in each step. You can disallow skipping of steps here.'),
		'sbs_navbar_navigation_callback',
		'sbs_order_settings',
		'sbs_order_settings'
	);

	// SBS Package Settings
	add_settings_field(
		'sbs_package_enable',
		'Enable / Disable',
		'sbs_package_enable_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);
	add_settings_field(
		'sbs_package_calc_label',
		'Calculator Widget Label ' . sbs_premium_site_link(),
		'sbs_package_calc_label_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);
	add_settings_field(
		'sbs_package_page',
		'Package Page',
		'sbs_package_page_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);
	add_settings_field(
		'sbs_package_category',
		'Package Category',
		'sbs_package_category_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);
	add_settings_field(
		'sbs_package_tiers',
		'Package Page Builder',
		'sbs_package_tier_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);
	add_settings_field(
		'sbs_package_add_to_cart',
		'Add-to-Cart Behavior',
		'sbs_package_atc_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);
	add_settings_field(
		'sbs_packages_style',
		'Package Selection Appearance',
		'sbs_package_select_style_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);
	add_settings_field(
		'sbs_package_merch_cred',
		'"Store Credit" Calculator Widget Label ' . sbs_premium_site_link(),
		'sbs_package_merch_cred_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);

  // SBS Display Settings Fields
  add_settings_field(
    'color_scheme',
    'Color Scheme',
    'sbs_display_color_scheme_callback',
    'sbs_display',
    'sbs_display'
  );
  add_settings_field(
    'navbar_style',
    'Step Number Shape',
    'sbs_display_navbar_number_shape_callback',
    'sbs_display',
    'sbs_display'
  );
	add_settings_field(
		'nav_title_style',
		'Step Name Shape',
		'sbs_display_navbar_title_shape_callback',
		'sbs_display',
		'sbs_display'
	);
	add_settings_field(
		'calc_widget',
		'SBS Calculator Widget' . sbs_admin_help_tooltip( 'right', 'Stylings for our widget displaying price totals of items in the cart, listed by step.' ),
		'sbs_display_calc_callback',
		'sbs_display',
		'sbs_display'
	);
	add_settings_field(
		'sbs_fonts',
		'Fonts',
		'sbs_display_fonts_callback',
		'sbs_display',
		'sbs_display'
	);
	add_settings_field(
		'misc_styles',
		'Miscellaneous Styles',
		'sbs_display_misc_callback',
		'sbs_display',
		'sbs_display'
	);

	add_settings_field(
		'sbs_premium_key',
		'License Key' . sbs_admin_help_tooltip( 'right', 'Please enter the license key for this product to activate premium features.<br>An email is sent, with your license key, to your valid email after purchasing the premium version of this plugin. If you are a developer, you can purchase a multiple site license.' ),
		'sbs_premium_key_callback',
		'sbs_premium',
		'sbs_premium'
	);
  // add_settings_field(
  //   'show_calculator',
  //   'Display Sidebar Calculator Widget',
  //   'sbs_display_sidebar_calculator_callback',
  //   'sbs_display',
  //   'sbs_display'
  // );

	register_setting('sbs_general', 'sbs_general', 'sbs_general_settings_sanitize');
  register_setting('sbs_order_settings', 'step_order');
	register_setting('sbs_order_settings', 'sbs_navbar', 'sbs_navbar_settings_sanitize');
	register_setting('sbs_package_settings', 'sbs_package', 'sbs_package_settings_sanitize');
  register_setting('sbs_display', 'sbs_display', 'sbs_display_settings_sanitize');
  // register_setting('sbs_display', 'color_scheme');
  // register_setting('sbs_display', 'navbar_style');
  // register_setting('sbs_display', 'show_calculator');

}

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */

/**
 * These functions provide the descriptions for each settings section
 */

function sbs_general_description() {
	ob_start();
	?>
	<p>
		Thank you for using the Step By Step Ordering System for WooCommerce.  The
		tabs above will help you configure the system for your needs.  For more
		information, click on the <a href="<?php echo esc_url( admin_url('admin.php') . '?page=stepbystepsys&tab=help' )?>">Help</a> tab or <a href="http://stepbystepsys.com">visit our site</a>.
	</p>
  <p>If you like working with Step-By-Step Ordering System for WooCommerce, you can donate to us by clicking the PayPal link below.</p>
  <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
    <input type="hidden" name="cmd" value="_s-xclick">
    <input type="hidden" name="hosted_button_id" value="4KHCRZMQXFU3Q">
    <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
  </form>
  <div class="hidden-sm hidden-md hidden-lg">
    <div class="container mobile-upsell-notice">
      <span style="font-size: 1.2em;"><strong>Upgrade to Premium today:</strong></span>
      <ul>
        <li>Unlimited Steps</li>
        <li>Unlimited Packages</li>
        <li>Store Credit</li>
        <li>Required Products</li>
        <li>Either/Or Products</li>
        <li>Auto-Add Products</li>
        <li>Color Schemes</li>
        <li>Multiple Nav Shapes</li>
        <li>Custom Labels</li>
        <li>Options and Fees Page</li>
        <li>Shadow Effects</li>
        <li>Premium Support</li>
      </ul>
      <a class="mobile-upsell-link" rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">GET PREMIUM</a>
    </div>
  </div>
	<?php

	echo ob_get_clean();
}

function sbs_sbs_description() {
  ob_start();
  ?>
	<p>Create your ordering process by dragging and dropping (or touching the control buttons at the right side of each button) your steps in the boxes below.</p>
	<p>You can select from your Product Categories.  Drag any desired categories from the
	Available Categories column, and move them to the Your Ordering Process column.
	You can also do this by touching the &#10133; button.
	</p>
	<p>To remove a step from your ordering process just drag it back under the Available Categories column. You can also do this by touching the &#10006; button.</p>
  <p><strong>Note: Products must belong to both a parent and a child category in order for Step-By-Step to function correctly.</strong> (<a rel="nofollow noreferrer" target="_blank" href="http://stepbystepsys.com/wp-content/uploads/2017/06/sbs-product-category.png">Example</a>)</p>

  <div class="hidden-sm hidden-md hidden-lg">
    <div class="container mobile-upsell-notice">
      <span style="font-size: 1.2em;"><strong>Upgrade to Premium today:</strong></span>
      <ul>
        <li>Unlimited Steps</li>
        <li>Unlimited Packages</li>
        <li>Store Credit</li>
        <li>Required Products</li>
        <li>Either/Or Products</li>
        <li>Auto-Add Products</li>
        <li>Color Schemes</li>
        <li>Multiple Nav Shapes</li>
        <li>Custom Labels</li>
        <li>Options and Fees Page</li>
        <li>Shadow Effects</li>
        <li>Premium Support</li>
      </ul>
      <a class="mobile-upsell-link" rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">GET PREMIUM</a>
    </div>
  </div>

  <?php
  echo ob_get_clean();
}

function sbs_package_description() {
	ob_start();
	?>
		<p>
			Packages serve as a lead-in to your store.  Selecting a package
			on the Packages page will take the customer to Step 1 of the ordering process.
		</p>
		<p>
			You can create a package product with multiple features to accompany your step-by-step store.<br>
			Add additional features such as a merchandise (store) credit to your packages (premium feature).  If you accompany your package(s) with
			our product features such as featured products, required products (premium), either/or products (premium), already added products (premium), etc.
			our step-by-step system provides endless ways to make your customer experience that much better!
		</p>
		<p>
			If you don't wish to use packages, select Deactivated from the drop down menu.
		</p>

    <div class="hidden-sm hidden-md hidden-lg">
      <div class="container mobile-upsell-notice">
        <span style="font-size: 1.2em;"><strong>Upgrade to Premium today:</strong></span>
        <ul>
          <li>Unlimited Steps</li>
          <li>Unlimited Packages</li>
          <li>Store Credit</li>
          <li>Required Products</li>
          <li>Either/Or Products</li>
          <li>Auto-Add Products</li>
          <li>Color Schemes</li>
          <li>Multiple Nav Shapes</li>
          <li>Custom Labels</li>
          <li>Options and Fees Page</li>
          <li>Shadow Effects</li>
          <li>Premium Support</li>
        </ul>
        <a class="mobile-upsell-link" rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">GET PREMIUM</a>
      </div>
    </div>

	<?php
	echo ob_get_clean();
}

function sbs_display_description() {
  ob_start();
  ?>
  <p>
    Customize the appearance of the ordering process with preset styles and themes.
  </p>
  <div class="hidden-sm hidden-md hidden-lg">
    <div class="container mobile-upsell-notice">
      <span style="font-size: 1.2em;"><strong>Upgrade to Premium today:</strong></span>
      <ul>
        <li>Unlimited Steps</li>
        <li>Unlimited Packages</li>
        <li>Store Credit</li>
        <li>Required Products</li>
        <li>Either/Or Products</li>
        <li>Auto-Add Products</li>
        <li>Color Schemes</li>
        <li>Multiple Nav Shapes</li>
        <li>Custom Labels</li>
        <li>Options and Fees Page</li>
        <li>Shadow Effects</li>
        <li>Premium Support</li>
      </ul>
      <a class="mobile-upsell-link" rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">GET PREMIUM</a>
    </div>
  </div>
  <?php
  echo ob_get_clean();
}

function sbs_premium_description() {
	echo '<p style="font-size: 1.1em;"><strong>Unlock the full version of this plugin by purchasing a license on <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">our website</a>. Enter the key sent to your valid email address.</strong></p>';
}

/**
 * Preserve any disabled fields that previously had values until user was
 * de-licensed
 */
function sbs_general_settings_sanitize( $input ) {

	$featured_label = isset( get_option('sbs_general')['featured-label'] ) ? get_option('sbs_general')['featured-label'] : 'Featured Items';
	$req_label_before = isset( get_option('sbs_general')['req-label-before'] ) ? get_option('sbs_general')['req-label-before'] : 'Select';
	$req_label_after = isset( get_option('sbs_general')['req-label-after'] ) ? get_option('sbs_general')['req-label-after'] : '(Required)';
	$opt_label_before = isset( get_option('sbs_general')['opt-label-before'] ) ? get_option('sbs_general')['opt-label-before'] : '';
	$opt_label_after = isset( get_option('sbs_general')['opt-label-after'] ) ? get_option('sbs_general')['opt-label-after'] : '(Addons)';

	$input['featured-label'] = $featured_label;
	$input['req-label-before'] = $req_label_before;
	$input['req-label-after'] = $req_label_after;
	$input['opt-label-before'] = $opt_label_before;
	$input['opt-label-after'] = $opt_label_after;

	return $input;

}

function sbs_package_settings_sanitize( $input ) {

	$title_label = isset( get_option('sbs_package')['label'] ) ? get_option('sbs_package')['label'] : 'Step-By-Step Ordering';
	$calc_label = isset( get_option('sbs_package')['merch-cred-label'] ) ? get_option('sbs_package')['merch-cred-label'] : 'Merchandise Credit';
	$add_to_cart_text = isset( get_option('sbs_package')['add-to-cart-text'] ) ? get_option('sbs_package')['add-to-cart-text'] : 'Select Package';
	$per_row = isset( get_option('sbs_package')['per-row'] ) ? get_option('sbs_package')['per-row'] : '1';

	$input['label'] = $title_label;
	$input['merch-cred-label'] = $calc_label;
	$input['add-to-cart-text'] = $add_to_cart_text;
	$input['per-row'] = $per_row;

	return $input;
}

function sbs_navbar_settings_sanitize( $input ) {
	$throttle_nav = isset( get_option('sbs_navbar')['throttle-nav'] ) ? get_option('sbs_navbar')['throttle-nav'] : 2;
	$input['throttle-nav'] = $throttle_nav;

	return $input;
}

function sbs_display_settings_sanitize( $input ) {
	$calc_font = isset( get_option('sbs_display')['calc-font'] ) ? get_option('sbs_display')['calc-font'] : 1;
	$category_font = isset( get_option('sbs_display')['category-font'] ) ? get_option('sbs_display')['category-font'] : 1;
	$category_desc_font = isset( get_option('sbs_display')['category-desc-font'] ) ? get_option('sbs_display')['category-desc-font'] : 1;
	$nav_button_font = isset( get_option('sbs_display')['nav-button-font'] ) ? get_option('sbs_display')['nav-button-font'] : 1;
	$navbar_font = isset( get_option('sbs_display')['navbar-font'] ) ? get_option('sbs_display')['navbar-font'] : 1;
	$drop_shadow = isset( get_option('sbs_display')['drop-shadow'] ) ? get_option('sbs_display')['drop-shadow'] : false;

	$input['calc-font'] = $calc_font;
	$input['category-font'] = $category_font;
	$input['category-desc-font'] = $category_desc_font;
	$input['nav-button-font'] = $nav_button_font;
	$input['navbar-font'] = $navbar_font;
	$input['drop-shadow'] = $drop_shadow;

	return $input;
}

/**
 *  Options Form Output Callbacks
 *
 */
function sbs_premium_site_link() {
	ob_start();
	?>
	(<a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">Premium</a>)
	<?php
	return ob_get_clean();
}

function sbs_page_name_callback() {

	$pages = get_pages();

	$option = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;

	ob_start();
	?>
	<fieldset>
		<label>
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'The page where the Step-By-Step Ordering is located must be selected in order for navigation to work properly.<br>Page contents:<br>[sbs_woocommerce_step_by_step_ordering]'
			);
			?>
			<select id="sbs_page_name" name="sbs_general[page-name]">
				<option value="">(Select a Page)</option>
				<?php
				foreach( $pages as $page ):
				?>
				<option value="<?php echo esc_attr( $page->ID ) ?>" <?php echo selected( $page->ID, $option ) ?>><?php echo esc_html( $page->post_title ) ?></option>
				<?php
				endforeach;
				?>
			</select>
		</label>
	</fieldset>
	<?php

	echo ob_get_clean();
}

function sbs_widgets_callback() {
	ob_start();
	?>
		<p>
		To configure your sidebar in the ordering process, add the WooCommerce Cart Totals widget to your sidebar.<br>
		It is also recommended that you add the WooCommerce Cart Widget under the WooCommerce Cart Totals.<br>
		You can do so in your <strong><a rel="noopener noreferrer" target="_blank" href="<?php echo admin_url( 'widgets.php' ) ?>">Widgets</a></strong> page.
		</p>
	<?php

	echo ob_get_clean();
}

function sbs_apply_sbs_ui_outside_sbs_pages_callback() {
  $option = isset( get_option('sbs_general')['ui-outside-sbs'] ) ? get_option('sbs_general')['ui-outside-sbs'] : 'no';

  ob_start();
  ?>
  <fieldset>
    <label>
      <?php echo sbs_admin_help_tooltip(
        'top',
        'Clicking product images in catalogs opens a popup window instead of opening a new page.  If enabled, this will apply to all WooCommerce shop pages, not just Step-By-Step.'
      ); ?>
      <select name="sbs_general[ui-outside-sbs]">
        <option value="no" <?php selected('no', $option) ?>>Disabled</option>
        <option value="yes" <?php selected('yes', $option) ?>>Enabled</option>
      </select>
    </label>
  </fieldset>
  <?php

  echo ob_get_clean();
}

function sbs_hide_product_placeholder_image_callback() {
  $option = isset( get_option('sbs_general')['hide-placeholder-images'] ) ? get_option('sbs_general')['hide-placeholder-images'] : 'no';

  ob_start();
  ?>
  <fieldset>
    <label>
      <?php echo sbs_admin_help_tooltip(
        'top',
        'If enabled, products without images assigned to them will not display a placeholder image.'
      ); ?>
      <select name="sbs_general[hide-placeholder-images]">
        <option value="no" <?php selected('no', $option) ?>>Disabled</option>
        <option value="yes" <?php selected('yes', $option) ?>>Enabled</option>
      </select>
    </label>
  </fieldset>
  <?php

  echo ob_get_clean();
}

function sbs_featured_items_pos_callback() {

	$option = isset( get_option('sbs_general')['featured-items-position'] ) ? get_option('sbs_general')['featured-items-position'] : 2;

	ob_start();
	?>
		<fieldset>
			<label class="grayed-out-text">
				<input type="radio" name="sbs_general[featured-items-position]" value="1" <?php echo checked(1, $option) ?> <?php disabled( true, true ) ?>>
				Top
			</label><br />
			<label>
				<input type="radio" name="sbs_general[featured-items-position]" value="2" <?php echo checked(2, $option) ?>>
				Bottom
			</label>
		</fieldset>
	<?php

	echo ob_get_clean();

}

function sbs_featured_items_vis_callback() {

	$option = isset( get_option('sbs_general')['featured-items-visibility'] ) ? get_option('sbs_general')['featured-items-visibility'] : 1;

	ob_start();
	?>
	<fieldset>
		<label>
			<input type="radio" name="sbs_general[featured-items-visibility]" value="1" <?php checked(1, $option) ?>>
			Display featured products under both their parent category and the featured section.
		</label><br>
		<label>
			<input type="radio" name="sbs_general[featured-items-visibility]" value="2" <?php checked(2, $option) ?>>
			Display featured products in the featured section only.
		</label>
	</fieldset>
	<?php

	echo ob_get_clean();

}

function sbs_req_feat_label_callback() {

	$featured_label = isset( get_option('sbs_general')['featured-label'] ) ? get_option('sbs_general')['featured-label'] : 'Featured Items';
	$req_label_before = isset( get_option('sbs_general')['req-label-before'] ) ? get_option('sbs_general')['req-label-before'] : 'Select';
	$req_label_after = isset( get_option('sbs_general')['req-label-after'] ) ? get_option('sbs_general')['req-label-after'] : '(Required)';
	$opt_label_before = isset( get_option('sbs_general')['opt-label-before'] ) ? get_option('sbs_general')['opt-label-before'] : '';
	$opt_label_after = isset( get_option('sbs_general')['opt-label-after'] ) ? get_option('sbs_general')['opt-label-after'] : '(Addons)';

	ob_start();
	?>
		<fieldset class="grayed-out-text">
			<span>
				<label>
					<strong>"Required Items" Section Title:</strong>
					<?php
					echo sbs_admin_help_tooltip(
						'top',
						'Products with the "Required" attribute are displayed in their own sections.  You can add text before and after the category name.'
					);
					?>
				</label><br />
				<label>
					Before Category Name:
					<input type="text" name="sbs_general[req-label-before]" value="<?php echo $req_label_before ?>" <?php disabled( true, true ) ?>/>
				</label><br />
				<label>
					After Category Name:
					<input type="text" name="sbs_general[req-label-after]" value="<?php echo $req_label_after ?>" <?php disabled( true, true ) ?> />
				</label><br />
			</span>
			<span>
				<label>
					<strong>"Optional Items" Section Title:</strong>
					<?php
					echo sbs_admin_help_tooltip(
						'top',
						'Products that do not have the "Required" attribute are displayed separately from those that do. You can add text before and after the category name.'
					);
					?>
				</label><br />
				<label>
					Before Category Name:
					<input type="text" name="sbs_general[opt-label-before]" value="<?php echo $opt_label_before ?>" <?php disabled( true, true ) ?> />
				</label><br />
				<label>
					After Category Name:
					<input type="text" name="sbs_general[opt-label-after]" value="<?php echo $opt_label_after ?>" <?php disabled( true, true ) ?> />
				</label><br />
			</span>
			<span>
				<label>
					<strong>"Featured Items" Section Title:</strong>
					<?php
					echo sbs_admin_help_tooltip(
						'top',
						'Featured Products are products with the "Featured" tag selected from the Products list.  You can name your featured section title anything you would like.'
					);
					?>
				</label><br />
				<label>
					<input type="text" name="sbs_general[featured-label]" value="<?php echo $featured_label ?>" <?php disabled( true, true ) ?> />
				</label><br />
			</span>
		</fieldset>
	<?php

	echo ob_get_clean();

}

function sbs_sbs_table_callback() {

  // get_the_category_by_ID() only works if this function is called for some reason
  $available_categories = sbs_get_all_wc_categories();

	$step_order = sbs_get_step_order( true );

	// Categories listed in the ordering process should not be listed in Available Categories
	// to prevent duplication

	$available_categories = array_filter( $available_categories, function( $category ) {

		$step_order = sbs_get_step_order( true );
		$package_cat = isset( get_option('sbs_package')['category'] ) ? get_option('sbs_package')['category'] : null;

		$flat_step_order = array();

    if ( $category->term_id == $package_cat || $category->term_id == $option_cat ) {
      return false;
    }

		if ( empty( $step_order ) ) {
			return true;
		}

		foreach( $step_order as $step ) {
			$flat_step_order[] = $step->catid;
			foreach ($step->children as $child) {
				$flat_step_order[] = $child->catid;
			}
		}

    // Do not list categories that are selected in the SBS order, is the package category, or the options and fees category
		return !in_array( $category->term_id, $flat_step_order ) && $category->term_id != $package_cat && $category->term_id != $option_cat;

	} );

  ob_start();
  ?>
	<p><strong style="color: red; font-size: 1.2em;">
		You may have up two steps active at a time in the free version of this plugin. Only the first two subcategories in each step will be displayed.<br>With the Premium version of SBS, you can add as many steps as you would like.  You can try Step-By-Step Premium for FREE! by visiting our website <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">here.</a>
	</strong></p>
  <div class="container-fluid">
    <div class="row">
      <div class="col-sm-6">

        <div class="sortable-container" id="sbs-order-container">
          <h3>Your Ordering Process</h3>
          <div class="fixed-item noselect">Package Selection</div>
          <ul id="sbs-order" class="sortable step-sortable">

            <?php
            if ( isset( $step_order ) )
            {
              foreach( $step_order as $category )
              {
              ?>
                <li data-catid="<?php echo $category->catid ?>" class="sortable-item" parent-id="<?php echo get_category($category->catid)->category_parent ?>">
                  <?php echo get_the_category_by_ID( $category->catid ) ?>
                  <div class="alignright">
                    <span class="sbs-sortable-item-move-up">&#9650;</span>
                    <span class="sbs-sortable-item-move-down">&#9660;</span>
                    <span class="sbs-sortable-item-add">&#10133;</span>
                    <span class="sbs-sortable-item-remove">&#10006;</span>
                  </div>
                  <div class="clearfix"></div>
                  <ul class="subcat-restricted">
                    <?php
                    foreach( $category->children as $child )
                    {
                    ?>
                      <li class="sortable-item sortable-nested-item" data-catid="<?php echo $child->catid ?>" parent-id="<?php echo get_category($child->catid)->category_parent ?>">
                        <span class="subcat-name">
                          <?php echo get_the_category_by_ID( $child->catid ) ?>
                        </span>
                        <div class="alignright">
                          <span class="sbs-sortable-item-move-up">&#9650;</span>
                          <span class="sbs-sortable-item-move-down">&#9660;</span>
                        </div>
                        <div class="clearfix"></div>
                      </li>
                    <?php
                    }
                    ?>
                  </ul>
                </li>
              <?php
              }
            }
            ?>

          </ul>

          <div class="fixed-item noselect">Checkout</div>
        </div>

      </div>

      <div class="col-sm-6">

        <div class="sortable-container" id="sbs-pool-container">
          <h3>Available Categories</h3>
          <ul id="sbs-pool" class="sortable">
            <?php foreach( $available_categories as $category ): ?>

              <?php if ( $category->category_parent === 0 ): ?>

                <li data-catid="<?php echo $category->term_id ?>" class="sortable-item" parent-id="<?php echo $category->category_parent ?>">
                  <?php echo $category->name ?>
                  <div class="alignright">
                    <span class="sbs-sortable-item-move-up">&#9650;</span>
                    <span class="sbs-sortable-item-move-down">&#9660;</span>
                    <span class="sbs-sortable-item-add">&#10133;</span>
                    <span class="sbs-sortable-item-remove">&#10006;</span>
                  </div>
                  <div class="clearfix"></div>
                  <ul>
                    <?php $children = get_term_children( $category->term_id, 'product_cat' ); ?>
                    <?php if ( !empty( $children ) ): ?>
                      <?php foreach( $children as $child_id ): ?>

                        <li data-catid="<?php echo $child_id ?>" class="sortable-item" parent-id="<?php echo $category->term_id ?>">
                          <?php echo get_the_category_by_ID( $child_id ) ?>
                          <div class="alignright">
                            <span class="sbs-sortable-item-move-up">&#9650;</span>
                            <span class="sbs-sortable-item-move-down">&#9660;</span>
                          </div>
                          <div class="clearfix"></div>
                        </li>

                      <?php endforeach; ?>
                    <?php endif; ?>
                  </ul>

                </li>

              <?php endif; ?>

            <?php endforeach; ?>
          </ul>
        </div>

      </div>
    </div>
  </div>

  <input type="hidden" id="step_order" name="step_order" value="<?php echo esc_attr( get_option('step_order') ) ?>" />
  <?php

  echo ob_get_clean();

}


function sbs_navbar_navigation_callback() {

	$option = isset( get_option('sbs_navbar')['throttle-nav'] ) ? get_option('sbs_navbar')['throttle-nav'] : 2;

	ob_start();
	?>
	<fieldset>
		<label>
			<input type="radio" id="step_navbar_navigation_2" name="sbs_navbar[throttle-nav]" value="2" <?php checked( 2, $option ) ?> />
			Only allow forward navigation one step a time, but let users backtrack to
			any step.
		</label><br />
		<label class="grayed-out-text">
			<input type="radio" id="step_navbar_navigation_1" name="sbs_navbar[throttle-nav]" value="1" <?php checked( 1, $option ) ?> <?php disabled( true, true ) ?>/>
			Only allow navigation one step at a time in any direction <?php echo sbs_premium_site_link() ?>
		</label><br />

		<label class="grayed-out-text">
			<input type="radio" id="step_navbar_navigation_3" name="sbs_navbar[throttle-nav]" value="3" <?php checked( 3, $option ) ?> <?php disabled( true, true ) ?>/>
			Users may freely navigate, skipping any step they'd like. <?php echo sbs_premium_site_link() ?>
		</label><br />
	</fieldset>

	<?php

	echo ob_get_clean();

}


function sbs_package_enable_callback() {

	$option = isset( get_option('sbs_package')['enabled'] ) ? get_option('sbs_package')['enabled'] : '1';

	ob_start();
	?>
	<fieldset>
		<label>
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'Enables the use of packages for the SBS system.  If deactivated, the package page will be replaced with a notice that links to Step 1. You can link directly to Step 1 copying the link from the address bar or typing "echo sbs_get_begin_url()" in your PHP code.'
			);
			?>
			<select id="sbs_package[enabled]" name="sbs_package[enabled]">
				<option value="1" <?php selected(1, $option) ?>>Activated</option>
				<option value="0" <?php selected(0, $option) ?>>Deactivated</option>
			</select>
		</label>
	</fieldset>
	<?php

	echo ob_get_clean();
}


function sbs_package_calc_label_callback() {

	$option = isset( get_option('sbs_package')['label'] ) ? get_option('sbs_package')['label'] : 'Step-By-Step Ordering';

	ob_start();
	?>
	<fieldset>
		<label>
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'Appears if "Deactivated" was selected above.'
			);
			?>
			<input style="width: 240px;" type="text" id="sbs_package[label]" name="sbs_package[label]" value="<?php echo $option ?>" <?php disabled( true, true ) ?>/>
		</label>
	</fieldset>
	<?php

	echo ob_get_clean();
}


function sbs_package_page_callback() {

	$option = isset( get_option('sbs_package')['page-name'] ) ? get_option('sbs_package')['page-name'] : get_page_by_title( 'Choose Package' )->ID;

	$pages = get_pages();

	ob_start();
	?>
	<fieldset>
		<label>
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'The page where the Select Packages page is located must be selected in order for navigation to work properly.<br>Page contents:<br>[sbs_select_package]'
			);
			?>
			<select id="sbs_package[page-name]" name="sbs_package[page-name]">
				<?php foreach( $pages as $page ): ?>
				<option value="<?php echo esc_attr( $page->ID ) ?>" <?php echo selected( $page->ID, $option ) ?>>
					<?php echo esc_html( $page->post_title ) ?>
				</option>
				<?php endforeach ?>
			</select>
		</label>
	</fieldset>
	<?php

	echo ob_get_clean();
}


function sbs_package_category_callback() {
	$wc_categories = sbs_get_all_wc_categories();
	ob_start();
	?>
		<fieldset>
			<label for="select-package-category">
				<?php
				echo sbs_admin_help_tooltip(
					'top',
					'Select the WooCommerce product category your packages are assigned to.<br />
					You must click Save Changes afterwards in order to refresh the package list.'
				);
				?>
				<select id="select-package-category" name="sbs_package[category]">
					<option value="">Select One</option>
					<?php
					foreach( $wc_categories as $category )
					{
					?>
						<option value="<?php echo $category->term_id ?>" <?php selected( $category->term_id, get_option('sbs_package')['category'] ) ?>>
							<?php echo $category->name ?>
						</option>
					<?php
					}
					?>
				</select>
			</label>
		</fieldset>

		<?php submit_button() ?>
	<?php

	echo ob_get_clean();
}


function sbs_package_merch_cred_callback() {

	$wc_attributes = wc_get_attribute_taxonomies();
	$calc_label = isset( get_option('sbs_package')['merch-cred-label'] ) ? get_option('sbs_package')['merch-cred-label'] : 'Merchandise Credit';

	ob_start();
	?>
	<fieldset>
		<label>
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'If your package has a store credit, the following text will be displayed on the widget calculator.  You can change this title to anything you would like.'
			);
			?>
			<input style="width: 240px;" type="text" name="sbs_package[merch-cred-label]" value="<?php echo $calc_label ?>" <?php disabled( true, true ) ?>/>
		</label>
	</fieldset>

	<?php
	echo ob_get_clean();
}


function sbs_package_tier_callback() {

	$package_cat_id = get_option('sbs_package')['category'];

	if ( !empty( $package_cat_id ) ) {
		$all_packages = sbs_get_wc_products_by_category( $package_cat_id );
		$active_packages = sbs_get_active_packages( true );

		$available_packages = array_filter( $all_packages, function( $package ) {

			$active_packages = sbs_get_active_packages( true );

			if ( isset( $active_packages ) ) {
				$active_packages = array_map( function( $package ) {
					return $package->catid;
				}, $active_packages);
			} else {
				$active_packages = array();
			}

			return !in_array( $package->ID, $active_packages );

		} );
	}

	ob_start();
	?>

	<?php if ( empty( $package_cat_id ) ): ?>

		<p>Select a package category above to begin.</p>

	<?php else: ?>
		<p>
			Drag (or touch the control buttons on the right side of each item) packages from the Available Packages box to the Active Packages here to build your Package Selection page.  You can rearrange the packages to change
			the order in which they are displayed.<br>
			<strong style="color: red; font-size: 1.2em;">You may have up to one package in the free version of this plugin.<br>With the Premium version of SBS, you can add as many packages as you would like.  You can try Step-By-Step Premium for FREE! by visiting our website <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">here.</a></strong>
		</p>
    <div class="container-fluid">
      <div class="row">
        <div class="col-sm-6">

          <div class="sortable-container" id="sbs-order-container">
            <h3>Active Packages</h3>
            <ul id="sbs-order" class="sortable package-sortable">
              <?php
              if ( isset( $active_packages ) )
              {
                foreach( $active_packages as $package )
                {
                ?>
                <li data-catid="<?php echo $package->catid ?>" class="sortable-item">
                  <?php echo get_the_title( $package->catid ) ?>
                  <div class="alignright">
                    <span class="sbs-sortable-item-move-up">&#9650;</span>
                    <span class="sbs-sortable-item-move-down">&#9660;</span>
                    <span class="sbs-sortable-item-add">&#10133;</span>
                    <span class="sbs-sortable-item-remove">&#10006;</span>
                  </div>
                  <div class="clearfix"></div>
                </li>
                <?php
                }
              }
              ?>
            </ul>
          </div>

        </div>

        <div class="col-sm-6">

          <div class="sortable-container" id="sbs-pool-container">
            <h3>Available Packages</h3>
            <ul id="sbs-pool" class="sortable">
              <?php
              if ( isset( $package_cat_id ) ) {
                foreach( $available_packages as $package )
                {
                ?>
                  <li data-catid="<?php echo $package->ID ?>" class="sortable-item">
                    <?php echo $package->post_title ?>
                    <div class="alignright">
                      <span class="sbs-sortable-item-move-up">&#9650;</span>
                      <span class="sbs-sortable-item-move-down">&#9660;</span>
                      <span class="sbs-sortable-item-add">&#10133;</span>
                      <span class="sbs-sortable-item-remove">&#10006;</span>
                    </div>
                    <div class="clearfix"></div>
                  </li>
                <?php
                }
              }
              ?>
            </ul>
          </div>

        </div>
      </div>
    </div>

		<input type="hidden" id="step_order" name="sbs_package[active]" value="<?php echo esc_attr( get_option('sbs_package')['active'] ) ?>" />

	<?php
	endif;

	echo ob_get_clean();
}

function sbs_package_atc_callback() {

	$option = isset( get_option('sbs_package')['clear-cart'] ) ? get_option('sbs_package')['clear-cart'] : '1';

	ob_start();
	?>
	<fieldset>
		<label>
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'When a package is selected or changed in the SBS system, this is the action taken when a package is added to the cart. The default behavior is to clear the cart so the user has a fresh experience.'
			);
			?>
			<select id="sbs_package[clear-cart]" name="sbs_package[clear-cart]">
				<option value="1" <?php selected(1, $option) ?>>Clear the cart when a package is selected</option>
				<option value="2" <?php selected(2, $option) ?>>Do not clear the cart when a package is selected</option>
			</select>
		</label>
	</fieldset>
	<?php

	echo ob_get_clean();

}


function sbs_package_select_style_callback() {

	$per_row = isset( get_option('sbs_package')['per-row'] ) ? get_option('sbs_package')['per-row'] : 1;

	$per_row_options = array( 1, 2, 3, 4, 5 );

	$add_to_cart_text = isset( get_option('sbs_package')['add-to-cart-text'] ) ? get_option('sbs_package')['add-to-cart-text'] : 'Select Package';

	ob_start();
	?>
	<fieldset>
		<label class="grayed-out-text">
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'On your package selection page, select the amount of packages to display per row.  This applies to desktop displays. Mobile displays may collapse to one package per row.'
			);
			?>
			Number of packages to display per row:
			<select id="sbs-package-per-row" name="sbs_package[per-row]" <?php disabled( true, true ) ?>>
			<?php
			foreach ( $per_row_options as $option )
			{
			?>
				<option value="<?php echo $option ?>" <?php selected( $option, $per_row ) ?>>
					<?php echo $option ?>
				</option>
			<?php
			}
			?>
			</select>
		</label><br />

		<label class="grayed-out-text">
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'Customize the text on the Add To Cart button on packages.'
			);
			?>
			"Add to Cart" Text <?php echo sbs_premium_site_link() ?>:
			<input type="text" id="sbs-package-add-cart-label" name="sbs_package[add-to-cart-text]" value="<?php echo $add_to_cart_text ?>" placeholder='Default: "Select Package"' <?php disabled( true, true ) ?>/>
		</label>

		<p>
			Package Image Custom Size:
		</p>
		<label>
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'Maximum image size is limited by the width of the package selection box and the original image size.'
			);
			?>
			Height (px):
			<input type="number" min="0" step="1" id="sbs_package[image-height]" name="sbs_package[image-height]" value="<?php echo get_option('sbs_package')['image-height'] ?>">
		</label>
		<label>
			Width (px):
			<input type="number" min="0" step="1" id="sbs_package[image-width]" name="sbs_package[image-width]" value="<?php echo get_option('sbs_package')['image-width'] ?>">
		</label>

	</fieldset>

	<?php

	echo ob_get_clean();

}

function sbs_display_color_scheme_callback() {

	$option = isset( get_option('sbs_display')['color-scheme'] ) ? get_option('sbs_display')['color-scheme'] : 1;
	$image_dir = plugin_dir_url( SBS_PLUGIN_FILE ) . 'assets/admin/color-schemes/';

	$colors = array(
		array(
			'name' => "Use your theme's colors (Default)",
			'premium' => false,
			'image' => null ),
		array(
			'name' => "Noir 1",
			'premium' => false,
			'image' => $image_dir . 'sbs-theme-noir-1.jpg' ),
		array(
			'name' => "Royal 1",
			'premium' => false,
			'image' => $image_dir . 'sbs-theme-royal-1.jpg' ),
		array(
			'name' => "Spring Green",
			'premium' => true,
			'image' => $image_dir . 'sbs-theme-green-1.jpg' ),
		array(
			'name' => "Aqua Green",
			'premium' => true,
			'image' => $image_dir . 'sbs-theme-green-2.jpg' ),
		array(
			'name' => "Autumn 1",
			'premium' => true,
			'image' => $image_dir . 'sbs-theme-autumn-1.jpg' ),
		array(
			'name' => "Autumn 2",
			'premium' => true,
			'image' => $image_dir . 'sbs-theme-autumn-2.jpg' ),
		array(
			'name' => "Neon",
			'premium' => true,
			'image' => $image_dir . 'sbs-theme-neon.jpg' ),
		array(
			'name' => "Neon Gradient",
			'premium' => true,
			'image' => $image_dir . 'sbs-theme-neon-gradient.jpg' ),
		array(
			'name' => "Noir 2",
			'premium' => true,
			'image' => $image_dir . 'sbs-theme-noir-2.jpg' ),
		array(
			'name' => "Royal 2",
			'premium' => true,
			'image' => $image_dir . 'sbs-theme-royal-2.jpg' )
	);

  ob_start();
  ?>
	<fieldset>
		<?php
		echo sbs_admin_help_tooltip(
			'top',
			'Colors buttons, navbars, headers, and the calculator with preset CSS themes.'
		);
		?>
		<select id="sbs_display[color-scheme]" name="sbs_display[color-scheme]">
		<?php
		foreach( $colors as $key => $color )
		{
		?>
	    <option value="<?php echo $key + 1 ?>" <?php echo selected( $key + 1, $option, false ) ?> <?php disabled( true, $color['premium'] ) ?>>
				<?php echo esc_html( $color['name'] ) ?>
				<?php echo $color['premium'] ? ' ' . sbs_premium_site_link() : null ?>
			</option>
		<?php
		}
		?>
		</select>
	</fieldset>

	<div class="sbs-display-thumbnail-wrap">
		<?php
		foreach( $colors as $key => $color ):
		if ( $key === 0 ) continue;
		?>
		<div class="sbs-display-thumbnail-item">

			<div class="sbs-display-thumbnail-img">
				<a href="<?php echo esc_url( $color['image'] . '?width=600&height=500&inlineId=color-scheme-' . $key ) ?>" title="<?php echo esc_attr( $color['name'] ) ?>" class="thickbox" rel="color-schemes">
					<img height="80" width="100" src="<?php echo esc_url( $color['image'] ) ?>" /><br>
					<small><?php echo esc_attr( $color['name'] ) ?></small>
				</a>
			</div>

			<div id="color-scheme-<?php echo $key ?>" style="display: none;">
				<img src="<?php echo esc_url( $color['image'] ) ?>" alt="<?php echo esc_attr( $color['name'] ) ?>" />
			</div>

		</div>
		<?php
		endforeach;
		?>
	</div>
  <?php

  echo ob_get_clean();
}

function sbs_display_calc_callback() {
	$calc_borders = isset( get_option('sbs_display')['calc-borders'] ) ? get_option('sbs_display')['calc-borders'] : false;
	$calc_font = isset( get_option('sbs_display')['calc-font'] ) ? get_option('sbs_display')['calc-font'] : 1;

	$merch_cred_display_align = isset( get_option('sbs_display')['merch-cred-display'] ) ? get_option('sbs_display')['merch-cred-display'] : 1;

	$fonts = array(
		"Theme Default",
		'Helvetica',
		'Arial',
		'Verdana'
	);

	ob_start();
	?>
		<div>
			<fieldset class="grayed-out-text">
				<label>
					<p><strong>Font Family <?php echo sbs_premium_site_link() ?></strong></p>
					<select id="sbs_display[calc-font]" name="sbs_display[calc-font]" <?php disabled( true, true ) ?>>
					<?php
					foreach( $fonts as $key => $font )
					{
					?>
						<option value="<?php echo $key + 1 ?>" <?php selected( $key + 1, $calc_font ) ?>>
							<?php echo $font ?>
						</option>
					<?php
					}
					?>
					</select>
				</label>
			</fieldset>
		</div>
		<div>
			<fieldset>
				<p><strong>Other Styles</strong></p>
				<label>
					<input type="checkbox" id="show_calc_borders" name="sbs_display[calc-borders]" value="1" <?php checked(1, $calc_borders) ?> />
					Show a vertical border separating the category column and the price column
				</label><br>
				<label>
					Store Credit Display:
					<?php
					echo sbs_admin_help_tooltip(
						'top',
						'Select the text alignment of package store credit in the calculator, if any.'
					);
					?>
					<select id="sbs_display[merch-cred-display]" name="sbs_display[merch-cred-display]">
						<option value="1" <?php selected(1, $merch_cred_display_align) ?>>Align label and credit value left and right</option>
						<option value="2" <?php selected(2, $merch_cred_display_align) ?>>Align label and credit value to the center</option>
					</select>
			</fieldset>
		</div>
	<?php
}


function sbs_display_fonts_callback() {

	$category_font = isset( get_option('sbs_display')['category-font'] ) ? get_option('sbs_display')['category-font'] : 1;
	$category_desc_font = isset( get_option('sbs_display')['category-desc-font'] ) ? get_option('sbs_display')['category-desc-font'] : 1;
	$nav_button_font = isset( get_option('sbs_display')['nav-button-font'] ) ? get_option('sbs_display')['nav-button-font'] : 1;
	$navbar_font = isset( get_option('sbs_display')['navbar-font'] ) ? get_option('sbs_display')['navbar-font'] : 1;

	$fonts = array(
		'Theme Default',
		'Helvetica',
		'Arial',
		'Verdana',
		'Georgia',
		'Lucida',
		'Palatino'
	);

	$sections = array(
		array( 'title' => 'Subcategory Name ' . sbs_premium_site_link(), 'slug' => 'category-font', 'option' => $category_font, 'tooltip' => 'Select section name fonts for displayed on each page.' ),
		array( 'title' => 'Subcategory Description ' . sbs_premium_site_link(), 'slug' => 'category-desc-font', 'option' => $category_desc_font, 'tooltip' => 'Select the font for the description under each section name.' ),
		array( 'title' => 'Nav Buttons ' . sbs_premium_site_link(), 'slug' => 'nav-button-font', 'option' => $nav_button_font, 'tooltip' => 'Select the font for the Back/Foward buttons on each page.' ),
		array( 'title' => 'Navbar ' . sbs_premium_site_link(), 'slug' => 'navbar-font', 'option' => $navbar_font, 'tooltip' => "Select the font for the bar at the top of each page displaying the customer's progress during ordering." ),
	);

	ob_start();
	?>

	<?php
	foreach ( $sections as $section )
	{
	?>
	<fieldset class="grayed-out-text">
		<label>
			<div>
				<strong><?php echo $section['title'] ?></strong>
				<?php
				echo sbs_admin_help_tooltip(
					'top',
					esc_html( $section['tooltip'] )
				);
				?>
			</div>
			<select id="sbs_display[<?php echo $section['slug'] ?>]" name="sbs_display[<?php echo $section['slug'] ?>]" <?php disabled( true, true ) ?>>
			<?php
			foreach ( $fonts as $key => $font )
			{
				$index = $key + 1;
			?>
				<option
					value="<?php echo $index ?>"
					<?php selected( $index, $section['option'] ) ?>
					>
					<?php echo $font ?>
				</option>
			<?php
			}
			?>
			</select>
		</label>
	</fieldset>
	<?php
	}

}



function sbs_display_misc_callback() {
	$hover_effect = isset( get_option('sbs_display')['hover-effect'] ) ? get_option('sbs_display')['hover-effect'] : false;
	$drop_shadow = isset( get_option('sbs_display')['drop-shadow'] ) ? get_option('sbs_display')['drop-shadow'] : false;

	ob_start();
	?>
	<fieldset>
		<label>
			<input type="checkbox" id="show_hover_effect" name="sbs_display[hover-effect]" value="1" <?php checked(1, $hover_effect) ?> />
			SBS links turn different colors when moused over (varies by theme)
		</label><br />
		<label class="grayed-out-text">
			<input type="checkbox" id="sbs_display[drop-shadow]" name="sbs_display[drop-shadow]" value="1" <?php checked( 1, $drop_shadow ); disabled( true, true ); ?> />
			Add drop shadows to SBS pages <?php echo sbs_premium_site_link() ?>
		</label>
	</fieldset>
	<?php

	echo ob_get_clean();
}

function sbs_display_sidebar_calculator_callback() {
  ob_start();
  ?>
    <input type="checkbox" id="show_calculator" name="sbs_display[show-calculator]" value="1" <?php echo checked(1, get_option('sbs_display')['show-calculator'], false) ?> />
  <?php

  echo ob_get_clean();
}

function sbs_display_navbar_number_shape_callback() {
	$number_style = isset( get_option('sbs_display')['navbar-style'] ) ? get_option('sbs_display')['navbar-style'] : 1;
	$image_dir = plugin_dir_url( SBS_PLUGIN_FILE ) . 'assets/admin/nav-num-shapes/';

	$styles = array(
		array(
			'name' => 'Square (Default)',
			'premium' => false,
		 	'image' => $image_dir . 'default.png'),
		array(
			'name' => 'Circle',
			'premium' => false,
		 	'image' => $image_dir . 'circle.png' ),
		array(
			'name' => 'Upward Triangle',
			'premium' => false,
		 	'image' => $image_dir . 'upward-triangle.png' ),
		array(
			'name' => 'Downward Triangle',
			'premium' => true,
		 	'image' => $image_dir . 'downward-triangle.png' ),
		array(
			'name' => 'Heart',
			'premium' => true,
		 	'image' => $image_dir . 'heart.png' ),
		array(
			'name' => '12-Pointed Star',
			'premium' => true,
		 	'image' => $image_dir . 'twelve-star.png' ),
		array(
			'name' => 'Kite',
			'premium' => true,
		 	'image' => $image_dir . 'kite.png' ),
		array(
			'name' => 'Badge Ribbon',
			'premium' => true,
		 	'image' => $image_dir . 'badge-ribbon.png' )
	);

  ob_start();
	?>
	<fieldset>
		<?php
		echo sbs_admin_help_tooltip(
			'top',
			'Change the shape of the step number in the navbar.'
		);
		?>
		<select id="sbs_display[navbar-style]" name="sbs_display[navbar-style]">
		<?php
		foreach ( $styles as $key => $style )
		{
			$index = $key + 1;
		?>
			<option value="<?php echo $index ?>" <?php echo selected( $index, $number_style, false) ?> <?php disabled( true, $style['premium'] ) ?>>
				<?php echo $style['name'] ?>
				<?php echo $style['premium'] ? ' ' . sbs_premium_site_link() : null ?>
			</option>
  	<?php
		}
		?>
		</select>
	</fieldset>

	<div class="sbs-display-thumbnail-wrap">
		<?php
		foreach( $styles as $key => $style ):
		?>
		<div class="sbs-display-thumbnail-item">

			<div class="sbs-display-thumbnail-img">
				<a href="<?php echo esc_url( $style['image'] . '?width=600&height=500&inlineId=nav-num-' . $key ) ?>" title="<?php echo esc_attr( $style['name'] ) ?>" class="thickbox" rel="nav-num-shapes">
					<img width="100" src="<?php echo esc_url( $style['image'] ) ?>" /><br>
					<small><?php echo esc_attr( $style['name'] ) ?></small>
				</a>
			</div>

			<div id="nav-num-<?php echo $key ?>" style="display: none;">
				<img src="<?php echo esc_url( $style['image'] ) ?>" alt="<?php echo esc_attr( $style['name'] ) ?>" />
			</div>

		</div>
		<?php
		endforeach;
		?>
	</div>
	<?php

  echo ob_get_clean();
}

function sbs_display_navbar_title_shape_callback() {
	$title_style = isset( get_option('sbs_display')['nav-title-style'] ) ? get_option('sbs_display')['nav-title-style'] : 1;
	$image_dir = plugin_dir_url( SBS_PLUGIN_FILE ) . 'assets/admin/nav-step-shapes/';

	$styles = array(
		array(
			'name' => 'Rectangular (Default)',
			'premium' => false,
		 	'image' => $image_dir . 'default.png' ),
		array(
			'name' => 'Capsule',
			'premium' => false,
		 	'image' => $image_dir . 'capsule.png' ),
		array(
			'name' => 'Arrows',
			'premium' => true,
		 	'image' => $image_dir . 'arrows.png' ),
		array(
			'name' => 'TV Screen',
			'premium' => true,
		 	'image' => $image_dir . 'tv-screen.png' ),
		array(
			'name' => 'Parallelogram',
			'premium' => true,
		 	'image' => $image_dir . 'parallelogram.png' )
	);

	ob_start();
	?>
	<fieldset>
		<?php
		echo sbs_admin_help_tooltip(
			'top',
			'Change the shape of the step names in the navbar.'
		);
		?>
		<select id="sbs_display[nav-title-style]" name="sbs_display[nav-title-style]">
		<?php
		foreach ($styles as $key => $style)
		{
			$index = $key + 1;
		?>
			<option value="<?php echo $index ?>" <?php selected( $index, $title_style ) ?> <?php disabled( true, $style['premium'] ) ?>>
				<?php echo $style['name'] ?>
				<?php echo $style['premium'] ? ' ' . sbs_premium_site_link() : null ?>
			</option>
		<?php
		}
		?>
		</select>
	</fieldset>

	<div class="sbs-display-thumbnail-wrap">
		<?php
		foreach( $styles as $key => $style ):
		?>
		<div class="sbs-display-thumbnail-item">

			<div class="sbs-display-thumbnail-img">
				<a href="<?php echo esc_url( $style['image'] . '?width=600&height=500&inlineId=nav-step-' . $key ) ?>" title="<?php echo esc_attr( $style['name'] ) ?>" class="thickbox" rel="nav-step-shapes">
					<img width="100" src="<?php echo esc_url( $style['image'] ) ?>" /><br>
					<small><?php echo esc_attr( $style['name'] ) ?></small>
				</a>
			</div>

			<div id="nav-step-<?php echo $key ?>" style="display: none;">
				<img src="<?php echo esc_url( $style['image'] ) ?>" alt="<?php echo esc_attr( $style['name'] ) ?>" />
			</div>

		</div>
		<?php
		endforeach;
		?>
	</div>
	<?php

	echo ob_get_clean();
}

function sbs_render_premium_page() {
  $trial_img_src = plugin_dir_url( SBS_PLUGIN_FILE ) . 'assets/admin/pro-trial.jpg';
  ob_start();
  ?>
  <div class="wrap" style="max-width: 1080px;">
    <h2 style="text-align: center; margin-bottom: 2em;"><strong>Step-By-Step Premium Upgrade</strong></h2>
    <a href="http://stepbystepsys.com"><img src="<?php echo esc_url( $trial_img_src ) ?>" class="img-responsive center-block" style="margin-bottom: 2em;"></a>
    <div>
      <p style="font-size: 1.2em;">If you like our plugin and would like to see more content from us please consider purchasing a license from us. Premium users get additional features like unlimited steps, navigation options, required products, either-or products, package store credit, preset themes, and much more! You will also have access to our support team!</p>
      <p style="font-size: 1.2em;">After purchasing the premium version, you will download it from our website. You will receive a license key, and this key will be sent to your provided email. Enter this key into the input box in the Premium tab in the Step-By-Step Ordering settings and then click Activate.</p>
      <p style="font-size: 1.2em;">If you have this version of the SBS plugin installed (SBS Light), then you will need to uninstall this version, and then install the premium version you received from our website.</p>
      <p style="font-size: 1.2em;">The premium version of this plugin can be purchased directly at <a href="http://stepbystepsys.com"><strong>our website</strong></a>.</p>
      <p style="font-size: 1.2em;"><span style="font-size: 1.4em"><strong>We offer a 14-day FREE trial</strong></span> of the premium version. Along with unlimited packages, we offer the ability to add a store (merchandise credit) to your package(s). This is a great selling tool. For more info, visit <a href="http://stepbystepsys.com"><strong>our website</strong></a> for a full list of features in the premium version and try the premium version for free!</p>
    </div>
  </div>
  <?php
  echo ob_get_clean();
}

function sbs_render_admin_help_page() {
	ob_start();
	?>
	<div class="wrap" style="max-width: 1080px">
		<h2>Help</h2>

		<h3>The Step-By-Step Ordering System For WooCommerce</h3>

		<p>A configurable step-by-step ordering e-commerce ordering system to enhance what
		you sell using WooCommerce.  Enhance your customer experience and drive more
		sales to your online shopping.  To get started review the documentation below or visit our website at <a rel="nofollow noreferrer" target="_blank" href="http://stepbystepsys.com">http://stepbystepsys.com</a>.</p>

		<section>
			<h3>Table of Contents</h3>
			<ul style="list-style-type: square; padding-left: 40px;">
				<li><a href="#installation">Installation</a></li>
				<li><a href="#packages">Packages</a></li>
				<li><a href="#widgets">Widgets</a></li>
				<li><a href="#stepbystep">Step-By-Step</a></li>
				<li><a href="#themes">Themes</a></li>
				<li><a href="#premium">Premium</a></li>
        <li><a href="#uninstall">Uninstalling</a></li>
			</ul>
		</section>

		<h3 id="installation">Installation</h3>

		<ol>
			<li>Install WooCommerce. WooCommerce is required for this plugin.</li>
			<li>Install the Step-By-Step plugin.</li>
			<li>Access the settings page found <a target="_blank" rel="noreferrer noopener" href="http://stepbystepsys.com/wp-content/uploads/2017/06/admin-sidebar.png">here</a>.</li>
		</ol>

		<h3 id="packages">Packages</h3>

		<ol>
			<li>
				When you installed the plugin, a Packages category was automatically created
				and is selected by default to display in the Choose Package Page.
				If you want a different category to do this, select the desired category
				under the Package Category field in Package Settings.
				(<a target="_blank" rel="noreferrer noopener" href="http://stepbystepsys.com/wp-content/uploads/2017/06/package-category.png">View Image</a>)
			</li>
			<li>
				Create some products. These will be your Packages. Make sure the Package
				Category is selected.
				(<a target="_blank" rel="noreferrer noopener" href="http://stepbystepsys.com/wp-content/uploads/2017/06/new-product.png">View Image</a>)
			</li>
			<li>
				If you have purchased a Premium License of this plugin you can assign Store
				Credit or Merchanise Credit that will be applied when it's added to the cart.  You can set it in the Product General Settings.
				(<a target="_blank" rel="noreferrer noopener" href="http://stepbystepsys.com/wp-content/uploads/2017/06/package-product-custom-fields.png">View Image</a>)
			</li>
			<li>
				When you're done creating packages, go back to the package settings page
				and drag packages from the Available Packages column to Your Active Packages
				column to build the Choose Package page.  You can rearrange packages in the
				Your Active Packages column to change the order they're displayed.
				(<a target="_blank" rel="noreferrer noopener" href="http://stepbystepsys.com/wp-content/uploads/2017/06/package-page-builder.png">View Image</a>)
			</li>
		</ol>

		<h3 id="widgets">Widgets</h3>

		<p>To configure your sidebar in the ordering process:</p>
		<ol>
			<li>Go to the widgets section on your Wordpress Dashboard</li>
			<li>Add the WooCommerce Cart Totals widget to your sidebar.</li>
			<li>It is also recommended that you add the WooCommerce Cart Widget under the WooCommerce Cart Totals.</li>
		</ol>
		(<a target="_blank" rel="noreferrer noopener" href="http://stepbystepsys.com/wp-content/uploads/2017/06/widgets.png">View Image</a>)


		<h3 id="stepbystep">Step By Step</h3>

		(<a target="_blank" rel="noreferrer noopener" href="http://stepbystepsys.com/wp-content/uploads/2017/06/sbs-builder.png">View Image</a>)

		<ol>
			<li>The Step-By-Step is located under the Step-By-Step tab in the Step-By-Step Ordering settings</li>
			<li>Create product categories. Each page in the ordering process will display one parent category, which will include their child categories.</li>
			<li>To create child categories, make a new category and select the parent category under the Parent field.</li>
      <li>Assign a parent and child category to each product. This is needed for Step-By-Step to function correctly.</li>
			<li>Go to the Step-By-Step settings page and drag categories into Your Ordering Process.  You can rearrange the categories inside to change the order.</li>
		</ol>

		<h3 id="themes">WordPress Themes</h3>

		<p>This plugin is configured to work with themes already compatible with
		WooCommerce.  While the plugin is fully functional with these themes out of the
		box, it is recommended to take these steps if you are using one of the
		following themes:</p>

		<h4>Twenty Sixteen</h4>

		<p>It is recommended that a full-width template be used on the Choose Package
		page.  Since Twenty Sixteen does not come with a full-width template, download
		a child theme from our website, install it, then activate it.  Go to the package
		selection page in the Admin settings and choose the Full Width template on the
		right-hand sidebar.</p>

    You can download the SBS Twenty Sixteen Theme <a href="http://stepbystepsys.com/download">here</a>.

		<h4>Twenty Seventeen</h4>

		<p>Twenty Seventeen does not come with a sidebar.  Download a child theme from our
		website, install it, then activate it.  Go to the Ordering page in WP Admin and
		choose the Page with Sidebar template on the right-hand sidebar.</p>

    You can download the SBS Twenty Seventeen Theme <a href="http://stepbystepsys.com/download">here</a>.

		<h4>Divi</h4>

		<p>The Choose Package and Checkout page works best on a full-width template.
		The Step-By-Step Ordering page should be used on a 3/4 and 1/4 width template
		for main content and sidebar, respectively.</p>

		<h4>Storefront</h4>

		<p>The Choose Package page is best displayed on the Full width template, which
		you can select when you edit that page on the right-hand sidebar.  The default
		template is recommended for the Ordering page.</p>

		<h3 id="premium">Step-By-Step Premium Upgrade</h3>

		<p>If you like our plugin and would like to upgrade to our expanded version, consider purchasing a license from us. Premium users receive great additional features like unlimited steps, navigation options, required products, either-or products, package store credit, preset themes, and much more! You will also have access to our support team!</p>

		<p>After purchasing the premium version, you will download it from our <a href="http://stepbystepsys.com">website</a>.  You will receive a license key, and this key will be sent to your provided email. Enter this key into the input box in the Premium tab in the Step-By-Step Ordering settings and then click Activate.</p>

    <p>If you have this version of the SBS plugin installed (the SBS Light Version), you will need to uninstall it and install the Premium Version you download from our website.</p>

    <p>The premium version of this plugin can be purchased directly at our <a href="http://stepbystepsys.com">website</a>.  We offer a 14 day trial of the premium version.  Along with unlimited packages, we offer the ability to add a store (merchandise credit) to your package(s).  This is a great selling tool.  For more info, visit our site for a full list of features in the premium version and try the premium version for free!</p>

    <h3 id="uninstall">Uninstalling</h3>
    <p>If you wish to disable this plugin's functionality you can deactivate it in
    the plugin settings.  If you wish to completely remove this plugin, deactivate
    it, then click the Delete button.</p>
    <p>By default all settings, custom fields, and custom metas are not deleted
    on uninstall.  If you wish to remove all Step-By-Step data, add this line to
    your wp-config.php file:</p>
    <pre>define( 'SBS_REMOVE_ALL_DATA', true );</pre>
    <p>This will delete any content created by the Step-By-Step plugin when you uninstall, including
    the Package and Step-By-Step Ordering pages, all settings, and post metas.
    Products and Categories will not be removed.</p>

    <div style="margin-top: 40px">
      <p>For more assistance and premium support with the Step-By-Step Ordering System for WooCommerce,
      please visit our site at <a target="_blank" rel="noopener noreferrer" href="http://stepbystepsys.com">http://stepbystepsys.com</a>.</p>

      <p>Thank you for using our plugin!</p>
    </div>

	</div>
	<?php
	echo ob_get_clean();
}
