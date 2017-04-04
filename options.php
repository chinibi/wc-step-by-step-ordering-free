<?php

// Create a WP-Admin menu item
// This is a WooCommerce submenu item, indicating it's an extension of WooCommerce

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
  // load jQuery UI libraries
  wp_enqueue_script( 'jquery-ui-draggable' );
  wp_enqueue_script( 'jquery-ui-droppable' );
  wp_enqueue_script( 'jquery-ui-sortable' );
  wp_enqueue_style( 'jquery-ui-style', 'https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/smoothness/jquery-ui.css' );

  // load custom jQuery UI scripts and styles
  wp_enqueue_script( 'use-jquery-sortable', plugin_dir_url( __FILE__ ) . 'js/admin/use-jquery-sortable.js', array( 'jquery', 'jquery-ui-sortable', 'jquery-ui-droppable', 'jquery-ui-draggable' ) );
  wp_enqueue_style( 'sbs_admin_style', plugin_dir_url( __FILE__ ) . 'css/admin/style.css' );
}
add_action( 'admin_enqueue_scripts', 'load_custom_wp_admin_style' );


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
    case 'display_options':
      echo sbs_render_display_options();
      break;
  }
}


function sbs_render_general_options() {
  ob_start();
  ?>
    <?php settings_fields('show_something') ?>
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
    'sbs_display',
    'Display Settings',
    'sbs_display_description',
    'sbs_display'
  );

  add_settings_field(
    'show_something', // String for use in the 'id' attribute of tags.
    'Content', // Title of the field.
    'toggle_display_callback', //  Function that fills the field with the desired inputs as part of the larger form. Passed a single argument, the $args array. Name and id of the input should match the $id given to this function. The function should echo its output.
    'sbs_general', //  The menu page on which to display this field. Should match $menu_slug from add_theme_page() or from do_settings_sections().
    'sbs_general', // The section of the settings page in which to show the box (default or a section you added with add_settings_section(), look at the page in the source to see what the existing ones are.)
    array(
      'Activate this setting to display the header.',
      'Activate this setting to display the body.',
      'Activate this setting to display the footer.'
    )
  );

  // SBS Step-By-Step Settings Fields
  add_settings_field(
    'step_order',
    'Step Order',
    'sbs_sbs_table_callback',
    'sbs_order_settings',
    'sbs_order_settings'
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
    'Navbar Appearance',
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

  register_setting('show_something', 'show_header');
  register_setting('show_something', 'show_content');
  register_setting('show_something', 'show_footer');

  register_setting('sbs_order_settings', 'step_order');

  register_setting('sbs_display', 'color_scheme');
  register_setting('sbs_display', 'navbar_style');
  register_setting('sbs_display', 'show_calculator');

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

function sbs_display_description() {
  echo '<p>Customize the appearance of the ordering process with preset styles and themes.</p>';
}

function toggle_display_callback( $args ) {
  ob_start();
  ?>
    <input type="checkbox" id="show_header" name="show_header" value="1" <?php echo checked(1, get_option('show_header'), false) ?> />
    <label for="show_header"><?php echo $args[0] ?></label><br />
    <input type="checkbox" id="show_content" name="show_content" value="1" <?php echo checked(1, get_option('show_content'), false) ?> />
    <label for="show_content"><?php echo $args[1] ?></label><br />
    <input type="checkbox" id="show_footer" name="show_footer" value="1" <?php echo checked(1, get_option('show_footer'), false) ?> />
    <label for="show_footer"><?php echo $args[2] ?></label><br />
  <?php

  echo ob_get_clean();
}

function sbs_sbs_table_callback() {

  // get_the_category_by_ID() only works if this function is called for some reason
  $all_categories = sbs_get_all_wc_categories();

  if ( get_option('step_order') ) {
    $step_order_ids = explode( ',' , get_option('step_order') );
    $step_order = array_map( function($id) {
      return array( 'id' => (int) $id, 'name' => get_the_category_by_ID($id) );
    }, $step_order_ids );

    $all_categories = array_filter( $all_categories, function( $category ) {
      $step_order_ids = explode( ',' , get_option('step_order') );
      return !in_array( $category->term_id, $step_order_ids );
    } );
  }
  // Categories listed in the ordering process should not be listed in Available Categories
  // to prevent duplication

  ob_start();
  ?>
  <?php  ?>
  <div id="sbs-order-container">
    <h3>Your Ordering Process</h3>
    <ul id="sbs-order" class="sortable">
      <li id="sbs_01" data-catid="start" class="sortable-item">Package Selection</li>

      <?php if ( isset( $step_order) ) {
              foreach( $step_order as $category ) { ?>
                <li data-catid="<?php echo $category['id'] ?>" class="sortable-item">
                  <?php echo $category['name'] ?>
                </li>
              <?php
              }
            } ?>

      <li id="sbs_05" data-catid="end" class="sortable-item">Checkout</li>
    </ul>
  </div>

  <div id="sbs-pool-container">
    <h3>Available Categories</h3>
    <ul id="sbs-pool" class="sortable">
      <?php foreach( $all_categories as $category ) { ?>
              <li data-catid="<?php echo $category->term_id ?>" class="sortable-item">
                <?php echo $category->name ?>
              </li>
      <?php } ?>
    </ul>
  </div>

  <div class="clearfix"></div>

  <input type="text" id="step_order" name="step_order" value="<?php echo get_option('step_order') ?>" />
  <?php

  echo ob_get_clean();

}


function sbs_display_color_scheme_callback() {
  ob_start();
  ?>
    <input type="radio" id="color-scheme-1" name="color_scheme" value="1" <?php echo checked(1, get_option('color_scheme'), false) ?> />
    <label for="color-scheme-1">Red</label><br />
    <input type="radio" id="color-scheme-2" name="color_scheme" value="2" <?php echo checked(2, get_option('color_scheme'), false) ?> />
    <label for="color-scheme-2">Blue</label><br />
    <input type="radio" id="color-scheme-3" name="color_scheme" value="3" <?php echo checked(3, get_option('color_scheme'), false) ?> />
    <label for="color-scheme-3">Green</label><br />
    <input type="radio" id="color-scheme-4" name="color_scheme" value="4" <?php echo checked(4, get_option('color_scheme'), false) ?> />
    <label for="color-scheme-4">Gray</label><br />
  <?php

  echo ob_get_clean();
}

function sbs_display_sidebar_calculator_callback() {
  ob_start();
  ?>
    <input type="checkbox" id="show_calculator" name="show_calculator" value="1" <?php echo checked(1, get_option('show_calculator'), false) ?> />
  <?php

  echo ob_get_clean();
}

function sbs_display_navbar_callback() {
  ob_start();
  ?>
    <input type="radio" id="color-scheme-1" name="navbar_style" value="1" <?php echo checked(1, get_option('navbar_style'), false) ?> />
    <label for="color-scheme-1">Circles</label><br />
    <input type="radio" id="color-scheme-2" name="navbar_style" value="2" <?php echo checked(2, get_option('navbar_style'), false) ?> />
    <label for="color-scheme-2">Squares</label><br />
    <input type="radio" id="color-scheme-3" name="navbar_style" value="3" <?php echo checked(3, get_option('navbar_style'), false) ?> />
    <label for="color-scheme-3">Triangles</label><br />
  <?php

  echo ob_get_clean();
}

function print_wc_categories() {
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
  echo var_dump( $all_categories );
  foreach ($all_categories as $cat) {
    if($cat->category_parent == 0) {
        $category_id = $cat->term_id;
        echo '<br /><a href="'. get_term_link($cat->slug, 'product_cat') .'">'. $cat->name .'</a>';

        $args2 = array(
                'taxonomy'     => $taxonomy,
                'child_of'     => 0,
                'parent'       => $category_id,
                'orderby'      => $orderby,
                'show_count'   => $show_count,
                'pad_counts'   => $pad_counts,
                'hierarchical' => $hierarchical,
                'title_li'     => $title,
                'hide_empty'   => $empty
        );
        $sub_cats = get_categories( $args2 );
        if($sub_cats) {
            foreach($sub_cats as $sub_category) {
                echo  $sub_category->name ;
            }
        }
    }
  }
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
