<?php

function sbs_render_package_selection_box( $product_id, $per_row ) {

	$package = wc_get_product( $product_id );

	$sbs_page = isset( get_option('sbs_general')['page-name'] ) ? get_option('sbs_general')['page-name'] : get_page_by_title( 'Step-By-Step Ordering' )->ID;
	$base_url = get_permalink( $sbs_page );

	$add_to_cart_url = $base_url . '?step=1&add-to-cart=' . $product_id;

	$add_to_cart_text = apply_filters( 'sbs_select_package_add_to_cart_text', 'Select Package' );

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

function sbs_select_package_shortcode() {

	$active = sbs_is_package_section_active();
	$steps = sbs_get_full_step_order();
	$packages = sbs_get_active_packages( true );
	$per_row = isset( get_option('sbs_package')['per-row'] ) ? get_option('sbs_package')['per-row'] : 3;

	switch ( $per_row ) {
		case 1:
		$container_width = '51%';
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
	<style>
	@media (min-width: 768px) {
		.sbs-package-container.woocommerce {
			<?php echo apply_filters(
				'sbs_package_container_flex_style',
				sprintf('flex: 0 1 calc(%s - 4px);', $container_width ),
				$container_width
			); ?>
		}
	}
	</style>
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

		<div id="sbs-package-list">
		<?php foreach( $packages as $package ) {
			echo sbs_render_package_selection_box( $package->catid, $per_row );
		} ?>
		</div>

	<?php
	}

	return ob_get_clean();

}


add_shortcode( 'sbs_select_package', 'sbs_select_package_shortcode' );
