<?php
/**
 * Content Single Tour
 *
 * @author : Physcode
 * @version: 2.0.0
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


?>

<?php
$tour_show_only_form_enquiry = get_post_meta( get_the_ID(), '_tour_show_only_form_enquiry', true );

if ( $tour_show_only_form_enquiry == '' ) {
	$tour_show_only_form_enquiry = 0;
}

do_action( 'tour_booking_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form();

	return;
}
?>

<div id="tour-<?php the_ID(); ?>" <?php post_class( 'tb_single_tour' ); ?> class="tb_single_tour">

	<?php
	do_action( 'tour_booking_before_single_tour' );

	do_action( 'tour_booking_single_gallery' );
	?>

	<div class="summary entry-summary">
		<?php
		/**
		 * tour_booking_single_title hook.
		 */
		do_action( 'tour_booking_single_title' );

		/**
		 * tour_booking_single_ratting hook.
		 */
		do_action( 'tour_booking_single_ratting' );
		/**
		 * tour_booking_single_price hook.
		 */
		do_action( 'tour_booking_single_price' );


		/**
		 * tour_booking_single_code hook.
		 */
		do_action( 'tour_booking_single_code' );

		if ( ! $tour_show_only_form_enquiry ) {
			/**
			 * tour_booking_single_booking hook.
			 */
			do_action( 'tour_booking_single_booking' );
		}
		?>
	</div>

	<div class="clear"></div>
	<?php
    do_action('tour_booking_single_faq');

	do_action( 'tour_booking_single_information' );

	do_action( 'tour_booking_single_related' );

	do_action( 'tour_booking_after_single_tour' );
	?>

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'tour_booking_after_single_product' ); ?>
