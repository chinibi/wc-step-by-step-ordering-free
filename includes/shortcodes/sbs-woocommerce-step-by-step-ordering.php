<?php

/**
 *	Step-By-Step Ordering Shortcode
 *
 *	This is the main shortcode for rendering SBS content, from package selection
 *	to product selection, to checkout.
 *
 */

if ( !defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

function sbs_previous_step_url( $current_step, $step_count ) {

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;
	$package_page = isset( get_option('sbs_package')['page-name'] ) ? get_option('sbs_package')['page-name'] : get_page_by_title( 'Choose Package' )->ID;
	$package_enabled = sbs_is_package_section_active();

	$sbs_base_url = get_permalink( $sbs_page );
	$package_base_url = get_permalink( $package_page );

	if ( $current_step > 0 ) {

		$previous_step = $current_step - 1;

		if ( $previous_step === 0 && !$package_enabled ) {
			return null;
		}

		return $previous_step === 0 ? $package_base_url : $sbs_base_url . '?step=' . $previous_step;

	} else {

		return null;

	}

}

function sbs_previous_step_button( $current_step, $step_count ) {

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;
	$package_page = isset( get_option('sbs_package')['page-name'] ) ? get_option('sbs_package')['page-name'] : get_page_by_title( 'Choose Package' )->ID;
	$package_enabled = sbs_is_package_section_active();

	$base_url = get_permalink( $sbs_page );
	$package_base_url = get_permalink( $package_page );

	if ( $current_step > 0 ) {

		$previous_step = $current_step - 1;

		if ( $previous_step === 0 && !$package_enabled ) {
			return '<div style="border: none; background: none;" class="sbs-store-back-forward-buttons"></div>';
		}

		ob_start();
		?>
		<?php if ($previous_step === 0): ?>
				<div class="sbs-store-back-forward-buttons">
						<a href="<?php echo esc_url($package_base_url) ?>">&#171; <?php echo __('GO BACK', 'wc-step-by-step-ordering') ?></a>
				</div>
		<?php else: ?>
				<div class="sbs-store-back-forward-buttons">
						<a href="<?php echo esc_url($base_url . '?step=' . $previous_step) ?>">&#171; <?php echo __('GO BACK', 'wc-step-by-step-ordering') ?></a>
				</div>
		<?php endif ?>
		<?php
		return ob_get_clean();

	} else {

		return '<div style="border: none; background: none;" class="sbs-store-back-forward-buttons"></div>';

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
		<div class="sbs-store-back-forward-buttons">
      <a href="<?php echo esc_url($base_url . '?step=' . $next_step) ?>"><?php echo __('NEXT', 'wc-step-by-step-ordering') ?> &#187;</a>
    </div>
		<?php
		return ob_get_clean();

	} else {

		return null;

	}

}


function sbs_render_required_products( $category_id, $columns ) {

	// $title = isset( get_option('sbs_step_section_label')['req-label-' . $current_step] ) ? get_option('sbs_step_section_label')['req-label-' . $current_step] . ' (Required)' : 'Featured Items';

	$sub_term = get_term_by('id', $category_id, 'product_cat', 'ARRAY_A');

	$req_args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => -1,
		'meta_key' => '_required_product',
		'meta_value' => 'yes',
		'orderby' => 'menu_order',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => $category_id
			)
		)
	);

	$query = new WP_Query( $req_args );

	// $required_products = sbs_req_get_required_products( $category_id );

	if ( $query->have_posts() ):

		$required_label_before = isset( get_option('sbs_general')['req-label-before'] ) ? get_option('sbs_general')['req-label-before'] : 'Select';
		$required_label_after = isset( get_option('sbs_general')['req-label-after'] ) ? get_option('sbs_general')['req-label-after'] : '(Required)';

		echo '<h3 class="sbs-subcat-name">' . esc_html( $required_label_before ) . ' ' . $sub_term['name'] . ' ' . esc_html( $required_label_after ) . '</h3>';
		echo '<p class="sbs-subcat-description">' . $sub_term['description'] . '</p>';
		echo '<div class="woocommerce columns-' . $columns . '">';
		woocommerce_product_loop_start();

		while ( $query->have_posts() ):

			$query->the_post();
			$product = wc_get_product( $query->post->ID );

			wc_get_template_part( 'content', 'product' );

		endwhile;

		woocommerce_product_loop_end();
		echo '</div>';

	endif;
	wp_reset_postdata();

}

