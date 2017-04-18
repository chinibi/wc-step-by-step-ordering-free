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

function load_custom_wp_admin_style() {

  // load custom jQuery UI scripts and styles
	wp_enqueue_script( 'johnny-jquery-sortable', plugin_dir_url( __FILE__ ) . 'js/admin/johnny-jquery-sortable.js', array( 'jquery' ) );
  wp_enqueue_script( 'use-jquery-sortable', plugin_dir_url( __FILE__ ) . 'js/admin/use-jquery-sortable.js', array( 'johnny-jquery-sortable' ) );
  wp_enqueue_style( 'sbs_admin_style', plugin_dir_url( __FILE__ ) . 'css/admin/style.css' );

}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );


function sbs_plugin_options_page() {

  $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options';
  ChromePhp::log(get_site_url());
  ?>

  <div>
    <h1>Step-By-Step Ordering Options</h1>
    <?php settings_errors(); ?>
    <h2 class="nav-tab-wrapper">
      <a href="?page=stepbystepsys&tab=general_options" class="nav-tab <?php echo $active_tab === 'general_options' ? 'nav-tab-active' : null ?>">General</a>
      <a href="?page=stepbystepsys&tab=sbs_options" class="nav-tab <?php echo $active_tab === 'sbs_options' ? 'nav-tab-active' : null ?>">Step-By-Step</a>
      <a href="?page=stepbystepsys&tab=package_options" class="nav-tab <?php echo $active_tab === 'package_options' ? 'nav-tab-active' : null ?>">Packages</a>
      <a href="?page=stepbystepsys&tab=display_options" class="nav-tab <?php echo $active_tab === 'display_options' ? 'nav-tab-active' : null ?>">Display</a>
    </h2>

    <form action="<?php echo esc_url('options.php') ?>" method="post">
      <?php sbs_render_active_tab($active_tab) ?>
      <?php submit_button() ?>
    </form>
  </div>

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
  }
}


function sbs_render_general_options() {
  ob_start();
  ?>
    <?php settings_fields('sbs_show_something') ?>
    <?php do_settings_sections('sbs_general') ?>
  <?php

  return ob_get_clean();
}

function sbs_render_sbs_options() {
  ob_start();
  ?>
    <?php settings_fields('sbs_order_settings') ?>
    <?php do_settings_sections('sbs_order_settings') ?>
  <?php

  return ob_get_clean();
}

function sbs_render_package_options() {
	ob_start();
	?>
		<?php settings_fields('sbs_package_settings') ?>
		<?php do_settings_sections('sbs_package_settings') ?>
	<?php

	return ob_get_clean();
}

