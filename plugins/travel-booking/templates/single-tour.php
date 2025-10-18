<?php
/**
 * Single tour
 *
 * @author : Physcode
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
get_header( 'shop' );

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked travelbooking_output_content_wrapper - 10 (outputs opening divs for the content)
 */
do_action( 'travelbooking_before_main_content' );

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked travelbooking_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked tour_booking_breadcrumb - 20
 */
do_action( 'travelbooking_before_loop' );


while ( have_posts() ) :
	the_post();

	tb_get_file_template( 'content-single-tour.php' );

endwhile;

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked travelbooking_wrapper_after_loop_end - 20 (outputs opening divs for the content left)
 */
do_action( 'travelbooking_after_loop' );

/**
 * travelbooking_sidebar hook.
 *
 * @hooked travelbooking_get_sidebar - 10
 */
do_action( 'travelbooking_sidebar' );

/**
 * woocommerce_after_main_content hook.
 *
 * @hooked travelbooking_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action( 'travelbooking_after_main_content' );

get_footer( 'shop' );

