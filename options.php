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
  wp_enqueue_style( 'sbs_admin_style', plugin_dir_url( __FILE__ ) . 'css/admin/style.css' );

}
add_action( 'admin_enqueue_scripts', 'sbs_load_custom_wp_admin_style' );


function sbs_plugin_options_page() {

  $active_tab = isset( $_GET['tab'] ) ? $_GET['tab'] : 'general_options';
  ?>

  <div>
    <h1>Step-By-Step Ordering Options</h1>
    <?php settings_errors(); ?>
    <h2 class="nav-tab-wrapper">
      <a href="?page=stepbystepsys&tab=general_options" class="nav-tab <?php echo $active_tab === 'general_options' ? 'nav-tab-active' : null ?>">General</a>
      <a href="?page=stepbystepsys&tab=sbs_options" class="nav-tab <?php echo $active_tab === 'sbs_options' ? 'nav-tab-active' : null ?>">Step-By-Step</a>
      <a href="?page=stepbystepsys&tab=package_options" class="nav-tab <?php echo $active_tab === 'package_options' ? 'nav-tab-active' : null ?>">Packages</a>
      <a href="?page=stepbystepsys&tab=onf_options" class="nav-tab <?php echo $active_tab === 'onf_options' ? 'nav-tab-active' : null ?>">Options and Fees</a>
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
		case 'onf_options':
			echo sbs_render_onf_options();
			break;
    case 'display_options':
      echo sbs_render_display_options();
      break;
  }
}


