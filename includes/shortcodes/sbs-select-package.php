<?php

function sbs_render_package_selection_box( $product_id ) {

  $package = wc_get_product( $product_id );
  $add_to_cart_url = get_permalink( get_the_ID() ) . '?step=1&add-to-cart=' . $product_id;

  ob_start();
  ?>

  <div class="sbs-package-container">
    <div class="sbs-package-thumbnail">
      <?php echo $package->get_image() ?>
    </div>
    <div class="sbs-package-title">
      <h2><?php echo $package->get_name() ?></h2>
    </div>
    <div class="sbs-package-content">
      <?php echo $package->get_description() ?>
    </div>
    <div class="sbs-package-price">
      <?php echo wc_price( $package->get_price() ) ?>
    </div>
    <div class="sbs-add-package-to-cart">
      <a href="<?php echo esc_url( $add_to_cart_url ) ?>">
        <?php echo $package->add_to_cart_text() ?>
      </a>
    </div>
  </div>

  <?php

  return ob_get_clean();
}

function sbs_select_package_shortcode() {

  $package_cat_id = get_option('sbs_package')['category'];
  $packages = $package_cat_id ? sbs_get_wc_products_by_category( $package_cat_id ) : null;

  ob_start();
  ?>
  <?php
  if ( empty( $packages ) || is_null( $packages ) )
  {
  ?>

    <div>
      Welcome to the Step-By-Step Ordering Process. Click the 'Next' button below
      to get started.
    </div>

  <?php
  } else {
    $basic_package_id = get_option('sbs_package')['basic']['product'];
    $premium_package_id = get_option('sbs_package')['premium']['product'];
  ?>

    <?php echo sbs_render_package_selection_box( $basic_package_id ) ?>
    <?php echo sbs_render_package_selection_box( $premium_package_id ) ?>

  <?php
  }
}


add_shortcode( 'sbs_select_package', 'sbs_select_package_shortcode' );