function sbs_render_featured_products( $current_step, $steps, $columns ) {

	$title = isset( get_option('sbs_general')['featured-label'] ) ? get_option('sbs_general')['featured-label'] : 'Featured Items';

	$args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => -1,
		'order' => 'ASC',
		'orderby' => 'menu_order',
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
		echo '<div class="woocommerce columns-' . $columns . '">';
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


function sbs_render_optional_products( $category_id, $columns ) {

	$sub_term = get_term_by('id', $category_id, 'product_cat', 'ARRAY_A');

	$req_args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => -1,
    'order' => 'ASC',
		'orderby' => 'menu_order',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => $category_id
			)
		)
	);

	$featured_visible = isset( get_option('sbs_general')['featured-items-visibility'] ) ? get_option('sbs_general')['featured-items-visibility'] : '1';

	if ( $featured_visible != '1' ) {
		$req_args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
			'operator' => 'NOT IN'
		);
	}

	$query = new WP_Query( $req_args );

	$optional_products = sbs_req_get_optional_products( $category_id );

	if ( $query->have_posts() && !empty( $optional_products ) ):

		$optional_label_before = isset( get_option('sbs_general')['opt-label-before'] ) ? get_option('sbs_general')['opt-label-before'] : null;
		$optional_label_after = isset( get_option('sbs_general')['opt-label-after'] ) ? get_option('sbs_general')['opt-label-after'] : '(Addons)';

		echo '<h3 class="sbs-subcat-name">' . esc_html( $optional_label_before ) . ' ' . $sub_term['name'] . ' ' . esc_html( $optional_label_after ) . '</h3>';
		echo '<p class="sbs-subcat-description">' . $sub_term['description'] . '</p>';
		echo '<div class="woocommerce columns-' . $columns . '">';
		woocommerce_product_loop_start();

		while ( $query->have_posts() ):

			$query->the_post();
			$product = wc_get_product( $query->post->ID );
			$is_required = get_post_meta( $product->get_id(), '_required_product', true ) === 'yes';

			if ( !$is_required )
				wc_get_template_part( 'content', 'product' );

		endwhile;

		woocommerce_product_loop_end();
		echo '</div>';

	endif;
	wp_reset_postdata();

}


function sbs_render_product_category( $category_id, $columns ) {

	$sub_term = get_term_by('id', $category_id, 'product_cat', 'ARRAY_A');

	$req_args = array(
		'post_type' => 'product',
		'post_status' => 'publish',
		'ignore_sticky_posts'	=> 1,
		'posts_per_page' => -1,
		'order' => 'ASC',
		'orderby' => 'menu_order',
		'tax_query' => array(
			array(
				'taxonomy' => 'product_cat',
				'field' => 'id',
				'terms' => $category_id
			)
		)
	);

	$featured_visible = isset( get_option('sbs_general')['featured-items-visibility'] ) ? get_option('sbs_general')['featured-items-visibility'] : '1';

	if ( $featured_visible != '1' ) {
		$req_args['tax_query'][] = array(
			'taxonomy' => 'product_visibility',
			'field'    => 'name',
			'terms'    => 'featured',
			'operator' => 'NOT IN'
		);
	}

	$query = new WP_Query( $req_args );

	if ( $query->have_posts() ):

		echo '<h3 class="sbs-subcat-name">' . $sub_term['name'] . '</h3>';
		echo '<p class="sbs-subcat-description">' . $sub_term['description'] . '</p>';
		echo '<div class="woocommerce columns-' . $columns . '">';
		woocommerce_product_loop_start();

		while ( $query->have_posts() ):

			$query->the_post();
			$product = wc_get_product( $query->post->ID );

			wc_get_template_part( 'content', 'product' );

		endwhile;

		woocommerce_product_loop_end();
		echo '</div>';

	endif;
	wp_reset_postdata();

}


