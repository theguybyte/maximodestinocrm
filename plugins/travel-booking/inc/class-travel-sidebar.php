<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * TravelPhysSidebar
 *
 * @author  physcode
 * @version 1.0.0
 */
class TravelPhysSidebar {

	public static function init() {
		add_action( 'widgets_init', array( __CLASS__, 'register_sidebar_tour_phys' ), 20 );
	}

	public static function register_sidebar_tour_phys() {
		$args = array(
			'name'          => __( 'Sidebar Tours', 'travel-booking' ),
			'id'            => 'sidebar-tour-phys',
			'description'   => '',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		);

		register_sidebar( $args );
	}
}

TravelPhysSidebar::init();

