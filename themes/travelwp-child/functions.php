<?php
 function travelwp_child_enqueue_styles() {
	wp_deregister_style( 'travelwp-style' );

	$parent_style = 'parent-style';
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'child-style', get_stylesheet_uri(), array( $parent_style ) );
	if ( is_file( TRAVELWP_UPLOADS_FOLDER . 'physcode_travelwp.css' ) ) {
		wp_deregister_style( 'physcode_travelwp' );
		wp_enqueue_style( 'physcode_travelwp_child', TRAVELWP_UPLOADS_URL . 'physcode_travelwp.css', array() );
	}
}

add_action( 'wp_enqueue_scripts', 'travelwp_child_enqueue_styles', 11 );
//remove_action( 'tour_booking_single_booking', 'tour_booking_single_booking' );