function sbs_render_display_options() {
  ob_start();
  ?>
    <?php settings_fields('sbs_display') ?>
    <?php do_settings_sections('sbs_display') ?>
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

add_action('admin_init', 'plugin_admin_init');
function plugin_admin_init() {

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

  add_settings_field(
    'sbs_show_something', // String for use in the 'id' attribute of tags.
    'Additional Features', // Title of the field.
    'toggle_display_callback', //  Function that fills the field with the desired inputs as part of the larger form. Passed a single argument, the $args array. Name and id of the input should match the $id given to this function. The function should echo its output.
    'sbs_general', //  The menu page on which to display this field. Should match $menu_slug from add_theme_page() or from do_settings_sections().
    'sbs_general' // The section of the settings page in which to show the box (default or a section you added with add_settings_section(), look at the page in the source to see what the existing ones are.)
  );

  // SBS Step-By-Step Settings Fields
  add_settings_field(
    'step_order',
    'Step Order',
    'sbs_sbs_table_callback',
    'sbs_order_settings',
    'sbs_order_settings'
  );

	// SBS Package Settings
	add_settings_field(
		'sbs_package_category',
		'Package Category',
		'sbs_package_category_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);
	add_settings_field(
		'sbs_package_tiers',
		'Package Tiers',
		'sbs_package_tier_callback',
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
    'Step Number Shapes',
    'sbs_display_navbar_callback',
    'sbs_display',
    'sbs_display'
  );
  add_settings_field(
    'show_calculator',
    'Display Sidebar Calculator Widget',
    'sbs_display_sidebar_calculator_callback',
    'sbs_display',
    'sbs_display'
  );

  register_setting('sbs_show_something', 'sbs_ui_feature');

  register_setting('sbs_order_settings', 'step_order');

	register_setting('sbs_package_settings', 'sbs_package');

  register_setting('sbs_display', 'sbs_display');
  // register_setting('sbs_display', 'color_scheme');
  // register_setting('sbs_display', 'navbar_style');
  // register_setting('sbs_display', 'show_calculator');

}

/* ------------------------------------------------------------------------ *
 * Section Callbacks
 * ------------------------------------------------------------------------ */

/**
 * This function provides a simple description for the General Options page.
 *
 * It is called from the 'sandbox_initialize_theme_options' function by being passed as a parameter
 * in the add_settings_section function.
 */

function sbs_general_description() {

  echo '<p>Main description goes here.</p>';

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
			You can add additional features such as store credit to packages.
		</p>
	<?php
	echo ob_get_clean();
}

function sbs_display_description() {
  echo '<p>Customize the appearance of the ordering process with preset styles and themes.</p>';
}

function toggle_display_callback( $args ) {
  ob_start();
  ?>
    <input type="checkbox" id="enable_lightbox" name="sbs_ui_feature[lightbox]" value="1" <?php echo checked(1, get_option('sbs_ui_feature')['lightbox'], false) ?> />
    <label for="enable_lightbox">Clicking product thumbnails opens product details in a lightbox popup instead of taking the user to a new page.</label><br />
  <?php

  echo ob_get_clean();
}

function sbs_get_step_order() {
	$step_order = get_option('step_order');
	$step_order = json_decode( $step_order );

	// Clean up this array because the nesting library did some weird stuff when serializing
	$step_order = $step_order[0];
	foreach( $step_order as $step ) {
		$step->children = $step->children[0];
	}

	return $step_order;
}

function sbs_sbs_table_callback() {

  // get_the_category_by_ID() only works if this function is called for some reason
  $available_categories = sbs_get_all_wc_categories();

	$step_order = sbs_get_step_order();

	// Categories listed in the ordering process should not be listed in Available Categories
	// to prevent duplication
	$available_categories = array_filter( $available_categories, function( $category ) {

		$step_order = sbs_get_step_order();
		$flat_step_order = array();

		foreach( $step_order as $step ) {
			$flat_step_order[] = $step->catid;
			foreach ($step->children as $child) {
				$flat_step_order[] = $child->catid;
			}
		}

		return !in_array( $category->term_id, $flat_step_order );
	} );

  ob_start();
  ?>
  <?php  ?>
  <div class="sortable-container" id="sbs-order-container">
    <h3>Your Ordering Process</h3>
    <div class="fixed-item noselect">Package Selection</div>
    <ul id="sbs-order" class="sortable">

      <?php
			if ( isset( $step_order ) )
			{
        foreach( $step_order as $category )
				{
				?>
          <li data-catid="<?php echo $category->catid ?>" class="sortable-item">
            <?php echo get_the_category_by_ID( $category->catid ) ?>

						<ul>
							<?php
							foreach( $category->children as $child )
							{
							?>
								<li class="sortable-item" data-catid="<?php echo $child->catid ?>">
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
    <div class="fixed-item noselect">Checkout</div>
  </div>

  <div class="sortable-container" id="sbs-pool-container">
    <h3>Available Categories</h3>
    <ul id="sbs-pool" class="sortable">
      <?php foreach( $available_categories as $category ) { ?>
              <li data-catid="<?php echo $category->term_id ?>" class="sortable-item">
                <?php echo $category->name ?>
								<ul></ul>
              </li>
      <?php } ?>
    </ul>
  </div>

  <div class="clearfix"></div>

  <input type="hidden" id="step_order" name="step_order" value="<?php echo esc_attr( get_option('step_order') ) ?>" />
  <?php

  echo ob_get_clean();

}

function sbs_package_category_callback() {
	$wc_categories = sbs_get_all_wc_categories();
	ob_start();
	?>
		<label for="select-package-category">
			Select the WooCommerce product category your packages are assigned to.<br />
			You must click Save Changes afterwards in order to refresh the package list.
		</label><br />
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
	<?php

	echo ob_get_clean();
}

function sbs_package_tier_callback() {

	if ( !empty( get_option('sbs_package')['category'] ) ) {

		$package_cat_id = get_option('sbs_package')['category'];
		$packages = sbs_get_wc_products_by_category( $package_cat_id );

	}

	ob_start();
	?>
		<div class="inline">
			<h3>Basic Tier</h3>

			<div>
				<label for="sbs-basic-package-name">Select Product: </label>
				<select id="sbs-basic-package-name" name="sbs_package[basic][product]">
					<option value="">Select One</option>
					<?php

					$selected_product = isset( get_option('sbs_package')['basic']['product'] ) ? get_option('sbs_package')['basic']['product'] : false;

					foreach( $packages as $package )
					{
					?>
						<option value="<?php echo $package->ID ?>" <?php selected( $package->ID, $selected_product ) ?>>
							<?php echo $package->post_title ?>
						</option>
					<?php
					}
					?>
				</select>
			</div>

			<div>
				<label for="sbs-basic-package-credit">Store Credit: </label>
				<input id="sbs-basic-package-credit" type="number" name="sbs_package[basic][credit]" value="<?php echo get_option('sbs_package')['basic']['credit'] ?>" />
			</div>

		</div>
		<div class="inline">
			<h3>Premium Tier</h3>

			<div>
				<label for="sbs-premium-package-name">Select Product: </label>
				<select id="sbs-premium-package-name" name="sbs_package[premium][product]">
					<option value="">Select One</option>
					<?php

					$selected_product = isset( get_option('sbs_package')['premium']['product'] ) ? get_option('sbs_package')['premium']['product'] : false;

					foreach( $packages as $package )
					{
					?>
						<option value="<?php echo $package->ID ?>" <?php selected( $package->ID, $selected_product ) ?>>
							<?php echo $package->post_title ?>
						</option>
					<?php
					}
					?>
				</select>
			</div>

			<div>
				<label for="sbs-premium-package-credit">Store Credit: </label>
				<input id="sbs-premium-package-credit" type="number" name="sbs_package[premium][credit]" value="<?php echo get_option('sbs_package')['premium']['credit'] ?>" />
			</div>

		</div>
	<?php

	echo ob_get_clean();

}


function sbs_display_color_scheme_callback() {
  ob_start();
  ?>
    <input type="radio" id="color-scheme-1" name="sbs_display[color-scheme]" value="1" <?php echo checked(1, get_option('sbs_display')['color-scheme'], false) ?> />
    <label for="color-scheme-1">Default</label><br />
    <input type="radio" id="color-scheme-2" name="sbs_display[color-scheme]" value="2" <?php echo checked(2, get_option('sbs_display')['color-scheme'], false) ?> />
    <label for="color-scheme-2">Spring Green</label><br />
    <input type="radio" id="color-scheme-3" name="sbs_display[color-scheme]" value="3" <?php echo checked(3, get_option('sbs_display')['color-scheme'], false) ?> />
    <label for="color-scheme-3">Aqua Green</label><br />
    <input type="radio" id="color-scheme-4" name="sbs_display[color-scheme]" value="4" <?php echo checked(4, get_option('sbs_display')['color-scheme'], false) ?> />
    <label for="color-scheme-4">Autumn 1</label><br />
		<input type="radio" id="color-scheme-5" name="sbs_display[color-scheme]" value="5" <?php echo checked(5, get_option('sbs_display')['color-scheme'], false) ?> />
    <label for="color-scheme-5">Autumn 2</label><br />
		<input type="radio" id="color-scheme-6" name="sbs_display[color-scheme]" value="6" <?php echo checked(6, get_option('sbs_display')['color-scheme'], false) ?> />
		<label for="color-scheme-6">Neon</label><br />
		<input type="radio" id="color-scheme-7" name="sbs_display[color-scheme]" value="7" <?php echo checked(7, get_option('sbs_display')['color-scheme'], false) ?> />
		<label for="color-scheme-7">Neon Gradients</label><br />
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

function sbs_display_navbar_callback() {
  ob_start();
  ?>
    <input type="radio" id="color-scheme-1" name="sbs_display[navbar-style]" value="1" <?php echo checked(1, get_option('sbs_display')['navbar-style'], false) ?> />
    <label for="color-scheme-1">Square</label><br />
    <input type="radio" id="color-scheme-2" name="sbs_display[navbar-style]" value="2" <?php echo checked(2, get_option('sbs_display')['navbar-style'], false) ?> />
    <label for="color-scheme-2">Circle</label><br />
    <input type="radio" id="color-scheme-3" name="sbs_display[navbar-style]" value="3" <?php echo checked(3, get_option('sbs_display')['navbar-style'], false) ?> />
    <label for="color-scheme-3">Triangles</label><br />
  <?php

  echo ob_get_clean();
}

function sbs_get_all_wc_categories() {

  $taxonomy     = 'product_cat';
  $orderby      = 'name';
  $show_count   = 0;      // 1 for yes, 0 for no
  $pad_counts   = 0;      // 1 for yes, 0 for no
  $hierarchical = 1;      // 1 for yes, 0 for no
  $title        = '';
  $empty        = 0;

  $args = array(
         'taxonomy'     => $taxonomy,
         'orderby'      => $orderby,
         'show_count'   => $show_count,
         'pad_counts'   => $pad_counts,
         'hierarchical' => $hierarchical,
         'title_li'     => $title,
         'hide_empty'   => $empty
  );
  $all_categories = get_categories( $args );

  return $all_categories;

}
