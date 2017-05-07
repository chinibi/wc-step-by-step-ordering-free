<?php

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function sbs_remove_edit_link() {
  return '';
}

function sbs_previous_step_url( $current_step, $step_count ) {

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;
  $base_url = get_permalink( $sbs_page );

  if ( $current_step > 0 ) {

    $previous_step = $current_step - 1;
    return $base_url . '?step=' . $previous_step;

  } else {

    return null;

  }

}

function sbs_previous_step_button( $current_step, $step_count ) {

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;
  $base_url = get_permalink( $sbs_page );

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

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;
  $base_url = get_permalink( $sbs_page );

  if ( $current_step !== $step_count - 1 ) {

    $next_step = $current_step + 1;
    return $base_url . '?step=' . $next_step;

  } else {

    return null;

  }

}

function sbs_next_step_button( $current_step, $step_count ) {

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;
  $base_url = get_permalink( $sbs_page );

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

function sbs_hide_sidebar_default_theme() {

	$current_theme = wp_get_theme()->get('Name');

	if ($current_theme == 'Twenty Sixteen' || $current_theme == 'Twenty Fifteen') {
		add_action( 'widgets_init', unregister_sidebar( 'sidebar-1' ), 11 );
		ob_start();
		?>
		<style>
			@media screen and (min-width: 56.875em) {.content-area {width: 100%;}}
		</style>
		<?php
		echo ob_get_clean();
	}

}

function sbs_render_required_products( $category_id ) {

	// $title = isset( get_option('sbs_step_section_label')['req-label-' . $current_step] ) ? get_option('sbs_step_section_label')['req-label-' . $current_step] . ' (Required)' : 'Featured Items';

	$sub_term = get_term_by('id', $category_id, 'product_cat', 'ARRAY_A');

	$req_args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => 12,
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => $category_id
			)
		)
	);

	$query = new WP_Query( $req_args );

	$required_products = sbs_req_get_required_products( $category_id );

	if ( $query->have_posts() && !empty( $required_products ) ):

		$required_label_before = isset( get_option('sbs_general')['req-label-before'] ) ? get_option('sbs_general')['req-label-before'] : 'Select';
		$required_label_after = isset( get_option('sbs_general')['req-label-after'] ) ? get_option('sbs_general')['req-label-after'] : '(Required)';

		echo '<h3 class="sbs-subcat-name">' . esc_html( $required_label_before ) . ' ' . $sub_term['name'] . ' ' . esc_html( $required_label_after ) . '</h3>';
		echo '<p class="sbs-subcat-description">' . $sub_term['description'] . '</p>';
		echo '<div class="woocommerce columns-4">';
		woocommerce_product_loop_start();

		while ( $query->have_posts() ):

			$query->the_post();
			$product = wc_get_product( $query->post->ID );

			if ( $product->get_attribute( 'required' ) )
				wc_get_template_part( 'content', 'product' );

		endwhile;

		woocommerce_product_loop_end();

	endif;
	wp_reset_postdata();

}

function sbs_render_featured_products( $current_step, $steps ) {

	$title = isset( get_option('sbs_general')['featured-label'] ) ? get_option('sbs_general')['featured-label'] : 'Featured Items';

	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => 12,
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => $steps[$current_step]->catid
			),
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
				'operator' => 'IN'
			)
		)
	);

	$query = new WP_Query( $args );

	if ( $query->have_posts() ):

		echo '<h3 class="sbs-subcat-name">' . $title . '</h3>';
		echo '<div class="woocommerce columns-4">';
		woocommerce_product_loop_start();

		while ( $query->have_posts() ):

			$query->the_post();

			wc_get_template_part( 'content', 'product' );

		endwhile;

		woocommerce_product_loop_end();
		echo '</div>';

	endif;
	wp_reset_postdata();

}


function sbs_render_product_category( $category_id ) {

	$sub_term = get_term_by('id', $category_id, 'product_cat', 'ARRAY_A');

	$req_args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => 24,
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => $category_id
			)
		)
	);

	$query = new WP_Query( $req_args );

	$optional_products = sbs_req_get_optional_products( $category_id );

	if ( $query->have_posts() && !empty( $optional_products ) ):

		$optional_label_before = isset( get_option('sbs_general')['opt-label-before'] ) ? get_option('sbs_general')['opt-label-before'] : null;
		$optional_label_after = isset( get_option('sbs_general')['opt-label-after'] ) ? get_option('sbs_general')['opt-label-after'] : '(Addons)';

		echo '<h3 class="sbs-subcat-name">' . esc_html( $optional_label_before ) . ' ' . $sub_term['name'] . ' ' . esc_html( $optional_label_after ) . '</h3>';
		echo '<p class="sbs-subcat-description">' . $sub_term['description'] . '</p>';
		echo '<div class="woocommerce columns-4">';
		woocommerce_product_loop_start();

		while ( $query->have_posts() ):

			$query->the_post();
			$product = wc_get_product( $query->post->ID );

			if ( !$product->get_attribute( 'required' ) )
				wc_get_template_part( 'content', 'product' );

		endwhile;

		woocommerce_product_loop_end();

	endif;
	wp_reset_postdata();

}