function sbs_render_general_options() {
  ob_start();
  ?>
    <?php settings_fields('sbs_general') ?>
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

function sbs_render_onf_options() {
	ob_start();
	?>
		<?php settings_fields('sbs_onf_settings') ?>
		<?php do_settings_sections('sbs_onf_settings') ?>
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

	add_settings_field(
		'sbs_page_name', // String for use in the 'id' attribute of tags.
		'Step-By-Step Page', // Title of the field.
		'sbs_page_name_callback', //  Function that fills the field with the desired inputs as part of the larger form. Passed a single argument, the $args array. Name and id of the input should match the $id given to this function. The function should echo its output.
		'sbs_general', //  The menu page on which to display this field. Should match $menu_slug from add_theme_page() or from do_settings_sections().
		'sbs_general' // The section of the settings page in which to show the box (default or a section you added with add_settings_section(), look at the page in the source to see what the existing ones are.)
	);
  // add_settings_field(
  //   'sbs_show_something',
  //   'Additional Features',
  //   'toggle_display_callback',
  //   'sbs_general',
  //   'sbs_general'
  // );
	add_settings_field(
		'sbs_featured_position',
		'Featured Items Position',
		'sbs_featured_items_pos_callback',
		'sbs_general',
		'sbs_general'
	);
	add_settings_field(
		'sbs_required_featured_label',
		'Featured and Required Products',
		'sbs_req_feat_label_callback',
		'sbs_general',
		'sbs_general'
	);

  // SBS Step-By-Step Settings Fields
  add_settings_field(
    'step_order',
    'Step Order',
    'sbs_sbs_table_callback',
    'sbs_order_settings',
    'sbs_order_settings'
  );
	add_settings_field(
		'sbs_navbar_navigation',
		'Navbar Navigation',
		'sbs_navbar_navigation_callback',
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
		'sbs_package_merch_cred',
		'Merchandise Credit',
		'sbs_package_merch_cred_callback',
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
	add_settings_field(
		'sbs_packages_style',
		'Package Selection Appearance',
		'sbs_package_select_style_callback',
		'sbs_package_settings',
		'sbs_package_settings'
	);

	// SBS Options and Fees Settings Fields
	add_settings_field(
		'sbs_onf_enable',
		'', // Enable/Disable Options and Fees page
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
		'SBS Calculator Widget',
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
  // add_settings_field(
  //   'show_calculator',
  //   'Display Sidebar Calculator Widget',
  //   'sbs_display_sidebar_calculator_callback',
  //   'sbs_display',
  //   'sbs_display'
  // );

	register_setting('sbs_general', 'sbs_general');
  register_setting('sbs_general', 'sbs_ui_feature');
  register_setting('sbs_order_settings', 'step_order');
	register_setting('sbs_order_settings', 'sbs_navbar');
	register_setting('sbs_package_settings', 'sbs_package');
	register_setting('sbs_onf_settings', 'sbs_onf');
  register_setting('sbs_display', 'sbs_display');
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
			You can add additional features such as store credit to packages.
		</p>
	<?php
	echo ob_get_clean();
}

function sbs_onf_description() {
	ob_start();
	?>
		<p>
			The Options and Fees page is for miscellaneous items, services, and fees.
			They will be each displayed compactly in a table.
		</p>
	<?php
	echo ob_get_clean();
}

function sbs_display_description() {
  echo '<p>Customize the appearance of the ordering process with preset styles and themes.</p>';
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
			<p>The page where the Step-By-Step Ordering is located must be selected in order for navigation to work properly.</p>
			<p><strong>You may need to set this option again if you change the name of the page.</strong></p>
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

function toggle_display_callback( $args ) {
	$option = isset( get_option('sbs_ui_feature')['lightbox'] ) ? get_option('sbs_ui_feature')['lightbox'] : 1;

  ob_start();
  ?>
    <input type="checkbox" id="enable_lightbox" name="sbs_ui_feature[lightbox]" value="1" <?php echo checked(1, get_option('sbs_ui_feature')['lightbox'], false) ?> />
    <label for="enable_lightbox">Clicking product thumbnails opens product details in a lightbox popup instead of taking the user to a new page.</label><br />
  <?php

  echo ob_get_clean();
}

function sbs_featured_items_pos_callback() {

	$option = isset( get_option('sbs_general')['featured-items-position'] ) ? get_option('sbs_general')['featured-items-position'] : 2;

	ob_start();
	?>
		<fieldset>
			<label>
				<input type="radio" name="sbs_general[featured-items-position]" value="1" <?php echo checked(1, $option) ?>>
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

function sbs_get_step_order() {
	$step_order = get_option('step_order');

	if ( !isset( $step_order ) ) {
		return;
	}

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

  ob_start();
  ?>

	<div id="main-sortable-container">

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
			if ( !isset( get_option('sbs_onf')['disabled'] ) || get_option('sbs_onf')['disabled'] != 1 )
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

	</div>

  <input type="hidden" id="step_order" name="step_order" value="<?php echo esc_attr( get_option('step_order') ) ?>" />
  <?php

  echo ob_get_clean();

}


function sbs_navbar_navigation_callback() {

	$option = isset( get_option('sbs_navbar')['throttle-nav'] ) ? get_option('sbs_navbar')['throttle-nav'] : 1;

	ob_start();
	?>
	<fieldset>
		<label>
			<input type="radio" id="step_navbar_navigation_1" name="sbs_navbar[throttle-nav]" value="1" <?php checked( 1, $option ) ?> />
			Only allow navigation one step at a time in any direction
		</label><br />

		<label>
			<input type="radio" id="step_navbar_navigation_2" name="sbs_navbar[throttle-nav]" value="2" <?php checked( 2, $option ) ?> />
			Only allow forward navigation one step a time, but let users backtrack to
			any step.
		</label><br />

		<label>
			<input type="radio" id="step_navbar_navigation_3" name="sbs_navbar[throttle-nav]" value="3" <?php checked( 3, $option ) ?> />
			Users may freely navigate, skipping any step they'd like.
		</label><br />
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
		<fieldset>
			<span>
				<label>
					<strong>"Required Items" Section Title:</strong>
				</label><br />
				<label>
					Before Category Name:
					<input type="text" name="sbs_general[req-label-before]" value="<?php echo $req_label_before ?>" />
				</label><br />
				<label>
					After Category Name:
					<input type="text" name="sbs_general[req-label-after]" value="<?php echo $req_label_after ?>" />
				</label><br />
			</span>
			<span>
				<label>
					<strong>"Optional Items" Section Title:</strong>
				</label><br />
				<label>
					Before Category Name:
					<input type="text" name="sbs_general[opt-label-before]" value="<?php echo $opt_label_before ?>" />
				</label><br />
				<label>
					After Category Name:
					<input type="text" name="sbs_general[opt-label-after]" value="<?php echo $opt_label_after ?>" />
				</label><br />
			</span>
			<span>
				<label>
					<strong>"Featured Items" Section Title:</strong>
				</label><br />
				<label>
					<input type="text" name="sbs_general[featured-label]" value="<?php echo $featured_label ?>" />
				</label><br />
			</span>
		</fieldset>
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

		<?php submit_button() ?>
	<?php

	echo ob_get_clean();
}


function sbs_package_merch_cred_callback() {

	$wc_attributes = wc_get_attribute_taxonomies();
	$selected_attr = isset( get_option('sbs_package')['merch-cred-attr'] ) ? get_option('sbs_package')['merch-cred-attr'] : null;

	ob_start();
	?>
	<p>
		You can assign store credit to packages.  To begin setting it up, create a
		WooCommerce product attribute, then go to the package's product page and
		add that attribute to the product, with the value of store credit you want
		to assign.
	</p>
	<p>
		Then select the attribute you created in the dropdown below and save. You
		may need to refresh the page to see it.
	</p>
	<label for="sbs-merch-cred-attribute">Store Credit Product Attribute: </label>
	<select id="sbs-merch-cred-attribute" name="sbs_package[merch-cred-attr]">
		<option value="">Select One</option>
		<?php
		foreach ( $wc_attributes as $attr )
		{
		?>

			<option value="<?php echo $attr->attribute_name ?>" <?php selected( $attr->attribute_name, $selected_attr ) ?>>
				<?php echo $attr->attribute_label ?>
			</option>

		<?php
		}
		?>
	</select>

	<?php submit_button() ?>

	<?php
	echo ob_get_clean();
}


function sbs_get_active_packages() {

	$package_order = get_option('sbs_package')['active'];

	if ( !isset( $package_order ) ) return null;

	$package_order = json_decode( $package_order );
	$package_order = $package_order[0];

	return $package_order;

}


function sbs_package_tier_callback() {

	$package_cat_id = get_option('sbs_package')['category'];
	$all_packages = sbs_get_wc_products_by_category( $package_cat_id );
	$active_packages = sbs_get_active_packages();

	$available_packages = array_filter( $all_packages, function( $package ) {

		$active_packages = sbs_get_active_packages();

		if ( isset( $active_packages ) ) {
			$active_packages = array_map( function( $package ) {
				return $package->catid;
			}, $active_packages);
		} else {
			$active_packages = array();
		}

		return !in_array( $package->ID, $active_packages );

	} );

	ob_start();
	?>

	<p>
		Drag packages from the Available Packages box to the Active Packages here to build your Package Selection page.  You can rearrange the packages to change
		the order in which they are displayed.
	</p>

	<div class="sortable-container" id="sbs-order-container">
		<h3>Your Active Packages</h3>
		<ul id="sbs-order" class="sortable">
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
			foreach( $available_packages as $package )
			{
			?>
				<li data-catid="<?php echo $package->ID ?>" class="sortable-item">
					<?php echo $package->post_title ?>
				</li>
			<?php
			}
			?>
		</ul>
	</div>

	<input type="hidden" id="step_order" name="sbs_package[active]" value="<?php echo esc_attr( get_option('sbs_package')['active'] ) ?>" />

	<?php

	echo ob_get_clean();
}


function sbs_package_select_style_callback() {

	$per_row = isset( get_option('sbs_package')['per-row'] ) ? get_option('sbs_package')['per-row'] : 3;

	$per_row_options = array( 1, 2, 3, 4, 5);

	ob_start();
	?>
	<fieldset>
		<label>
			Number of packages to display per row:
			<select id="sbs-package-per-row" name="sbs_package[per-row]">
		</label>
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
		</select><br />

		<label>
			"Add to Cart" Text:
			<input type="text" id="sbs-package-add-cart-label" name="sbs_package[add-to-cart-text]" value="<?php echo get_option('sbs_package')['add-to-cart-text'] ?>" placeholder='Default: "Select Package"' />
		</label>
	</fieldset>

	<?php

	echo ob_get_clean();

}


function sbs_onf_enable_callback() {

	$option = isset( get_option('sbs_onf')['disabled'] ) ? get_option('sbs_onf')['disabled'] : false;

	ob_start();
	?>
		<input type="checkbox" id="onf_disabled" name="sbs_onf[disabled]" value="1" <?php checked(1, $option) ?> />
		<label for="onf_disabled">Disable Options and Fees page</label>
	<?php

	echo ob_get_clean();

}

function sbs_onf_category_callback() {

	$wc_categories = sbs_get_all_wc_categories();

	$option = isset( get_option('sbs_onf')['category'] ) ? get_option('sbs_onf')['category'] : null;

	ob_start();
	?>
		<label for="select-package-category">
			Select the WooCommerce product category your Options and Fees items are located.<br />
			Then click Save Changes to refresh the page.
		</label><br />
		<select id="select-package-category" name="sbs_onf[category]">
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

		<?php submit_button() ?>
	<?php

	echo ob_get_clean();
}

function sbs_get_onf_order() {

	$onf_order = get_option('sbs_onf')['order'];

	if ( empty($onf_order) )
		return null;

	$onf_order = json_decode( $onf_order );

	// Clean up this array because the nesting library did some weird stuff when serializing
	$onf_order = $onf_order[0];
	foreach( $onf_order as $onf ) {
		$onf->children = $onf->children[0];
	}

	return $onf_order;

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

	ob_start();
	?>

	<div class="sortable-container" id="sbs-order-container">
		<h3>Options and Fees Page Outline</h3>
		<ul id="sbs-order" class="sortable">

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

	<input type="hidden" id="step_order" name="sbs_onf[order]" value="<?php echo esc_attr( get_option('sbs_onf')['order'] ) ?>" />

	<?php

	echo ob_get_clean();

}


function sbs_display_color_scheme_callback() {

	$colors = array(
		'Default',
		'Spring Green',
		'Aqua Green',
		'Autumn 1',
		'Autumn 2',
		'Neon',
		'Neon Gradients',
		'Noir 1',
		'Noir 2',
		'Royal 1',
		'Royal 2'
	);

  ob_start();
  ?>

		<?php
		foreach( $colors as $key => $color )
		{
		?>

			<input
				type="radio"
				id="color-scheme-<?php echo $key + 1 ?>"
				name="sbs_display[color-scheme]"
				value="<?php echo $key + 1 ?>"
				<?php echo checked( $key + 1, get_option('sbs_display')['color-scheme'], false ) ?>
			/>
	    <label for="color-scheme-<?php echo $key + 1 ?>"><?php echo $color ?></label><br />

		<?php
		}
		?>

  <?php

  echo ob_get_clean();
}

function sbs_display_calc_callback() {
	$calc_borders = isset( get_option('sbs_display')['calc-borders'] ) ? get_option('sbs_display')['calc-borders'] : false;
	$calc_font = isset( get_option('sbs_display')['calc-font'] ) ? get_option('sbs_display')['calc-font'] : 1;

	$fonts = array(
		'Default',
		'Helvetica',
		'Arial',
		'Verdana'
	);

	ob_start();
	?>
		<div>
			<p><strong>Font Family</strong></p>
			<?php
			foreach( $fonts as $key => $font )
			{
			?>
				<input
					type="radio"
					id="calc_font_<?php echo $key + 1 ?>"
					name="sbs_display[calc-font]"
					value="<?php echo $key + 1 ?>"
					<?php checked( $key + 1, $calc_font ) ?>
					/>
				<label for="calc_font_<?php echo $key + 1 ?>">
					<?php echo $font ?>
				</label><br />
			<?php
			}
			?>
		</div>
		<div>
			<p><strong>Other Styles</strong></p>
			<input type="checkbox" id="show_calc_borders" name="sbs_display[calc-borders]" value="1" <?php checked(1, $calc_borders) ?> />
			<label for="show_calc_borders">
				Show a vertical border separating the category column and the price column
			</label>
		</div>
	<?php
}


function sbs_display_fonts_callback() {

	$category_font = isset( get_option('sbs_display')['category-font'] ) ? get_option('sbs_display')['category-font'] : 1;
	$category_desc_font = isset( get_option('sbs_display')['category-desc-font'] ) ? get_option('sbs_display')['category-desc-font'] : 1;
	$nav_button_font = isset( get_option('sbs_display')['nav-button-font'] ) ? get_option('sbs_display')['nav-button-font'] : 1;
	$navbar_font = isset( get_option('sbs_display')['navbar-font'] ) ? get_option('sbs_display')['navbar-font'] : 1;

	$fonts = array(
		'Default',
		'Helvetica',
		'Arial',
		'Verdana',
		'Georgia',
		'Lucida',
		'Palatino'
	);

	$sections = array(
		array( 'title' => 'Subcategory Name', 'slug' => 'category-font', 'option' => $category_font ),
		array( 'title' => 'Subcategory Description', 'slug' => 'category-desc-font', 'option' => $category_desc_font ),
		array( 'title' => 'Nav Buttons', 'slug' => 'nav-button-font', 'option' => $nav_button_font ),
		array( 'title' => 'Navbar', 'slug' => 'navbar-font', 'option' => $navbar_font ),
	);

	ob_start();
	?>

	<?php
	foreach ( $sections as $section )
	{
	?>
		<div class="horizontal-stack">
		<div><strong><?php echo $section['title'] ?></strong></div>
		<?php
		foreach ( $fonts as $key => $font )
		{
			$index = $key + 1;
		?>
			<input
				type="radio"
				id="<?php echo $section['slug'] . $key ?>"
				name="sbs_display[<?php echo $section['slug'] ?>]"
				value="<?php echo $index ?>"
				<?php checked( $index, $section['option'] ) ?>
				/>
			<label for="<?php echo $section['slug'] . $key ?>">
				<?php echo $font ?>
			</label><br />

		<?php
		}
		?>
		</div>
		<?php
	}

}



function sbs_display_misc_callback() {
	$hover_effect = isset( get_option('sbs_display')['hover-effect'] ) ? get_option('sbs_display')['hover-effect'] : false;

	ob_start();
	?>
		<input type="checkbox" id="show_hover_effect" name="sbs_display[hover-effect]" value="1" <?php checked(1, $hover_effect) ?> />
		<label for="show_hover_effect">
			SBS links turn different colors when moused over (varies by theme)
		</label><br />
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

	$styles = array(
		'Default (Square)',
		'Circle',
		'Downward Triangle',
		'Upward Triangle',
		'Heart',
		'12-Pointed Star',
		'Kite',
		'Badge Ribbon'
	);

  ob_start();
	?>
		<?php
		foreach ( $styles as $key => $style )
		{
			$index = $key + 1;
		?>
			<input type="radio" id="color-scheme-<?php echo $index ?>" name="sbs_display[navbar-style]" value="<?php echo $index ?>" <?php echo checked( $index, $number_style, false) ?> />
			<label for="color-scheme-<?php echo $index ?>"><?php echo $style ?></label><br />
  	<?php
		}

  echo ob_get_clean();
}

function sbs_display_navbar_title_shape_callback() {
	$title_style = isset( get_option('sbs_display')['nav-title-style'] ) ? get_option('sbs_display')['nav-title-style'] : 1;

	$styles = array(
		'Default',
		'Capsule',
		'Arrows',
		'TV Screen',
		'Parallelogram'
	);

	ob_start();
	?>
		<?php
		foreach ($styles as $key => $style)
		{
			$index = $key + 1;
		?>

			<input type="radio" id="nav-title-shape-<?php echo $index ?>" name="sbs_display[nav-title-style]" value="<?php echo $index ?>" <?php checked( $index, $title_style ) ?>>
			<label for="nav-title-shape-<?php echo $index ?>"><?php echo $style ?></label><br />

		<?php
		}

	echo ob_get_clean();
}
