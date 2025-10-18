<?php
/**
 *
 *
 */

add_action( 'after_setup_theme', 'create_tour_taxonomies_phys', 20 );
function create_tour_taxonomies_phys() {
	$labels = array(
		'name'              => __( 'Tour Categories', 'travel-booking' ),
		'singular_name'     => __( 'Tour Category', 'travel-booking' ),
		'search_items'      => __( 'Search Tour Categories', 'travel-booking' ),
		'all_items'         => __( 'All Tour Categories', 'travel-booking' ),
		'parent_item'       => __( 'Parent Tour Category', 'travel-booking' ),
		'parent_item_colon' => __( 'Parent Tour Category:', 'travel-booking' ),
		'edit_item'         => __( 'Edit Tour Category', 'travel-booking' ),
		'update_item'       => __( 'Update Tour Category', 'travel-booking' ),
		'add_new_item'      => __( 'Add New Tour Category', 'travel-booking' ),
		'new_item_name'     => __( 'New Tour Category Name', 'travel-booking' ),
		'menu_name'         => __( 'Tour Category', 'travel-booking' ),
	);

	$args = array(
		'hierarchical'      => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => array( 'slug' => 'tour-category' ),
	);

	register_taxonomy( 'tour_phys', array( 'product' ), $args );
}