function sbs_render_step_by_step_ordering_content( $current_step, $steps ) {

	global $woocommerce;

  if ( $current_step === 0 ) {
		add_action( 'sbs_before_select_package', 'sbs_hide_sidebar_default_theme' );

		do_action( 'sbs_before_select_package' );
    echo do_shortcode( '[sbs_select_package]' );
    return;
  }

  if ( isset( $steps[$current_step]->catid ) ) {

		$cat_term = get_term_by( 'id', $steps[$current_step]->catid, 'product_cat', 'ARRAY_A' );
    $current_category_name = get_the_category_by_ID( $steps[$current_step]->catid );

    echo '<h1 class="sbs-step-title">Step ' . $current_step . ': ' . $current_category_name . '</h1>';
		echo '<p>' . $cat_term['description'] . '</p>';

		if ( isset( get_option('sbs_general')['featured-items-position'] ) && get_option('sbs_general')['featured-items-position'] === '1' ) {
			sbs_render_featured_products( $current_step, $steps );
		}

    if ( !empty( $steps[$current_step]->children ) ) {

      foreach( $steps[$current_step]->children as $subcategory ) {

				sbs_render_required_products( $subcategory->catid );

        sbs_render_product_category( $subcategory->catid );

      }

    }

		if ( !isset( get_option('sbs_general')['featured-items-position'] ) || get_option('sbs_general')['featured-items-position'] === '2' ) {
			sbs_render_featured_products( $current_step, $steps );
		}

    return;

  }

	if ( !isset( get_option('sbs_onf')['disabled'] ) && $current_step === count($steps) - 2 ) {

    echo '<h1 class="sbs-step-title">Step ' . $current_step . ': Options' . '</h1>';
		echo do_shortcode( '[sbs_options_and_fees]' );
		return;

	}

  if ($current_step === count($steps) - 1) {

    // echo '<h1 class="sbs-step-title">Step ' . $current_step . ': Checkout' . '</h1>';
    // echo do_shortcode( '[woocommerce_checkout]' );
		echo '<script type="text/javascript">window.location.href="' . esc_url( $woocommerce->cart->get_checkout_url() ) . '"</script>';
    return;

  }

}


/**
 *	Generate URLs for the SBS navbar
 *	Links will vary on the choice of navigation method selected in admin options
 *
 *	@param int $step_key: The current index of the element being iterated over in
 *												the array of SBS steps
 *				 int $current_step: The step of the page being currently viewed, given
 *														by $_GET['step']
 *				 int $step_count: The number of steps in the SBS step array
 *
 *  @return string URL link
 *
 */
function sbs_generate_navbar_url( $step_key, $current_step, $step_count ) {

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;

	$base_url = get_permalink( $sbs_page );

	$previous_step = $current_step - 1;
	$next_step = $current_step + 1;
	$nav_option = isset( get_option('sbs_navbar')['throttle-nav'] ) ? get_option('sbs_navbar')['throttle-nav'] : '1';

	if ( $step_key < $current_step ) {

		switch( $nav_option ) {
			case '1':
				return $base_url . '?step=' . $previous_step;
			case '2':
				return $base_url . '?step=' . $step_key;
			case '3':
				return $base_url . '?step=' . $step_key;
		}

	}

	if ( $step_key === $current_step ) {

		return false;

	}

	if ( $step_key > $current_step ) {

		switch( $nav_option ) {
			case '1':
				return $base_url . '?step=' . $next_step;
			case '2':
				return $base_url . '?step=' . $next_step;
			case '3':
				return $base_url . '?step=' . $step_key;
		}

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

	if ( !isset( get_option('sbs_onf')['disabled'] ) || get_option('sbs_onf')['disabled'] != 1 ) {

		$steps_onf = new stdClass();
		$steps_onf->name = get_the_category_by_ID( get_option('sbs_onf')['category'] );
		array_push( $steps, $steps_onf );

	}

  array_push( $steps, $steps_checkout );

  // Default to step 0 if an invalid step was requested
  if ( !array_key_exists( $current_step, $steps ) ) {
    $current_step = 0;
  }

	do_action( 'sbs_before_sbs_content' );

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
										<span class="step-number-before <?php echo $key === $current_step ? 'active' : 'inactive' ?>"></span>
                    <span class="step-number <?php echo $key === $current_step ? 'active' : 'inactive' ?>">
                      <?php echo $key ?>
                    </span>
										<span class="step-number-after"></span>
                  </div>
                  <div class="step-title <?php echo $key === $current_step ? 'active' : 'inactive' ?>">
										<span class="step-title-text">
	                    <?php

												if ( sbs_generate_navbar_url( $key, $current_step, count( $steps ) ) !== false )
												{
												?>
													<a href="<?php echo esc_url( sbs_generate_navbar_url( $key, $current_step, count($steps) ) ) ?>"><?php echo $step->name ?></a>
												<?php
												}
												else
												{
												?>
													<?php echo $step->name ?>
												<?php
												}

	                    ?>
										</span>
                  </div>
									<div class="clearfix"></div>
                </div>
              </span>

      <?php } ?>
    </div>

    <div>
      <?php wc_print_notices() ?>
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
      <?php echo sbs_previous_step_button( $current_step, count($steps) ) ?>
    </div>
    <div class="sbs-store-back-forward-buttons">
      <?php echo sbs_next_step_button( $current_step, count($steps) ) ?>
    </div>
  </div>

  <?php
  }
  ?>

  <?php

  return ob_get_clean();
}

add_shortcode( 'sbs_woocommerce_step_by_step_ordering', 'sbs_woocommerce_step_by_step_ordering_shortcode' );
