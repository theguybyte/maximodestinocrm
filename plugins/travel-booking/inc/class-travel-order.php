<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Travel Order
 *
 * @author  physcode
 * @version 1.2.8
 */
class TravelPhysOrder {
	public static $_fields_tour_order = null;

	public static function init() {
		add_filter( 'woocommerce_hidden_order_itemmeta', array( __CLASS__, 'remove_order_item_meta_fields_backend' ) );
		add_action( 'woocommerce_before_order_itemmeta', array( __CLASS__, 'show_booking_date_order_phys_backend' ), 11, 3 );

		// order-details-item.php && email-order-items.php
		add_filter( 'woocommerce_order_item_quantity_html', array( __CLASS__, 'html_order_tour_item_info_remove_quantity_default' ), 11, 2 );
		add_action( 'woocommerce_order_item_meta_end', array( __CLASS__, 'html_order_tour_item_info' ), 12, 2 );

		/*** Personal Information ***/
		add_action( 'woocommerce_admin_order_data_after_order_details', array( __CLASS__, 'personal_information_order_backend' ), 11, 1 );
	}

	public static function setup_fields_tour_order() {
		if (self::$_fields_tour_order === null) {
			self::$_fields_tour_order = apply_filters(
				'fields_tour_order_phys',
				array(
					'_is_tour',
					'_date_booking',
					'_tour_date_end',
					'_date_check_in',
					'_date_check_out',
					'_tour_variations',
					'_tour_variations_options',
					'_price_dates_tour',
					'_tour_group_discount',
					'_number_children',
					'_price_children',
					'_price_adults'
				)
			);
		}
	}

	public static function get_fields_tour_order() {
		self::setup_fields_tour_order();
		return self::$_fields_tour_order;
	}

	/**
	 * @param $fields
	 *
	 * @return array
	 */
	public static function remove_order_item_meta_fields_backend( $fields ) {
		$fields = array_merge( $fields, self::get_fields_tour_order() );

		return $fields;
	}

	/**
	 * @param int           $item_id
	 * @param WC_Order_Item $item
	 * @param WC_Product    $_product
	 *
	 * @throws Exception
	 */
	public static function show_booking_date_order_phys_backend( $item_id, $item, $_product ) {
		$html = '';

		self::html_order_tour_item_info( $item_id, $item );
	}

	public static function personal_information_order_backend( $order ) {

		$travel_personal_information_option = get_post_meta( $order->get_id(), 'travel_personal_information_option', true );

		if ( $travel_personal_information_option != '' ) {
			$order_items = $order->get_items();
			?>
			<div class="clear"></div>

			<div class="wrapper-personal-information-order">
				<h3><?php echo __( 'Personal Information', 'travel-booking' ); ?></h3>
				<?php
				$count = 1;
				foreach ( $order_items as $item ) {
					$tour      = $item->get_data();
					$tour_id   = $tour['product_id'];
					$qty_adult = $tour['quantity'];
					$key       = $tour_id . '_';
					$is_tour   = wc_get_order_item_meta( $item->get_id(), '_is_tour', true );
					$qty_child = (int) wc_get_order_item_meta( $item->get_id(), '_number_children', true );

					if ( $is_tour && $is_tour != '' ) {
						echo '<h5>' . $tour['name'] . '</h5>';

						$travel_personal_information_option = get_post_meta( $order->get_id(), 'travel_personal_information_option', true );

						if ( $travel_personal_information_option != '' ) {
							$travel_personal_information_option_obj = json_decode( $travel_personal_information_option );
							echo '<div class="personal-infomation-item">';
							for ( $i = 1; $i <= $qty_adult; $i ++ ) {
								echo '<p><span><strong>' . __( 'Adult', 'travel-booking' ) . ' ' . $i . '</strong></span>:</p>';

								foreach ( $travel_personal_information_option_obj as $k => $info ) {
									$key_field = $key . $k . '_adult_' . $i;

									if ( $info->enable ) {
										$value = get_post_meta( $order->get_id(), $key_field, true );

										echo '<p>';
										echo '<span class="label col-sm-3">' . __( $info->label, 'travel-booking' ) . ':&nbsp;' . '</span>';
										if ( $info->type == 'text' ) {
											echo '<span>' . $value . '</span>';
										} elseif ( $info->type == 'select' ) {
											echo '<span>' . $value . '</span>';
										} elseif ( $info->type == 'radio' ) {
											echo '<span>' . $value . '</span>';
										} elseif ( $info->type == 'checkbox' ) {
											echo '<span>' . $value . '</span>';
										}
										echo '</p>';
									}
								}
							}

							for ( $i = 1; $i <= $qty_child; $i ++ ) {
								echo '<p><span><strong>' . __( 'Child', 'travel-booking' ) . ' ' . $i . '</strong></span>:</p>';

								foreach ( $travel_personal_information_option_obj as $k => $info ) {
									$key_field = $key . $k . '_child_' . $i;

									if ( $info->enable ) {
										$value = get_post_meta( $order->get_id(), $key_field, true );

										echo '<p>';
										echo '<span class="label col-sm-3">' . __( $info->label, 'travel-booking' ) . ':&nbsp;' . '</span>';
										if ( $info->type == 'text' ) {
											echo '<span>' . $value . '</span>';
										} elseif ( $info->type == 'select' ) {
											echo '<span>' . $value . '</span>';
										} elseif ( $info->type == 'radio' ) {
											echo '<span>' . $value . '</span>';
										} elseif ( $info->type == 'checkbox' ) {
											echo '<span>' . $value . '</span>';
										}
										echo '</p>';
									}
								}
							}
							echo '</div > ';
						}
					}
					?>
					<?php
					$count ++;
				}
				?>
			</div>
			<?php
		}
	}

