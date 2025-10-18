<?php
/**
 * Form Booking Travel
 *
 * @version 2.0.0
 * @author  Physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
$product = wc_get_product( get_the_ID() );
if( !$product){
	return;
};
$phys_enable_tour_fixed_duration      = 0;
$phys_tour_calendar_type              = 0;
$phys_dates_disable                   = '';
$phys_max_year_enable                 = '';
$phys_price_dates_type                = 'price_dates_range';
$phys_price_of_dates_option           = '';
$phys_starts_after                    = '';
$tour_show_only_form_enquiry          = 0;
$phys_tour_duration_number            = 1;
$total_price_tour_phys                = 0;
$tour_min_number_ticket_per_booking   = $tour_max_number_children_per_booking = 0;
$tour_min_number_children_per_booking = $tour_max_number_ticket_per_booking = 0;

foreach ( TravelPhysTab::$_fields_tab_tour_booking as $key => $val ) {
	global ${$key};
	${$key} = get_post_meta( get_the_ID(), '_' . $key, true ) !== '' ? get_post_meta( get_the_ID(), '_' . $key, true ) : $val['default'];
}

$price_child = TravelPhysTab::instance()->get_tour_meta( get_the_ID(), '_price_child' );

foreach ( TravelPhysTab::$field_arr as $key => $val ) {
	global ${$key};
	${$key} = get_post_meta( get_the_ID(), '_' . $key, true ) !== '' ? get_post_meta( get_the_ID(), '_' . $key, true ) : '';
}

foreach ( TravelPhysTab::$_fields_tab_tour_dates_price as $key => $val ) {
	${$key} = get_post_meta( get_the_ID(), '_' . $key, true ) !== '' ? get_post_meta( get_the_ID(), '_' . $key, true ) : $val['default'];
}

foreach ( TravelPhysTab::$_fields_tab_tour_group_discount as $key => $val ) {
	${$key} = get_post_meta( get_the_ID(), '_' . $key, true ) !== '' ? get_post_meta( get_the_ID(), '_' . $key, true ) : '';
}

$start_date                        = get_post_meta( get_the_ID(), '_tour_start_date', true );
$end_date                          = get_post_meta( get_the_ID(), '_tour_end_date', true );
$tour_days                         = get_post_meta( get_the_ID(), '_price_each_day', true );
$number_ticket                     = get_post_meta( get_the_ID(), '_tour_number_ticket', true );
$min_number_ticket_per             = (float) $number_ticket == 0 ? 0 : 1;
$_page_redirect_after_tour_booking = get_option( Tour_Settings_Tab_Phys::$_page_redirect_after_tour_booking );
$checkout_page_id                  = get_option( 'woocommerce_checkout_page_id' );
$woocommerce_price_num_decimals    = get_option( 'woocommerce_price_num_decimals' );

if ( is_user_logged_in() ) {
	$user       = wp_get_current_user();
	$first_name = get_user_meta( $user->ID, 'billing_first_name', true );
	$last_name  = get_user_meta( $user->ID, 'billing_last_name', true );
	$email      = get_user_meta( $user->ID, 'billing_email', true );
	$phone      = get_user_meta( $user->ID, 'billing_phone', true );
}

$show            = true;
$show_first_name = apply_filters( 'tb_show_first_name', $show );
$show_last_name  = apply_filters( 'tb_show_last_name', $show );
$show_email      = apply_filters( 'tb_show_email', $show );
$show_phone      = apply_filters( 'tb_show_phone', $show );
$show_date_book  = apply_filters( 'tb_show_date_book', $show );

$tour                            = wc_get_product( get_the_ID() );
$stock_status                    = $tour->get_stock_status();
$manage_stock                    = $tour->get_manage_stock();
$stock_qty                       = $tour->get_stock_quantity();
$class_field_calculate           = ' field-travel-booking';
$phys_enable_tour_fixed_duration = (int) $phys_enable_tour_fixed_duration;
$phys_tour_calendar_type         = (int) $phys_tour_calendar_type;
$tour_show_only_form_enquiry     = (int) $tour_show_only_form_enquiry;
$phys_tour_duration_number       = (int) $phys_tour_duration_number;
if(isset($data['tour_show_only_form_enquiry'])){
	$tour_show_only_form_enquiry     = (int)$data['tour_show_only_form_enquiry'];
}	
if ( $phys_tour_duration_number == 0 ) {
	$phys_tour_duration_number = 1;
}

if ( (int) $tour_min_number_ticket_per_booking <= 0 ) { 
	$tour_number_ticket = 1;
} else {
	$tour_number_ticket = $tour_min_number_ticket_per_booking;
}
if ( (float) $product->get_price() > 0 && ! $tour_show_only_form_enquiry ) { 
	$total_price_tour_phys = $product->get_price() * $tour_number_ticket;
	?>

	<div class="booking">
		<?php
		if ( $stock_status == 'instock' ) {
			?>
			<div class="">
				<div class="form-block__title">
					<h4><?php _e( 'Book the tour', 'travel-booking' ); ?></h4>
				</div>
				<form id="tourBookingForm" method="POST" action="">

					<?php if ( $show_first_name ) { ?>
						<div class="form-field">
							<input name="first_name" value="<?php echo isset( $first_name ) ? $first_name : ''; ?>"
								   placeholder="<?php _e( 'First name', 'travel-booking' ); ?>" type="text">
						</div>
					<?php } ?>
					<?php if ( $show_last_name ) { ?>
						<div class="form-field">
							<input name="last_name" value="<?php echo isset( $last_name ) ? $last_name : ''; ?>"
								   placeholder="<?php _e( 'Last name', 'travel-booking' ); ?>" type="text">
						</div>
					<?php } ?>
					<?php if ( $show_email ) { ?>
						<div class="form-field">
							<input name="email_tour" value="<?php echo isset( $email ) ? $email : ''; ?>"
								   placeholder="<?php _e( 'Email', 'travel-booking' ); ?>" type="text">
						</div>
					<?php } ?>
					<?php if ( $show_phone ) { ?>
						<div class="form-field">
							<input name="phone" value="<?php echo isset( $phone ) ? $phone : ''; ?>"
								   placeholder="<?php _e( 'Phone', 'travel-booking' ); ?>" type="text">
						</div>
					<?php } ?>
					<?php
					if ( $show_date_book ) {
						if ( ! $phys_enable_tour_fixed_duration ) {
							if ( $phys_tour_calendar_type == 0 ) {
								?>
								<div class="tour_date_checkin_checkout">
									<div class="form-field">
										<input type="text" name="tour_date_check_in" value=""
											   placeholder="<?php _e( 'Date check in', 'travel-booking' ); ?>"
											   readonly='true'>
									</div>
									<div class="form-field">
										<input type="text" name="tour_date_check_out" value=""
											   placeholder="<?php _e( 'Date check out', 'travel-booking' ); ?>"
											   readonly='true'>
									</div>
									<div class="number-days form-field">
										<?php echo __( 'Total Night', 'travel-booking' ); ?>:&nbsp;<span
												class="number-day"></span>
									</div>
								</div>
								<?php
							} else {
								?>
								<div class="tour-datepicker-range-checkin-checkout">
									<input type="text" name="tour_datepicker_range_checkin_checkout" value=""
										   placeholder="<?php _e( 'Date check in and check out', 'travel-booking' ); ?>"
										   readonly='true'>
								</div>
								<?php
							}
						} else {
							?>
							<div class="form-field">
								<input type="text" name="date_book" value=""
									   placeholder="<?php _e( 'Date Book', 'travel-booking' ); ?>" readonly="true">
							</div>
							<?php
						}

						echo '<input type="hidden" name="phys_tour_max_year_enable" value="' . $phys_max_year_enable . '">';
						echo '<input type="hidden" name="phys_tour_dates_disable" value="' . htmlentities( $phys_dates_disable ) . '">';
						echo '<input type="hidden" name="phys_tour_price_dates_type" value="' . $phys_price_dates_type . '">';
						echo '<input type="hidden" name="phys_tour_price_of_dates_option" value="' . htmlentities( $phys_price_of_dates_option ) . '">';
					}
					?>

					<!-- Hook add more element after date book tour -->
					<?php
					/**
					 * 1.html_tour_variation_on_booking_form
					 */
					do_action( 'tmpl_element_after_date_book_tour_booking_form' )
					?>

					<!-- For stock -->
					<?php
					if ( $manage_stock ) {
						echo '<div class="tour-stock-manage">';
						echo '<p>';
						echo '<span>' . $stock_qty . '</span>' . esc_attr__( 'ticket in stock', 'travel-booking' );
						echo '</p>';
						echo '</div>';
					}
					?>

					<!-- Ticket --->
					<div class="form-group">
						<?php
						$label_number_ticket = 'Number ticket';

						/*** Children ticket ***/
						if ( get_option( 'show_adults_children' ) && (float) $price_child > 0 ) {
							$label_number_ticket = 'Adult';

							$tour_number_children_ticket = 0;

							if ( (int) $tour_min_number_children_per_booking > 0 ) {
								$tour_number_children_ticket = $tour_min_number_children_per_booking;
							} else {
								$tour_min_number_children_per_booking = 0;
							}

							$total_price_tour_phys += $tour_number_children_ticket * $price_child;
							?>
							<div class="item-field-tour-booking">
								<div class="input-number-ticket">
									<input type="number" name="number_children"
										   value="<?php echo $tour_number_children_ticket; ?>"
										   min="<?php echo $tour_min_number_children_per_booking; ?>"
										   max="<?php echo (int) $tour_max_number_children_per_booking > 0 ? $tour_max_number_children_per_booking : ''; ?>"
										   class="<?php echo $class_field_calculate; ?>">
									<span class="label"><?php echo __( 'Children', 'travel-booking' ); ?></span>
									&nbsp;&times;
									<?php echo TravelPhysUtility::tour_format_price( $price_child, 'price_child' ); ?>
								</div>
								<input type="hidden" name="price_children" value="<?php echo $price_child; ?>">
							</div>
							<?php
						}
						?>

						<!-- Adult ticket -->
						<div class="item-field-tour-booking">
							<div class="input-number-ticket">
								<input type="number" name="number_ticket"
									   value="<?php echo $tour_number_ticket; ?>"
									   min="<?php echo $tour_min_number_ticket_per_booking; ?>"
									   max="<?php echo (int) $tour_max_number_ticket_per_booking > 0 ? $tour_max_number_ticket_per_booking : ''; ?>"
									   class="<?php echo $class_field_calculate; ?>">
								&nbsp;<span
										class="label"><?php echo __( $label_number_ticket, 'travel-booking' ); ?></span>
								&nbsp;&times;&nbsp;<?php echo TravelPhysUtility::tour_format_price( $product->get_price(), 'price_ticket' ); ?>
							</div>
						</div>
					</div>

					<?php
					// Group discount
					if ( $tour_group_discount_enable && $tour_group_discount_enable == 1 ) {
						if ( $tour_group_text_form_booking !== '' ) {
							echo '<p class="text-discount">' . $tour_group_text_form_booking . '</p>';
						}
						if ( $tour_group_discount_data !== '' ) {
							echo '<div class="form-group label-tour-group-discount">' . __( 'Group discount', 'travel-booking' ) . '&nbsp;' . TravelPhysUtility::tour_format_price( 0, 'val-discount' ) . '&nbsp;(<span class="total-people"></span>&nbsp;' . __( 'People', 'travel-booking' ) . ')' . '</div>';
							echo '<input type="hidden" name="tour_group_discount_data_phys" value="' . htmlspecialchars( $tour_group_discount_data ) . '">';
						}
					}
					?>

					<!-- Hook add more element before total price -->
					<?php do_action( 'tmpl_element_before_total_price_tour_booking_form' ); ?>

					<!-- Total -->
					<div class="total_price_arrow">
						<div class="form-field">
							<span><?php echo __( 'Total', 'travel-booking' ); ?> = </span>
							<?php echo TravelPhysUtility::tour_format_price( $total_price_tour_phys, 'tour-subtotal' ); ?>
						</div>
					</div>

					<input type="hidden" name="tour_id" value="<?php echo get_the_ID(); ?>">
					<input type="hidden" name="nonce"
						   value="<?php echo wp_create_nonce( 'tb_booking_nonce_action' ); ?>">
					<div class="spinner">
						<div class="rect1"></div>
						<div class="rect2"></div>
						<div class="rect3"></div>
						<div class="rect4"></div>
						<div class="rect5"></div>
					</div>
					<input class="btn-booking btn" value="<?php _e( 'Booking now', 'travel-booking' ); ?>" type="submit">
					<input type="hidden" name="price_ticket" value="<?php echo $product->get_price(); ?>">
					<input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
					<input type="hidden" name="end_date" value="<?php echo $end_date; ?>">
					<input type="hidden" name="tour_days" value="<?php echo htmlspecialchars( $tour_days ); ?>">
					<input type="hidden" name="day_book" value="">
					<input type="hidden" name="woocommerce_price_num_decimals"
						value="<?php echo $woocommerce_price_num_decimals; ?>">
					<input type="hidden" name="url_home" value="<?php echo get_site_url() . '/'; ?>">
					<input type="hidden" name="tour_date_starts_after" value="<?php echo $phys_starts_after; ?>">
					<input type="hidden" name="tour_duration_number" value="<?php echo $phys_tour_duration_number; ?>">
					<input type="hidden" name="tour_month"
						value="<?php echo implode( ',', TravelPhysUtility::$month_arr ); ?>">
					<?php
					if ( $_page_redirect_after_tour_booking != '' ) {
						echo '<input type="hidden" name="checkout_url" value="' . get_page_link( $_page_redirect_after_tour_booking ) . '">';
					} else {
						echo '<input type="hidden" name="checkout_url" value="' . get_the_permalink( $checkout_page_id ) . '">';
					}
					?>
				</form>
			</div>
			<?php
		} else {
			echo '<p>' . esc_attr__( 'Tour is Out of stock', 'travel-booking' ) . '</p>';
		}
		?>

	</div>
	<?php
}
?>
