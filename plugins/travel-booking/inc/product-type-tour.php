<?php
/**
 * Register product type tour
 */
add_action( 'plugins_loaded', 'register_product_type_tour_phys', 20 );
function register_product_type_tour_phys() {
	class WC_Product_Tour_Phys extends WC_Product {
		/**
		 * Tour product type
		 * @var string
		 */
		public string $product_type = TB_PHYS_PRODUCT_TYPE;

		public function __construct( $product ) {
			parent::__construct( $product );
		}
	}
}

/**
 * show option Tour
 */
add_filter( 'product_type_selector', 'add_type_tour_to_product_phys' );
function add_type_tour_to_product_phys( $types ) {
	// $types['tour_phys'] = __( 'Tour', 'travel-booking' );
	$tour_type = array( 'tour_phys' => __( 'Tour', 'travel-booking' ) );
	return array_merge( $tour_type, $types );
}

add_action( 'admin_menu', 'settings_menu', 2 );
function settings_menu() {
	global $submenu;
	if ( ! empty( $submenu['edit.php?post_type=product'] ) ) {
		$productsMenu = &$submenu['edit.php?post_type=product'];
		array_unshift(
			$productsMenu,
			array(
				__( 'Tours', 'travel-booking' ),
				'edit_products',
				'edit.php?post_type=product&product_type=tour_phys',
			)
		);
	}
}

add_action( 'manage_product_posts_custom_column', 'remove_action_show_product_admin', 5 );
function remove_action_show_product_admin( $column ) {
	global $post, $the_product;
	switch ( $column ) {
		case 'name':
			if ( ! isset( $_GET['product_type'] ) ) {
				if ( $the_product->get_type() == 'tour_phys' ) {
					echo '<input type="hidden" name="hidden_tour_phys" value="true">';
				}
			} else {
				ob_start();
				echo '<input type="hidden" name="active_tour_css" value="true">';
				ob_clean();
			}

			break;
	}
}

