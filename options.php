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

  // load custom jQuery UI scripts and styles
	wp_enqueue_script( 'johnny-jquery-sortable', plugin_dir_url( __FILE__ ) . 'js/admin/johnny-jquery-sortable.js', array( 'jquery' ) );
  wp_enqueue_script( 'use-jquery-sortable', plugin_dir_url( __FILE__ ) . 'js/admin/use-jquery-sortable.js', array( 'johnny-jquery-sortable' ) );
  wp_enqueue_style( 'sbs_admin_style', plugin_dir_url( __FILE__ ) . 'css/admin/style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/admin/style.css' ) );

}
add_action( 'admin_enqueue_scripts', 'sbs_load_custom_wp_admin_style' );

function sbs_admin_dashboard_notice() {

	$license = sbs_check_license_cache();
	$key = get_option('sbs_premium_key');
	global $pagenow;

	if ( $pagenow === 'index.php' && !$license && empty( $key ) ) {
		echo '<div class="notice notice-info is-dismissible">';
		echo '<p class="sbs-buy-notice">Thank you for trying out the <strong>Step-By-Step Plugin</strong>.  Please support us by <strong><a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">purchasing a license</a></strong>, which will unlock additional features like unlimited steps, navigation options, required products, either-or products, package store credit, preset themes, and much more!  You will also have access to our <strong>support team</strong>!</p>';
		echo '</div>';
	}

}
add_action( 'admin_notices', 'sbs_admin_dashboard_notice' );

function sbs_admin_settings_notices() {

	$license = sbs_check_license_cache();
	$key = get_option('sbs_premium_key');
	$current_admin_page = isset( $_GET['page'] ) ? $_GET['page'] : false;

	if ( $current_admin_page !== 'stepbystepsys' ) {
		return;
	}

	if ( !$license && !empty( $key ) ) {
		echo '<div class="notice notice-info is-dismissible">';
		echo '<p>Your license key has expired or is no longer valid.  Any premium settings will have no effect until your license is renewed.</p>';
		echo '</div>';
	}
	elseif( !$license && empty( $key ) ) {
		echo '<div class="notice notice-info is-dismissible">';
		echo '<p class="sbs-buy-notice">This is the free version of the <strong>Step-By-Step Plugin</strong>.  Please support us by <strong><a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">purchasing a license</a></strong>, which will unlock additional features like unlimited steps, navigation options, required products, either-or products, package store credit, preset themes, and much more!  You will also have access to our <strong>support team</strong>!</p>';
		echo '</div>';
	}

}
add_action( 'admin_notices', 'sbs_admin_settings_notices' );


