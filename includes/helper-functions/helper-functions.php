<?php

/**
 * Gets the parent category of a specified product.
 *
 * Get all WooCommerce product categories for the specified product, then looks
 * through the parent property of each of them. A parent property with value 0
 * means the category is top-level.
 *
 *
 * @param int $product_id : The ID of the product
 *
 *
 * @return object $category : A category object returned by wp_get_post_terms
 */

function sbs_get_product_parent_category( $product_id ) {

  $categories = wp_get_post_terms($product_id, 'product_cat');

  foreach ($categories as $key => $category) {
    if ($category->parent === 0)
      return $category;
  }

}

function sbs_get_wc_products_by_category( $category_id ) {

  sbs_get_all_wc_categories();

  $args = array(
    'post_type' => 'product',
    'product_cat' => get_term_by('id', $category_id, 'product_cat')->slug
  );

  $products = get_posts( $args );

  return $products;

}