	/**
	 * @param int                   $item_id
	 * @param WC_Order_Item_Product $item
	 *
	 * @return string
	 * @throws Exception
	 */
	public static function html_order_tour_item_info( $item_id, $item ) {
		$order   = $item->get_order();
		$item_id = $item->get_id();
		$is_tour = $item->get_meta( '_is_tour', true );

		$html = '';

		if ( $is_tour ) {
			$date_booking    = $tour_date_end = $date_check_in = $date_check_out = $price_adults = null;
			$number_children = $price_children = $tour_variations = $tour_variations_options = $tour_group_discount = null;

			foreach ( TravelPhysOrder::get_fields_tour_order() as $field ) {
				$variable_field    = preg_replace( '/^\_/', '', $field );
				${$variable_field} = $item->get_meta( $field, true );
			}

			$currency     = $order->get_currency();
			$format_price = array( 'currency' => $currency );

			if ( $date_booking && $tour_date_end ) {
				$html .= apply_filters( 'html_order_item_check_in_cart', '<div><strong>' . __( 'Check in', 'travel-booking' ) . '</strong>: ' . $date_booking . '</div>', $date_booking );
				$html .= apply_filters( 'html_order_item_check_out_cart', '<div><strong>' . __( 'Check out', 'travel-booking' ) . '</strong>: ' . $tour_date_end . '</div>', $tour_date_end );
			}

			if ( $date_check_in && $date_check_out ) {
				$html .= apply_filters( 'html_order_item_check_in_cart', '<div class="cart-item-date-check-in"><strong>' . __( 'Check in', 'travel-booking' ) . '</strong>: ' . $date_check_in . '</div>', $date_check_in );
				$html .= apply_filters( 'html_order_item_check_out_cart', '<div class="cart-item-date-check-out"><strong>' . __( 'Check out', 'travel-booking' ) . '</strong>: ' . $date_check_out . '</div>', $date_check_out );
			}

			$html = apply_filters( 'html_order_check_in_out_after', $html, $item, $item_id );

			if ( $number_children ) {
				$html .= '<div><strong>' . __( 'Adults', 'travel-booking' ) . '</strong>&nbsp;(' . wc_price( $price_adults, $format_price ) . ')&nbsp;&times;&nbsp;' . $item->get_quantity() . '</div>';
				$html .= '<div><strong>' . __( 'Children', 'travel-booking' ) . '</strong>&nbsp;(' . wc_price( $price_children, $format_price ) . ')&nbsp;&times;&nbsp;' . $number_children . '</div>';
			} else {
				$html .= apply_filters( 'html_order_item_ticket', '<div><strong>' . __( 'Ticket', 'travel-booking' ) . '</strong>(' . wc_price( $price_adults, $format_price ) . ')' . '&nbsp;&times;&nbsp;' . $item->get_quantity() . '</div>', $item['qty'] );
			}

			/*** Variation ***/
			if ( isset( $tour_variations ) && isset( $tour_variations_options ) && isset( $price_dates_tour ) ) {
				$html .= TravelPhysVariation::view_variation_detail( $tour_variations, $tour_variations_options, $price_dates_tour, $currency );
			}
			//
			/*** Group discount ***/
			if ( $tour_group_discount ) {
				$html .= apply_filters( 'html_tour_group_discount', $tour_group_discount );
			}

			do_action( 'html_order_item_tour_phys', $html, $item, $item_id );
		}

		echo $html;
	}

	public static function html_order_tour_item_info_remove_quantity_default( $html, $item ) {
		$is_tour = $item->get_meta( '_is_tour', true );

		return $is_tour ? $html = '' : $html;
	}
}

TravelPhysOrder::init();