function sbs_render_step_by_step_ordering_content( $current_step, $steps, $columns ) {

	global $woocommerce;

	if ( $current_step === 0 ) {

		$package_page = isset( get_option('sbs_package')['page-name'] ) ? get_option('sbs_package')['page-name'] : get_page_by_title( 'Choose Package' )->ID;

		echo sprintf( '<script>window.location.href="%s"</script>', get_permalink( $package_page ) );
		return;

	}

	if ( $steps[$current_step]->type === 'main' ) {

		$cat_term = get_term_by( 'id', $steps[$current_step]->catid, 'product_cat', 'ARRAY_A' );
		$current_category_name = get_term( $steps[$current_step]->catid )->name;

		echo '<h1 class="sbs-step-title">' . __('Step', 'wc-step-by-step-ordering') . ' ' . $current_step . ': ' . $current_category_name . '</h1>';
		echo '<p>' . $cat_term['description'] . '</p>';

		if ( isset( get_option('sbs_general')['featured-items-position'] ) && get_option('sbs_general')['featured-items-position'] === '1' ) {
			sbs_render_featured_products( $current_step, $steps, $columns );
		}

		if ( !empty( $steps[$current_step]->children ) ) {

			foreach( $steps[$current_step]->children as $subcategory ) {
				sbs_render_product_category( $subcategory->catid, $columns );
			}

		}

		if ( !isset( get_option('sbs_general')['featured-items-position'] ) || get_option('sbs_general')['featured-items-position'] === '2' ) {
			sbs_render_featured_products( $current_step, $steps, $columns );
		}

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

	$nav_option = 2;

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


function sbs_render_sbs_navbar( $current_step, $steps ) {

	foreach( $steps as $key => $step ):
		if ($key === 0) continue;
	?>

		<span class="step-span-container">
			<div class="step-div-container">
				<div class="step-index">
					<span class="step-number-before <?php echo $key === $current_step ? 'active' : 'inactive' ?>"></span>
					<span class="step-number <?php echo $key === $current_step ? 'active' : 'inactive' ?>">
						<a href="<?php echo esc_url( sbs_generate_navbar_url( $key, $current_step, count($steps) ) ) ?>"><?php echo $key ?></a>
					</span>
					<span class="step-number-after"></span>
				</div>
				<div class="step-title <?php echo $key === $current_step ? 'active' : 'inactive' ?>">
					<span class="step-title-text">

						<?php
						if ( sbs_generate_navbar_url( $key, $current_step, count( $steps ) ) !== false ):
						?>
							<a href="<?php echo esc_url( sbs_generate_navbar_url( $key, $current_step, count($steps) ) ) ?>"><?php echo $step->name ?></a>
						<?php
						else:
						?>
							<?php echo esc_html( $step->name ) ?>
						<?php
						endif;
						?>

					</span>
				</div>
				<div class="clearfix"></div>
			</div>
		</span>

	<?php
	endforeach;

}


function sbs_woocommerce_step_by_step_ordering_shortcode( $atts = [] ) {

	// Set default shortcode attributes
	$atts = shortcode_atts(
		array(
			'columns' => '4'
		), $atts, 'sbs_woocommerce_step_by_step_ordering'
	);
	$columns = $atts['columns'];
	add_filter( 'loop_shop_columns', function( $cols ) use ( $columns ) {
		return $columns;
	}, 10 );

	$active_packages = sbs_get_active_packages();
	$packages_enabled = get_option('sbs_package')['enabled'];

	$default_step = empty( $active_packages ) || !$packages_enabled ? 1 : 0;

	$current_step = isset( $_GET['step'] ) && is_numeric( $_GET['step'] ) ? (int) $_GET['step'] : $default_step;

	$all_categories = sbs_get_all_wc_categories();

	$steps = sbs_get_full_step_order( true );

	if ( empty( $steps ) ) {
		return '<div>This is the SBS Step-By-Step Ordering Page.  No ordering process has been set up yet; please access your admin settings and set up an ordering process.</div>';
	}

	foreach( $steps as $step ) {
		if ( !empty( $step->children ) ) {
			 $step->children = array_slice( $step->children, 0, 2 );
		}
	}

	// Default to step 0 if an invalid step was requested
	if ( !array_key_exists( $current_step, $steps ) ) {
		$current_step = 0;
	}

	do_action( 'sbs_before_sbs_content', $current_step, $steps );

	ob_start();
	?>

	<?php
	if ( $current_step > 0 ): // Do not render on package selection page.
		?>
		<div id="sbs-navbar">
			<?php sbs_render_sbs_navbar( $current_step, $steps ) ?>
		</div>

		<div>
			<?php wc_print_notices() ?>
		</div>
		<?php
	endif;
	?>

	<div>
		<?php sbs_render_step_by_step_ordering_content( $current_step, $steps, $columns ) ?>
	</div>

	<?php
	if ( $current_step > 0 ): // Do not render on package selection page.
	?>

		<div id="sbs-store-back-forward-buttons-container">
			<?php echo sbs_previous_step_button( $current_step, count($steps) ) ?>
			<?php echo sbs_next_step_button( $current_step, count($steps) ) ?>
		</div>

	<?php
	endif;
	?>

	<?php

	return ob_get_clean();
}

add_shortcode( 'sbs_woocommerce_step_by_step_ordering', 'sbs_woocommerce_step_by_step_ordering_shortcode' );
