<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * @author : Physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header();

global $wp_query;
$title = apply_filters( 'title_tours_page_default', 'Tours' );
if ( ! is_null( single_term_title( '', false ) ) ) {
	$title = single_term_title( '', false );
} elseif ( isset( get_queried_object()->ID ) ) {
	$title = get_the_title( get_queried_object()->ID );
} elseif ( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) ) {
	$title = get_the_title( get_option( Tour_Settings_Tab_Phys::$_tours_show_page_id ) );
}

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

echo '<h1 class="page-title">' . $title . '</h1>';

/**
 * Hook: woocommerce_before_shop_loop.
 *
 * @hooked travelbooking_result_count - 20
 * @hooked travelbooking_catalog_ordering - 30
 */
do_action( 'travelbooking_result_count' );
global $wp_query;

if ( have_posts() ) : ?>
	<ul class="tours-default">

		<?php
		while ( have_posts() ) :
			the_post();
			?>
			<?php tb_get_file_template( 'content-tour.php', false ); ?>

		<?php endwhile; ?>
	</ul>

<?php else : ?>

	<p class="woocommerce-info"><?php _e( 'No tours were found matching your selection.', 'travel-booking' ); ?></p>

<?php endif;

tb_get_file_template( 'loop/pagination.php' );

/**
 * woocommerce_before_main_content hook.
 *
 * @hooked travelbooking_wrapper_after_loop_end - 10 (outputs opening divs for the content left)
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


get_footer();
?>