function sbs_plugin_options_page() {

  $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options';
  ?>

  <div class="wrap">
    <h2>Step-By-Step Ordering Options</h2>
    <?php settings_errors(); ?>
    <h2 class="nav-tab-wrapper">
      <a href="?page=stepbystepsys&tab=general_options" class="nav-tab <?php echo $active_tab === 'general_options' ? 'nav-tab-active' : null ?>">General</a>
			<a href="?page=stepbystepsys&tab=package_options" class="nav-tab <?php echo $active_tab === 'package_options' ? 'nav-tab-active' : null ?>">Packages</a>
      <a href="?page=stepbystepsys&tab=sbs_options" class="nav-tab <?php echo $active_tab === 'sbs_options' ? 'nav-tab-active' : null ?>">Step-By-Step</a>
      <a href="?page=stepbystepsys&tab=onf_options" class="nav-tab <?php echo $active_tab === 'onf_options' ? 'nav-tab-active' : null ?>">Options and Fees</a>
      <a href="?page=stepbystepsys&tab=display_options" class="nav-tab <?php echo $active_tab === 'display_options' ? 'nav-tab-active' : null ?>">Display</a>
			<a href="?page=stepbystepsys&tab=sbs_premium" class="nav-tab <?php echo $active_tab === 'sbs_premium' ? 'nav-tab-active' : null ?>">Premium</a>
			<a href="?page=stepbystepsys&tab=help" class="nav-tab <?php echo $active_tab === 'help' ? 'nav-tab-active' : null ?>">Help</a>
    </h2>

		<?php if ( $active_tab !== 'sbs_premium' ): ?>
	    <form action="<?php echo esc_url('options.php') ?>" method="post">
	      <?php sbs_render_active_tab($active_tab) ?>
	    </form>
		<?php else: ?>
			<?php sbs_render_active_tab($active_tab) ?>
		<?php endif ?>
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
		case 'onf_options':
			echo sbs_render_onf_options();
			break;
    case 'display_options':
      echo sbs_render_display_options();
      break;
		case 'sbs_premium':
			echo sbs_render_premium_key_page();
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

function sbs_render_onf_options() {
	$license = sbs_check_license_cache();
	ob_start();
	?>
		<?php settings_fields('sbs_onf_settings') ?>
		<?php do_settings_sections('sbs_onf_settings') ?>
		<?php if ( $license ) { submit_button(); } ?>
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
	<p class="alignleft description">If you like the <strong>Step-By-Step Ordering System for WooCommerce</strong> please leave us a <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">5-star</a> rating.  Thanks for your support!</p>
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
		'sbs_onf_settings',
		'Options and Fees Settings',
		'sbs_onf_description',
		'sbs_onf_settings'
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
		'sbs_featured_position',
		'Featured Items Position (Premium)' . sbs_admin_help_tooltip( 'right', 'Display featured items at the beginning or end of pages.' ),
		'sbs_featured_items_pos_callback',
		'sbs_general',
		'sbs_general'
	);
	add_settings_field(
		'sbs_required_featured_label',
		'Featured and Required Section Labels (Premium)',
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
		'Navbar Navigation' . sbs_admin_help_tooltip('right', 'The navbar contains navigable links in each step.  You can disallow skipping of steps here.'),
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
		'Calculator Widget Label (Premium)',
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
		'"Store Credit" Calculator Widget Label (Premium)',
		'sbs_package_merch_cred_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);

	// SBS Options and Fees Settings Fields
	add_settings_field(
		'sbs_onf_enable',
		'Enable / Disable', // Enable/Disable Options and Fees page
		'sbs_onf_enable_callback',
		'sbs_onf_settings',
		'sbs_onf_settings'
	);
	add_settings_field(
		'sbs_onf_category',
		'Category',
		'sbs_onf_category_callback',
		'sbs_onf_settings',
		'sbs_onf_settings'
	);
	add_settings_field(
		'sbs_onf_order',
		'Options and Fees Builder',
		'sbs_onf_order_callback',
		'sbs_onf_settings',
		'sbs_onf_settings'
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
		'SBS Calculator Widget' . sbs_admin_help_tooltip( 'right', 'A widget displaying price totals of items in the cart, listed by step.' ),
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
		'License Key' . sbs_admin_help_tooltip( 'right', 'Please enter the license key for this product to activate premium features.<br>An email was sent, with your license key, to your valid email after purchasing the premium version of this plugin.' ),
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
  register_setting('sbs_general', 'sbs_ui_feature');
  register_setting('sbs_order_settings', 'step_order');
	register_setting('sbs_order_settings', 'sbs_navbar', 'sbs_navbar_settings_sanitize');
	register_setting('sbs_package_settings', 'sbs_package', 'sbs_package_settings_sanitize');
	register_setting('sbs_onf_settings', 'sbs_onf');
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

}

function sbs_sbs_description() {
  ob_start();
  ?>

    <p>Create your ordering process by dragging and dropping your steps in the boxes below.</p>
    <p>You can select from your Product Categories.  Drag any desired categories from the
      Available Categories column, and move them to the Your Ordering Process column.</p>
    <p>To remove a step from your ordering process just drag it back under the Available Categories column.</p>

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
	<?php
	echo ob_get_clean();
}

function sbs_onf_description() {

	$license = sbs_check_license_cache();
	ob_start();
	?>
		<p>
			The Options and Fees page is for miscellaneous items, services, and fees.
			They will be each displayed compactly in a table.
			<?php if ( ! $license ): ?>
			<br><strong style="color: red; font-size: 1.2em;">A premium license is required to access this section.  You can purchase one <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">here.</a></strong>
			<?php endif ?>
		</p>
	<?php
	echo ob_get_clean();
}

function sbs_display_description() {
  echo '<p>Customize the appearance of the ordering process with preset styles and themes.</p>';
}

function sbs_premium_description() {
	$license = sbs_check_license_cache();

	if ( !$license ) {
		echo '<p style="font-size: 1.1em;"><strong>Unlock the full version of this plugin by purchasing a license on <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">our website</a>. Enter the key sent to your valid email address.</strong></p>';
	}

}

/**
 * Preserve any disabled fields that previously had values until user was
 * de-licensed
 */
function sbs_general_settings_sanitize( $input ) {

	$license = sbs_check_license_cache();

	if ( !$license ):

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

	endif;

	return $input;

}

function sbs_package_settings_sanitize( $input ) {

	$license = sbs_check_license_cache();

	if ( !$license ):
		$title_label = isset( get_option('sbs_package')['label'] ) ? get_option('sbs_package')['label'] : 'Step-By-Step Ordering';
		$calc_label = isset( get_option('sbs_package')['merch-cred-label'] ) ? get_option('sbs_package')['merch-cred-label'] : 'Merchandise Credit';
		$add_to_cart_text = isset( get_option('sbs_package')['add-to-cart-text'] ) ? get_option('sbs_package')['add-to-cart-text'] : 'Select Package';

		$input['label'] = $title_label;
		$input['merch-cred-label'] = $calc_label;
		$input['add-to-cart-text'] = $add_to_cart_text;
	endif;

	return $input;

}

function sbs_navbar_settings_sanitize( $input ) {

	$license = sbs_check_license_cache();

	if ( !$license ):
		$throttle_nav = isset( get_option('sbs_navbar')['throttle-nav'] ) ? get_option('sbs_navbar')['throttle-nav'] : 2;

		$input['throttle-nav'] = $throttle_nav;
	endif;

	return $input;
}

function sbs_display_settings_sanitize( $input ) {

	$license = sbs_check_license_cache();

	if ( !$license ):
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
	endif;

	return $input;

}

/**
 *  Options Form Output Callbacks
 *
 */

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
				'The page where the Step-By-Step Ordering is located must be selected in order for navigation to work properly.<br>
				<strong>You may need to set this option again if you change the name of the page.</strong>'
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

function sbs_featured_items_pos_callback() {

	$option = isset( get_option('sbs_general')['featured-items-position'] ) ? get_option('sbs_general')['featured-items-position'] : 2;

	$license = sbs_check_license_cache();

	ob_start();
	?>
		<fieldset>
			<label class="<?php echo !$license ? 'grayed-out-text' : null ?>">
				<input type="radio" name="sbs_general[featured-items-position]" value="1" <?php echo checked(1, $option) ?> <?php disabled( false, $license ) ?>>
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

function sbs_req_feat_label_callback() {

	$featured_label = isset( get_option('sbs_general')['featured-label'] ) ? get_option('sbs_general')['featured-label'] : 'Featured Items';
	$req_label_before = isset( get_option('sbs_general')['req-label-before'] ) ? get_option('sbs_general')['req-label-before'] : 'Select';
	$req_label_after = isset( get_option('sbs_general')['req-label-after'] ) ? get_option('sbs_general')['req-label-after'] : '(Required)';
	$opt_label_before = isset( get_option('sbs_general')['opt-label-before'] ) ? get_option('sbs_general')['opt-label-before'] : '';
	$opt_label_after = isset( get_option('sbs_general')['opt-label-after'] ) ? get_option('sbs_general')['opt-label-after'] : '(Addons)';

	$license = sbs_check_license_cache();

	ob_start();
	?>
		<fieldset class="<?php echo !$license ? 'grayed-out-text' : null ?>">
			<span>
				<label>
					<strong>"Required Items" Section Title:</strong>
					<?php
					echo sbs_admin_help_tooltip(
						'top',
						'Products with the "Required" attribute are displayed in their own sections.'
					);
					?>
				</label><br />
				<label>
					Before Category Name:
					<input type="text" name="sbs_general[req-label-before]" value="<?php echo $req_label_before ?>" <?php disabled( false, $license ) ?>/>
				</label><br />
				<label>
					After Category Name:
					<input type="text" name="sbs_general[req-label-after]" value="<?php echo $req_label_after ?>" <?php disabled( false, $license ) ?> />
				</label><br />
			</span>
			<span>
				<label>
					<strong>"Optional Items" Section Title:</strong>
					<?php
					echo sbs_admin_help_tooltip(
						'top',
						'Products that do not have the "Required" attribute are displayed separately from those that do.'
					);
					?>
				</label><br />
				<label>
					Before Category Name:
					<input type="text" name="sbs_general[opt-label-before]" value="<?php echo $opt_label_before ?>" <?php disabled( false, $license ) ?> />
				</label><br />
				<label>
					After Category Name:
					<input type="text" name="sbs_general[opt-label-after]" value="<?php echo $opt_label_after ?>" <?php disabled( false, $license ) ?> />
				</label><br />
			</span>
			<span>
				<label>
					<strong>"Featured Items" Section Title:</strong>
					<?php
					echo sbs_admin_help_tooltip(
						'top',
						'Featured Products are products with the "Featured" tag selected from the Products list.'
					);
					?>
				</label><br />
				<label>
					<input type="text" name="sbs_general[featured-label]" value="<?php echo $featured_label ?>" <?php disabled( false, $license ) ?> />
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
		$option_cat = isset( get_option('sbs_onf')['category'] ) ? get_option('sbs_onf')['category'] : null;

		$flat_step_order = array();

		foreach( $step_order as $step ) {
			$flat_step_order[] = $step->catid;
			foreach ($step->children as $child) {
				$flat_step_order[] = $child->catid;
			}
		}

		return !in_array( $category->term_id, $flat_step_order ) && $category->term_id != $package_cat && $category->term_id != $option_cat;

	} );

	$license = sbs_check_license_cache();

  ob_start();
  ?>
	<?php if ( !$license ) { ?>
	<p><strong style="color: red; font-size: 1.2em;">You may have up to two parent categories, or steps, active at a time in the free version of this plugin.<br>You can add as many categories as you would like after purchasing a license for the premium version <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">here.</a></strong></p>
	<?php } ?>
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

						<ul>
							<?php
							foreach( $category->children as $child )
							{
							?>
								<li class="sortable-item" data-catid="<?php echo $child->catid ?>" parent-id="<?php echo get_category($child->catid)->category_parent ?>">
									<?php echo get_the_category_by_ID( $child->catid ) ?>
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

		<?php
		if ( sbs_is_onf_section_active() )
		{
		?>
			<div class="fixed-item noselect">Options and Fees</div>
		<?php
		}
		?>

    <div class="fixed-item noselect">Checkout</div>
  </div>

  <div class="sortable-container" id="sbs-pool-container">
    <h3>Available Categories</h3>
    <ul id="sbs-pool" class="sortable">
      <?php foreach( $available_categories as $category ): ?>

				<?php if ( $category->category_parent === 0 ): ?>

          <li data-catid="<?php echo $category->term_id ?>" class="sortable-item" parent-id="<?php echo $category->category_parent ?>">
            <?php echo $category->name ?>

						<ul>
							<?php $children = get_term_children( $category->term_id, 'product_cat' ); ?>
							<?php if ( !empty( $children ) ): ?>
								<?php foreach( $children as $child_id ): ?>

									<li data-catid="<?php echo $child_id ?>" class="sortable-item" parent-id="<?php echo $category->term_id ?>">
										<?php echo get_the_category_by_ID( $child_id ) ?>
									</li>

								<?php endforeach; ?>
							<?php endif; ?>
						</ul>

          </li>

				<?php endif; ?>

      <?php endforeach; ?>
    </ul>
  </div>

  <input type="hidden" id="step_order" name="step_order" value="<?php echo esc_attr( get_option('step_order') ) ?>" />
  <?php

  echo ob_get_clean();

}


function sbs_navbar_navigation_callback() {

	$option = isset( get_option('sbs_navbar')['throttle-nav'] ) ? get_option('sbs_navbar')['throttle-nav'] : 2;

	$license = sbs_check_license_cache();

	ob_start();
	?>
	<fieldset>
		<label>
			<input type="radio" id="step_navbar_navigation_2" name="sbs_navbar[throttle-nav]" value="2" <?php checked( 2, $option ) ?> />
			Only allow forward navigation one step a time, but let users backtrack to
			any step.
		</label><br />
		<label class="<?php echo !$license ? 'grayed-out-text' : null ?>">
			<input type="radio" id="step_navbar_navigation_1" name="sbs_navbar[throttle-nav]" value="1" <?php checked( 1, $option ) ?> <?php disabled( false, $license ) ?>/>
			Only allow navigation one step at a time in any direction <?php echo !$license ? ' (Premium)' : null ?>
		</label><br />

		<label class="<?php echo !$license ? 'grayed-out-text' : null ?>">
			<input type="radio" id="step_navbar_navigation_3" name="sbs_navbar[throttle-nav]" value="3" <?php checked( 3, $option ) ?> <?php disabled( false, $license ) ?>/>
			Users may freely navigate, skipping any step they'd like. <?php echo !$license ? ' (Premium)' : null ?>
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
				'If deactivated, the package page will be replaced with a notice that links to Step 1. You can link directly to Step 1 copying the link from the address bar or echoing sbs_get_begin_url( ) in your PHP code.'
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
	$license = sbs_check_license_cache();

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
			<input style="width: 240px;" type="text" id="sbs_package[label]" name="sbs_package[label]" value="<?php echo $option ?>" <?php disabled( false, $license ) ?>/>
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
				'The page where the Select Packages page is located must be selected in order for navigation to work properly.<br>
				<strong>You may need to set this option again if you change the name of the page.</strong>'
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
	$license = sbs_check_license_cache();
	$calc_label = isset( get_option('sbs_package')['merch-cred-label'] ) ? get_option('sbs_package')['merch-cred-label'] : 'Merchandise Credit';

	ob_start();
	?>
	<fieldset>
		<label>
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'Any store credit the selected package has will be displayed on the calculator.'
			);
			?>
			<input style="width: 240px;" type="text" name="sbs_package[merch-cred-label]" value="<?php echo $calc_label ?>" <?php disabled( false, $license ) ?>/>
		</label>
	</fieldset>

	<?php
	echo ob_get_clean();
}


function sbs_package_tier_callback() {

	$package_cat_id = get_option('sbs_package')['category'];
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

	$license = sbs_check_license_cache();

	ob_start();
	?>

	<?php if ( !isset( $package_cat_id ) ): ?>

		<p>Select a package category above to begin.</p>

	<?php else: ?>
		<p>
			Drag packages from the Available Packages box to the Active Packages here to build your Package Selection page.  You can rearrange the packages to change
			the order in which they are displayed.

			<?php if ( !$license ) { ?>
				<br>
				<strong style="color: red; font-size: 1.2em;">You may have up to one package in the free version of this plugin.<br>You can add as many packages as you would like after purchasing a license for the premium version of this plugin <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">here.</a></strong>
			<?php } ?>
		</p>

		<div class="sortable-container" id="sbs-order-container">
			<h3>Your Active Packages</h3>
			<ul id="sbs-order" class="sortable package-sortable">
				<?php
				if ( isset( $active_packages ) )
				{
					foreach( $active_packages as $package )
					{
					?>
					<li data-catid="<?php echo $package->catid ?>" class="sortable-item">
						<?php echo get_the_title( $package->catid ) ?>
					</li>
					<?php
					}
				}
				?>
			</ul>
		</div>

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
						</li>
					<?php
					}
				}
				?>
			</ul>
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
				'Action taken when a package is added to the cart.'
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

	$per_row = isset( get_option('sbs_package')['per-row'] ) ? get_option('sbs_package')['per-row'] : 3;

	$per_row_options = array( 1, 2, 3, 4, 5);

	$add_to_cart_text = isset( get_option('sbs_package')['add-to-cart-text'] ) ? get_option('sbs_package')['add-to-cart-text'] : 'Select Package';

	$license = sbs_check_license_cache();

	ob_start();
	?>
	<fieldset>
		<label class="<?php // echo !$license ? 'grayed-out-text' : null ?>">
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'Applies to desktop displays.  Mobile displays may collapse to one package per row.'
			);
			?>
			Number of packages to display per row:
			<select id="sbs-package-per-row" name="sbs_package[per-row]">
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

		<label class="<?php echo !$license ? 'grayed-out-text' : null ?>">
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'Customize the text on the Add To Cart button on packages.'
			);
			?>
			"Add to Cart" Text (Premium):
			<input type="text" id="sbs-package-add-cart-label" name="sbs_package[add-to-cart-text]" value="<?php echo $add_to_cart_text ?>" placeholder='Default: "Select Package"' <?php disabled( false, $license ) ?>/>
		</label>

		<p>
			Package Image Custom Size:
		</p>
		<label>
			<?php
			echo sbs_admin_help_tooltip(
				'top',
				'Maximum image size is limited to the width of the package selection box, while maintaining aspect ratio.'
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


function sbs_onf_enable_callback() {

	$option_defined = isset( get_option('sbs_onf')['enabled'] );
	$category_defined = isset( get_option('sbs_onf')['category'] ) && !empty( get_option('sbs_onf')['category'] );
	$option = $category_defined ? get_option('sbs_onf')['enabled'] : '0';

	$license = sbs_check_license_cache();

	ob_start();
	?>
		<fieldset>
			<label>
				<?php
				echo sbs_admin_help_tooltip(
					'top',
					'If deactivated, removes the page from the ordering process.'
				);
				?>
				<select id="sbs_onf[enabled]" name="sbs_onf[enabled]" <?php disabled( false, $category_defined && $license ) ?>>
					<option value="1" <?php selected(1, $option) ?>>Activated</option>
					<option value="0" <?php selected(0, $option) ?>>Deactivated</option>
				</select>
				<?php if ( !$category_defined ): ?>
					<p class="description">Select a product category below to enable the Options and Fees section in your ordering process.</p>
				<?php endif ?>
			</label>
		</fieldset>
	<?php

	echo ob_get_clean();

}

function sbs_onf_category_callback() {

	$wc_categories = sbs_get_all_wc_categories();

	$license = sbs_check_license_cache();

	$option = isset( get_option('sbs_onf')['category'] ) ? get_option('sbs_onf')['category'] : null;

	ob_start();
	?>
		<fieldset class="<?php echo !$license ? 'grayed-out-text' : null ?>">
			<label>
				<?php
				echo sbs_admin_help_tooltip(
					'top',
					'Select the WooCommerce product category your Options and Fees items are located.<br />
					Then click Save Changes to refresh the page.'
				);
				?>
				<select id="select-package-category" name="sbs_onf[category]" <?php disabled( false, $license ) ?>>
					<option value="">Select One</option>
					<?php
					foreach( $wc_categories as $category )
					{
					?>
						<option value="<?php echo $category->term_id ?>" <?php selected( $category->term_id, $option ) ?>>
							<?php echo $category->name ?>
						</option>
					<?php
					}
					?>
				</select>
			</label>
		</fieldset>

		<?php if ( $license ) { submit_button(); } ?>
	<?php

	echo ob_get_clean();
}

function sbs_onf_order_callback() {

	$onf_category = get_option('sbs_onf')['category'];
	$onf_order = sbs_get_onf_order();

	if ( !isset( $onf_category ) ) {
		echo '<p>Select a product category above to begin.</p>';
		return;
	}

	$onf_subcats = sbs_get_subcategories_from_parent( $onf_category );

	$available_categories = array_filter( $onf_subcats, function( $category ) {

		$onf_order = sbs_get_onf_order();

		if ( isset( $onf_order ) ) {
			$onf_order = array_map( function( $package ) {
				return $package->catid;
			}, $onf_order);
		} else {
			$onf_order = array();
		}

		return !in_array( $category->term_id, $onf_order );
	} );

	$license = sbs_check_license_cache();

	ob_start();
	?>
	<div class="<?php echo !$license ? 'grayed-out-text' : null ?>">
		<p>Create your Options and Fees page by dragging and dropping your items in the boxes below.</p>
		<p>You can select from your subcategories of the parent category chosen to serve as Options.  Drag any desired categories from the
		Available Categories column, and move them to the Your Ordering Process column.  You can change the order they are displayed by rearranging the order of items in the column.</p>
		<p>To remove a category from the page just drag it back under the Available Categories column.</p>
	</div>

	<div class="sortable-container <?php echo !$license ? 'grayed-out-text' : null ?>" id="sbs-order-container">
		<h3 class="<?php echo !$license ? 'grayed-out-text' : null ?>">Options and Fees Page Outline</h3>
		<ul id="sbs-order" class="sortable onf-sortable">

			<?php
			if ( $onf_order )
			{
				foreach( $onf_order as $category )
				{
				?>
					<li data-catid="<?php echo $category->catid ?>" class="sortable-item">
						<?php echo get_the_category_by_ID( $category->catid ) ?>

						<ul>
							<?php
							if ( !empty( $onf_order->children ) )
							{
								foreach( $category->children as $child )
								{
								?>
									<li class="sortable-item" data-catid="<?php echo $child->catid ?>">
										<?php echo get_the_category_by_ID( $child->catid ) ?>
									</li>
								<?php
								}
							}
							?>
						</ul>

					</li>
				<?php
				}
			}
			?>

		</ul>
	</div>

	<div class="sortable-container <?php echo !$license ? 'grayed-out-text' : null ?>" id="sbs-pool-container">
		<h3 class="<?php echo !$license ? 'grayed-out-text' : null ?>">Available Categories</h3>
		<ul id="sbs-pool" class="sortable onf-sortable">
			<?php foreach( $available_categories as $category ) { ?>
							<li data-catid="<?php echo $category->term_id ?>" class="sortable-item">
								<?php echo $category->name ?>
								<ul></ul>
							</li>
			<?php } ?>
		</ul>
	</div>

	<input type="hidden" id="step_order" name="sbs_onf[order]" value="<?php echo esc_attr( get_option('sbs_onf')['order'] ) ?>" />

	<?php

	echo ob_get_clean();

}


function sbs_display_color_scheme_callback() {

	$option = isset( get_option('sbs_display')['color-scheme'] ) ? get_option('sbs_display')['color-scheme'] : 1;
	$image_dir = plugin_dir_url( __FILE__ ) . 'assets/admin/color-schemes/';

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

	$license = sbs_check_license_cache();

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
	    <option value="<?php echo $key + 1 ?>" <?php echo selected( $key + 1, $option, false ) ?> <?php disabled( true, $color['premium'] && !$license ) ?>>
				<?php echo esc_html( $color['name'] ) ?>
				<?php echo $color['premium'] ? ' (Premium)' : null ?>
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

	$license = sbs_check_license_cache();

	ob_start();
	?>
		<div>
			<fieldset class="<?php echo !$license ? 'grayed-out-text' : null ?>">
				<label>
					<p><strong>Font Family (Premium)</strong></p>
					<select id="sbs_display[calc-font]" name="sbs_display[calc-font]" <?php disabled( false, $license ) ?>>
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
		array( 'title' => 'Subcategory Name (Premium)', 'slug' => 'category-font', 'option' => $category_font, 'tooltip' => 'Section names in each page.' ),
		array( 'title' => 'Subcategory Description (Premium)', 'slug' => 'category-desc-font', 'option' => $category_desc_font, 'tooltip' => 'The description under each section name.' ),
		array( 'title' => 'Nav Buttons (Premium)', 'slug' => 'nav-button-font', 'option' => $nav_button_font, 'tooltip' => 'The Back/Foward buttons on each page.' ),
		array( 'title' => 'Navbar (Premium)', 'slug' => 'navbar-font', 'option' => $navbar_font, 'tooltip' => "The bar at the top of each page displaying the customer's progress during ordering." ),
	);

	$license = sbs_check_license_cache();

	ob_start();
	?>

	<?php
	foreach ( $sections as $section )
	{
	?>
	<fieldset class="<?php echo !$license ? 'grayed-out-text' : null ?>">
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
			<select id="sbs_display[<?php echo $section['slug'] ?>]" name="sbs_display[<?php echo $section['slug'] ?>]" <?php disabled( false, $license ) ?>>
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
	$license = sbs_check_license_cache();
	$hover_effect = isset( get_option('sbs_display')['hover-effect'] ) ? get_option('sbs_display')['hover-effect'] : false;
	$drop_shadow = isset( get_option('sbs_display')['drop-shadow'] ) ? get_option('sbs_display')['drop-shadow'] : false;

	ob_start();
	?>
	<fieldset>
		<label>
			<input type="checkbox" id="show_hover_effect" name="sbs_display[hover-effect]" value="1" <?php checked(1, $hover_effect) ?> />
			SBS links turn different colors when moused over (varies by theme)
		</label><br />
		<label class="<?php echo !$license ? 'grayed-out-text' : null ?>">
			<input type="checkbox" id="sbs_display[drop-shadow]" name="sbs_display[drop-shadow]" value="1" <?php checked( 1, $drop_shadow ); disabled( false, $license ); ?> />
			Add drop shadows to SBS pages (Premium)
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
	$image_dir = plugin_dir_url( __FILE__ ) . 'assets/admin/nav-num-shapes/';

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

	$license = sbs_check_license_cache();

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
			<option value="<?php echo $index ?>" <?php echo selected( $index, $number_style, false) ?> <?php disabled( true, $style['premium'] && !$license ) ?>>
				<?php echo $style['name'] ?>
				<?php echo $style['premium'] && !$license ? ' (Premium)' : null ?>
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
	$image_dir = plugin_dir_url( __FILE__ ) . 'assets/admin/nav-step-shapes/';

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

	$license = sbs_check_license_cache();

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
			<option value="<?php echo $index ?>" <?php selected( $index, $title_style ) ?> <?php disabled( true, $style['premium'] && !$license ) ?>>
				<?php echo $style['name'] ?>
				<?php echo $style['premium'] && !$license ? ' (Premium)' : null ?>
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


function sbs_premium_key_callback() {

	$secret_key = 'sb4Oj2baaBvIp7gP67lNq370';
	$server_url = 'http://stepbystepsys.com';
	$item_reference = 'SBS Premium License';

	/*** License activate button was clicked ***/
	if (isset($_REQUEST['activate_license'])) {

			if ( !isset( $_POST['sbs_premium_key_form_nonce'] ) || !wp_verify_nonce( $_POST['sbs_premium_key_form_nonce'], 'sbs_premium_key' ) ) {
				exit;
			}

			$license_key = $_REQUEST['sbs_premium_key'];

			// API query parameters
			$api_params = array(
					'slm_action' => 'slm_activate',
					'secret_key' => $secret_key,
					'license_key' => $license_key,
					'registered_domain' => $_SERVER['SERVER_NAME'],
					'item_reference' => urlencode($item_reference)
			);

			// Send query to the license manager server
			$query = esc_url_raw(add_query_arg($api_params, $server_url));
			$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

			// Check for error in the response
			if (is_wp_error($response)){
					echo "Unexpected Error! The query returned with an error.";
			}

			//var_dump($response);//uncomment it if you want to look at the full response

			// License data.
			$license_data = json_decode(wp_remote_retrieve_body($response));

			// TODO - Do something with it.
			//var_dump($license_data);//uncomment it to look at the data

			if($license_data->result == 'success'){//Success was returned for the license activation

					//Uncomment the followng line to see the message that returned from the license server
					echo '<p style="color: red;"><strong>' . $license_data->message . '</strong></p>';

					//Save the license key in the options table
					update_option('sbs_premium_key', $license_key);
					set_site_transient( 'sbs_premium_key_valid', 'true', 2 * DAY_IN_SECONDS  );
			}
			else{
					//Show error to the user. Probably entered incorrect license key.

					//Uncomment the followng line to see the message that returned from the license server
					echo '<p style="color: red;"><strong>' . $license_data->message . '.  Please try again.  If you do not have a license you can purchase one <a rel="noopener noreferrer" target="_blank" href="http://stepbystepsys.com">here</a></strong>.</p>';
			}

	}
	/*** End of license activation ***/

	/*** License activate button was clicked ***/
	if (isset($_REQUEST['deactivate_license'])) {
			$license_key = get_option('sbs_premium_key');

			if ( !isset( $_POST['sbs_premium_key_form_nonce'] ) || !wp_verify_nonce( $_POST['sbs_premium_key_form_nonce'], 'sbs_premium_key' ) ) {
				exit;
			}

			// API query parameters
			$api_params = array(
					'slm_action' => 'slm_deactivate',
					'secret_key' => $secret_key,
					'license_key' => $license_key,
					'registered_domain' => $_SERVER['SERVER_NAME'],
					'item_reference' => urlencode($item_reference),
			);

			// Send query to the license manager server
			$query = esc_url_raw(add_query_arg($api_params, $server_url));
			$response = wp_remote_get($query, array('timeout' => 20, 'sslverify' => false));

			// Check for error in the response
			if (is_wp_error($response)){
					echo "Unexpected Error! The query returned with an error.";
			}

			//var_dump($response);//uncomment it if you want to look at the full response

			// License data.
			$license_data = json_decode(wp_remote_retrieve_body($response));

			// TODO - Do something with it.
			//var_dump($license_data);//uncomment it to look at the data

			if($license_data->result == 'success'){//Success was returned for the license activation
					echo '<p style="color: red;"><strong>' . $license_data->message . '</strong></p>';

					//Remove the licensse key from the options table. It will need to be activated again.
					update_option('sbs_premium_key', '');
					set_site_transient( 'sbs_premium_key_valid', 'false' );
			}
			else{
					//Show error to the user. Probably entered incorrect license key.

					//Uncomment the followng line to see the message that returned from the license server
					echo '<p style="color: red;"><strong>' . $license_data->message . '</strong></p>';
			}

	}
	/*** End of license deactivation ***/

	$saved_license_key = get_option('sbs_premium_key');

	if ( empty( $saved_license_key ) ) {

	}
	else {

		// $check_api_params = array(
		// 	'slm_action' => 'slm_check',
		// 	'secret_key' => $secret_key,
		// 	'license_key' => $saved_license_key
		// );
		//
		// $check_response = wp_remote_get( add_query_arg( $check_api_params, $server_url ) );

		$check_response = sbs_query_key_verification_server();

		if ( ! $check_response['response_success'] ) {
			echo "The server was unable to respond to the query.";
		}
		else {

			// $check_response = json_decode( wp_remote_retrieve_body( $check_response ) );

			if ( $check_response['verify_success'] && $check_response['response_success'] ) {
				echo '<p class="description">Product key verified and premium features unlocked.</p>';
			}
			else {
				echo '<p class="description">Your product key is invalid or has expired.</p>';
			}

		}

	}

	?>
	<form action="" method="post">
		<?php wp_nonce_field( 'sbs_premium_key', 'sbs_premium_key_form_nonce' ) ?>
		<fieldset>
			<label>
				<input class="regular-text" type="text" id="sbs_premium_key" name="sbs_premium_key" value="<?php echo $saved_license_key ?>" <?php disabled( true, !empty( $saved_license_key ) ) ?>>
				<p class="submit">
					<?php if ( empty( $saved_license_key ) ): ?>
						<input type="submit" name="activate_license" value="Activate" class="button-primary" />
					<?php endif ?>
					<?php if ( !empty( $saved_license_key ) ): ?>
						<input type="submit" name="deactivate_license" value="Deactivate" class="button" onclick="return confirm('Are you sure you want to deactivate all premium features?');"/>
					<?php endif ?>
				</p>
			</label>
		</fieldset>
	</form>

	<p class="description"><?php // echo sbs_check_license_cache() ? 'Cache valid' : 'Cache invalid' ?></p>

	<?php if ( isset( $check_response ) && isset( $check_response['data'] ) ) { ?>
	<table>
		<thead>
			<tr>
				<th colspan="2">License Information</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Status</td>
				<td><strong><?php echo ucwords( esc_html( $check_response['data']->status ) ) ?></strong></td>
			</tr>
			<tr>
				<td>Registered Email</td>
				<td><?php echo esc_html( $check_response['data']->email ) ?></td>
			</tr>
			<tr>
				<td>Maximum Allowed Domains</td>
				<td><?php echo esc_html( $check_response['data']->max_allowed_domains ) ?></td>
			</tr>
			<tr>
				<td>Registered Domains</td>
				<td>
					<?php foreach( $check_response['data']->registered_domains as $domain ): ?>
						<?php echo esc_html( $domain->registered_domain ) . '<br>' ?>
					<?php endforeach ?>
				</td>
			</tr>
			<tr>
				<td>Registered</td>
				<td><?php echo esc_html( $check_response['data']->date_created ) ?></td>
			</tr>
			<tr>
				<td>Renewed</td>
				<td><?php echo esc_html( $check_response['data']->date_renewed ) ?></td>
			</tr>
			<tr>
				<td>Expiration</td>
				<td><?php echo esc_html( $check_response['data']->date_expiry ) ?></td>
			</tr>
		</tbody>
	</table>
	<?php } ?>
	<?php
}

function sbs_render_admin_help_page() {
	ob_start();
	?>
	<div class="wrap">
		<h3>Help</h3>
		<p>This is the help section for the Step-By-Step Plugin.</p>
		<div class="sbs-tooltip">
			Help
			<span class="sbs-tooltiptext sbs-tooltip-right">Tooltip Text</span>
		</div>
	</div>
	<?php
	echo ob_get_clean();
}
