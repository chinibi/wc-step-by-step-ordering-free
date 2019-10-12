<?php

function sbs_render_package_selection_box( $product_id, $per_row ) {

	$package = wc_get_product( $product_id );

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;
	$base_url = get_permalink( $sbs_page );

	$add_to_cart_url = $base_url . '?step=1&add-to-cart=' . $product_id;

	$add_to_cart_text = apply_filters( 'sbs_select_package_add_to_cart_text', __('Select Package', 'wc-step-by-step-ordering') );

	ob_start();
	?>
	<div class="sbs-package-container woocommerce">
		<?php if ( $package->get_image( 'shop_thumbnail', array(), false ) && get_option('sbs_general')['hide-placeholder-images'] ): ?>
			<div class="sbs-package-thumbnail">
				<?php
				$package_image_height = isset( get_option('sbs_package')['image-height'] ) ? get_option('sbs_package')['image-height'] : null;
				$package_image_width = isset( get_option('sbs_package')['image-width'] ) ? get_option('sbs_package')['image-width'] : null;

				if ( !empty( $package_image_height ) && !empty( $package_image_width ) ) {
					echo $package->get_image( array( $package_image_width, $package_image_height ) );
				} else {
					echo $package->get_image();
				}
				?>
			</div>
		<?php endif ?>
		<div class="sbs-package-title">
			<?php echo $package->get_name() ?>
		</div>
		<div class="sbs-package-price">
			<?php echo wc_price( $package->get_price() ) ?>
		</div>
		<div class="sbs-package-content sbs-package-content-per-row-<?php echo esc_attr($per_row) ?>">
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

function sbs_responsive_column_reset( $index, $per_row ) {
	switch( $per_row ) {
		case 1:
			$grid_class = 'col-sm-6';
			$offset_grid_class = ' col-sm-offset-3';
			break;
		case 2:
			if ( ($index + 1) % 2 === 0 ) {
				echo '<div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div>';
			}
			break;
		case 3:
			if ( ($index + 1) % 3 === 0 ) {
				echo '<div class="clearfix visible-sm-block visible-md-block visible-lg-block"></div>';
			}
			break;
		case 4:
			if ( ($index + 1) % 3 === 0 ) {
				echo '<div class="clearfix visible-sm-block"></div>';
			}
			if ( ($index + 1) % 4 === 0 ) {
				echo '<div class="clearfix visible-md-block visible-lg-block"></div>';
			}
			break;
		case 5:
			if ( ($index + 1) % 3 === 0 ) {
				echo '<div class="clearfix visible-sm-block"></div>';
			}
			if ( ($index + 1) % 5 === 0 ) {
				echo '<div class="clearfix visible-md-block visible-lg-block"></div>';
			}
			break;
	}
}

function sbs_select_package_shortcode() {

	$active = sbs_is_package_section_active();
	$steps = sbs_get_full_step_order();
	$packages = sbs_get_active_packages( true );
	// $per_row = isset( get_option('sbs_package')['per-row'] ) ? get_option('sbs_package')['per-row'] : 1;
	$per_row = 1;

	switch( $per_row ) {
		case 1:
			$grid_class = 'col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2';
			break;
		case 2:
			$grid_class = 'col-sm-6';
			break;
		case 3:
			$grid_class = 'col-sm-4';
			break;
		case 4:
			$grid_class = 'col-md-3 col-sm-4';
			break;
		case 5:
			$grid_class = 'col-md-5ths col-sm-4';
			break;
	}

	ob_start();
	?>
	<?php
	if ( !$packages || !$active )
	{
	?>
		<div>
		<?php echo apply_filters(
			'sbs_empty_package_placeholder',
			sprintf(
				'Welcome to the Step-By-Step Ordering Process. Click <a href="%s">here</a> to get started.',
				sbs_next_step_url( 0, count( $steps ) )
				)
			); ?>
		</div>
	<?php
	} else {
	?>
		<div id="sbs-package-list" class="sbs-container">
			<?php foreach( $packages as $index => $package ) {
			echo '<div class="grid-container ' . $grid_class . '">';
			echo sbs_render_package_selection_box( $package->catid, $per_row );
			echo '</div>';
			echo sbs_responsive_column_reset( $index, $per_row );
			} ?>
		</div>

	<?php
	}

	return ob_get_clean();

}


add_shortcode( 'sbs_select_package', 'sbs_select_package_shortcode' );
