<?php

function sbs_remove_edit_link() {
  return '';
}

function sbs_previous_step_url( $current_step, $step_count ) {

  $base_url = get_permalink( get_the_ID() );

  if ( $current_step > 0 ) {

    $previous_step = $current_step - 1;
    return $base_url . '?step=' . $previous_step;

  } else {

    return null;

  }

}

function sbs_previous_step_link( $current_step, $step_count ) {

  $base_url = get_permalink( get_the_ID() );

  if ( $current_step > 0 ) {

    $previous_step = $current_step - 1;
    ob_start();
    ?>
      <a href="<?php echo esc_url( $base_url . '?step=' . $previous_step ) ?>">&#171; GO BACK</a>
    <?php
    return ob_get_clean();

  } else {

    return null;

  }

}

function sbs_next_step_url( $current_step, $step_count ) {

  $base_url = get_permalink( get_the_ID() );

  if ( $current_step !== $step_count - 1 ) {

    $next_step = $current_step + 1;
    return $base_url . '?step=' . $next_step;

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
      <a href="<?php echo esc_url( $base_url . '?step=' . $next_step ) ?>">NEXT &#187;</a>
    <?php
    return ob_get_clean();

  } else {

    return null;

  }

}

function sbs_render_step_by_step_ordering_content( $current_step, $steps ) {

  if ( $current_step === 0 ) {
    echo do_shortcode( '[sbs_select_package]' );
    return;
  }

  if ( isset( $steps[$current_step]->catid ) ) {

    $current_category_name = get_the_category_by_ID( $steps[$current_step]->catid );

    echo '<h1 class="sbs-step-title">Step ' . $current_step . ': ' . $current_category_name . '</h1>';

    if ( !empty( $steps[$current_step]->children ) ) {

      foreach( $steps[$current_step]->children as $subcategory ) {

        $sub_term = get_term_by('id', $subcategory->catid, 'product_cat', 'ARRAY_A');

        echo '<h2 class="sbs-subcat-name">' . $sub_term['name'] .'</h2>';
        echo '<p class="sbs-subcat-description">' . $sub_term['description'] . '</p>';
        echo do_shortcode( '[product_category category=' . $sub_term['slug'] . ']' );

      }

    }

    return;

  }

  if ($current_step === count($steps) - 1) {

    echo '<h1 class="sbs-step-title">Step ' . $current_step . ': Checkout' . '</h1>';
    echo do_shortcode( '[woocommerce_checkout]' );
    return;

  }

}


function sbs_woocommerce_step_by_step_ordering_shortcode() {

  $current_step = isset( $_GET['step'] ) && is_numeric( $_GET['step'] ) ? (int) $_GET['step'] : 0;

  $all_categories = sbs_get_all_wc_categories();

  // Generate the Steps array
  $steps = sbs_get_step_order();
  foreach( $steps as $step ) {
    $step->name = get_the_category_by_ID( $step->catid );
  }
  $steps_package = new stdClass();
  $steps_package->name = 'Packages';
  $steps_checkout = new stdClass();
  $steps_checkout->name = 'Checkout';
  array_unshift( $steps, $steps_package );
  array_push( $steps, $steps_checkout );

  // Default to step 0 if an invalid step was requested
  if ( !array_key_exists( $current_step, $steps ) ) {
    $current_step = 0;
  }

  ob_start();
  ?>

  <?php
  if ( $current_step > 0 )
  {
  ?>
    <div id="sbs-navbar">
      <?php foreach( $steps as $key => $step ) {
              if ($key === 0) continue;
      ?>

              <span class="step-span-container">
                <div class="step-div-container">
                  <div class="step-index">
                    <span class="<?php echo $key === $current_step ? 'active' : null ?>">
                      <?php echo $key ?>
                    </span>
                  </div>
                  <div class="step-title <?php echo $key === $current_step ? 'active' : null ?>">
                    <?php

                      if ($key < $current_step)
                      {
                      ?>
                        <a href="<?php echo esc_url( sbs_previous_step_url($current_step, count($steps)) ) ?>"><?php echo $step->name ?></a>
                      <?php
                      }
                      else if ($key === $current_step)
                      {
                      ?>
                        <?php echo $step->name ?>
                      <?php
                      }
                      else if ($key > $current_step)
                      {
                      ?>
                        <a href="<?php echo esc_url( sbs_next_step_url($current_step, count($steps)) ) ?>"><?php echo $step->name ?></a>
                      <?php
                      }

                    ?>
                  </div>
                </div>
              </span>

      <?php } ?>
    </div>
  <?php
  }
  ?>

  <div>
    <?php sbs_render_step_by_step_ordering_content( $current_step, $steps ) ?>
  </div>

  <?php
  if ( $current_step > 0 )
  {
  ?>

  <div id="sbs-store-back-forward-buttons-container">
    <div class="sbs-store-back-forward-buttons">
      <?php echo sbs_previous_step_link( $current_step, count($steps) ) ?>
    </div>
    <div class="sbs-store-back-forward-buttons">
      <?php echo sbs_next_step_link( $current_step, count($steps) ) ?>
    </div>
  </div>

  <?php
  }
  ?>

  <?php

  return ob_get_clean();
}

add_shortcode( 'sbs_woocommerce_step_by_step_ordering', 'sbs_woocommerce_step_by_step_ordering_shortcode' );
