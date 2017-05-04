<?php

function sbs_render_package_selection_box( $product_id ) {

  $package = wc_get_product( $product_id );
  $add_to_cart_url = get_permalink( get_the_ID() ) . '?step=1&add-to-cart=' . $product_id;

  $per_row = isset( get_option('sbs_package')['per-row'] ) ? get_option('sbs_package')['per-row'] : 3;
  $add_to_cart_text = isset( get_option('sbs_package')['add-to-cart-text'] ) ? get_option('sbs_package')['add-to-cart-text'] : 'Select Package';

  switch ( $per_row ) {
    case 1:
      $container_width = '70%';
      break;
    case 2:
      $container_width = '45%';
      break;
    case 3:
      $container_width = '30%';
      break;
    case 4:
      $container_width = '22%';
      break;
    case 5:
      $container_width = '19%';
      break;
    default:
      $container_width = '30%';
      break;
  }

  ob_start();
  ?>

  <div class="sbs-package-container woocommerce" style="flex: 0 1 calc(<?php echo $container_width ?> - 4px);">
    <div class="sbs-package-thumbnail">
      <?php echo $package->get_image() ?>
    </div>
    <div class="sbs-package-title">
      <?php echo $package->get_name() ?>
    </div>
    <div class="sbs-package-price">
      <?php echo wc_price( $package->get_price() ) ?>
    </div>
    <div class="sbs-package-content">
      <?php echo $package->get_description() ?>
    </div>
    <div class="sbs-add-package-to-cart">
      <a href="<?php echo esc_url( $add_to_cart_url ) ?>" class="button product_type_simple add_to_cart_button">
        <?php echo esc_html( $add_to_cart_text ) ?>
      </a>
    </div>
  </div>

  <?php

  return ob_get_clean();
}

function sbs_select_package_shortcode() {

  $packages = sbs_get_active_packages();

  ob_start();
  ?>
  <?php
  if ( !$packages )
  {
  ?>

    <div>
      Welcome to the Step-By-Step Ordering Process. Click the 'Next' button below
      to get started.
    </div>

  <?php
  } else {
  ?>

    <div id="sbs-package-list">
    <?php foreach( $packages as $package ) {
            echo sbs_render_package_selection_box( $package->catid );
          } ?>
    </div>

  <?php
  }

}


add_shortcode( 'sbs_select_package', 'sbs_select_package_shortcode' );
