<?php

function sbs_remove_edit_link() {
  return '';
}

function sbs_previous_step_link( $current_step, $step_count ) {

  $base_url = get_permalink( get_the_ID() );

  if ( $current_step > 0 ) {

    $previous_step = $current_step - 1;
    ob_start();
    ?>
      <a href="<?php echo esc_url( $base_url . '?step=' . $previous_step ) ?>">Previous Step</a>
    <?php
    return ob_get_clean();

  } else {

    return null;

  }

}

function sbs_next_step_link( $current_step, $step_count ) {

  $base_url = get_permalink( get_the_ID() );

  if ( $current_step !== $step_count - 1 ) {

    $next_step = $current_step + 1;
    ob_start();
    ?>
      <a href="<?php echo esc_url( $base_url . '?step=' . $next_step ) ?>">Next Step</a>
    <?php
    return ob_get_clean();

  } else {

    return null;

  }

}

function sbs_render_step_by_step_ordering_content( $current_step, $steps ) {

  if ( $current_step === 0 ) {
    echo 'Select Package Placeholder';
    return;
  }

  if ( isset( $steps[$current_step]['category_id'] ) ) {
    $current_category_name = $steps[$current_step]['name'];
    echo do_shortcode( '[product_category category=' . $current_category_name . ']' );
    return;
  }

  if ($current_step === count($steps) - 1) {
    echo do_shortcode( '[woocommerce_checkout]' );
    return;
  }

}

function sbs_woocommerce_step_by_step_ordering_shortcode() {

  $current_step = isset( $_GET['step'] ) && is_int( (int) $_GET['step'] ) ? (int) $_GET['step'] : 0;

  $all_categories = sbs_get_all_wc_categories();

  // Generate the Steps array
  $steps = explode( ',' , get_option( 'step_order' ) );
  $steps = array_map( function($id) {
    return array( 'category_id' => (int) $id, 'name' => get_the_category_by_ID($id) );
  }, $steps );

  array_unshift( $steps, array( 'name' => 'Packages' ) );
  array_push( $steps, array( 'name' => 'Checkout' ) );

  // Default to step 0 if an invalid step was requested
  if ( !array_key_exists( $current_step, $steps ) ) {
    $current_step = 0;
  }
  ChromePhp::log( get_permalink(get_the_ID()) );
  ob_start();
  ?>
  <h1>This is the Step-By-Step Ordering Process</h1>

  <ul>
    <?php foreach( $steps as $key => $step ) { ?>

            <li>
              <?php if ( $key === $current_step ) { ?>
                      <strong><?php echo $step['name'] ?> (Current Step)</strong>
              <?php } else { ?>
                      <?php echo $step['name'] ?>
              <?php } ?>
            </li>

    <?php } ?>
  </ul>

  <div>
    <?php sbs_render_step_by_step_ordering_content( $current_step, $steps ) ?>
  </div>

  <?php echo sbs_previous_step_link( $current_step, count($steps) ) ?>
  <?php echo sbs_next_step_link( $current_step, count($steps) ) ?>

  <?php

  return ob_get_clean();
}

add_shortcode( 'sbs_woocommerce_step_by_step_ordering', 'sbs_woocommerce_step_by_step_ordering_shortcode' );
