<?php
/**
 * Class TravelPhysCheckout
 *
 * @version 1.0.1
 * @author  physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TravelPhysCheckout {

	public static function init() {
		// review-order.php
		add_filter( 'woocommerce_checkout_cart_item_quantity', array( __CLASS__, 'html_review_order_tour' ), 10, 3 );

		/*** Personal Information */
		if ( get_option( Tour_Settings_Tab_Phys::$_personal_information_enable ) == 1 ) {
			add_filter( 'woocommerce_checkout_fields', array( __CLASS__, 'personal_information_fields' ), 11, 1 );
			add_action(
				'woocommerce_checkout_before_customer_details',
				array(
					__CLASS__,
					'personal_information',
				),
				11
			);
		}
		add_action( 'woocommerce_store_api_checkout_order_processed', array( __CLASS__, 'add_order_item_meta_store_api' ) );
		add_action( 'woocommerce_checkout_order_processed', array( __CLASS__, 'add_order_item_meta_classic' ), 10, 4 );
	}
	/**
	 * Use for classic checkout page
	 *
	 * @param  integer $order_id Woocommerce order id
	 */
	public static function add_order_item_meta_classic( $order_id ) {
		self::add_order_item_meta_data( $order_id );
	}
	/**
	 * Use for blocks checkout page
	 *
	 * @param  WC_Order $order woocommerce order
	 */
	public static function add_order_item_meta_store_api( $order ) {
		$order_id = $order->get_id();
		self::add_order_item_meta_data( $order_id );
	}
	/**
	 * save tour order item meta data
	 * @param integer $order_id wc order id
	 */
	public static function add_order_item_meta_data( $order_id ) {
		$order       = wc_get_order( $order_id );
		$cart_items  = WC()->cart->get_cart();
		$order_items = $order->get_items();
		if ( empty( $cart_items ) || empty( $order_items ) ) {
			return;
		}
		foreach ( $order_items as $item ) {
			if ( $item->get_product()->get_type() === TB_PHYS_PRODUCT_TYPE ) {
				$product_id    = $item->get_product_id();
				$cart_item_key = WC()->cart->generate_cart_id( $product_id );
				if ( ! empty( $cart_items[ $cart_item_key ] ) ) {
					$cart_item   = $cart_items[ $cart_item_key ];
					$tour_fields = TravelPhysOrder::get_fields_tour_order();
					foreach ( $tour_fields as $field ) {
						$key_cart_item = preg_replace( '/^\_/', '', $field );
						if ( $field === '_price_adults' ) {
							$item->update_meta_data( '_price_adults', $cart_item['data']->get_price(), true );
						} elseif ( isset( $cart_item[ $key_cart_item ] ) ) {
							$item->update_meta_data( $field, $cart_item[ $key_cart_item ], true );
						}
					}
					$item->save();
					$travel_personal_information_option = get_option( Tour_Settings_Tab_Phys::$_personal_information );
					if ( get_option( Tour_Settings_Tab_Phys::$_personal_information_enable ) == 1 && ! empty( $travel_personal_information_option ) ) {
						self::save_order_personal_information( $cart_item, $order, $travel_personal_information_option );
					}
				}
			}
		}
	}

	public static function html_review_order_tour( $html_item_name, $cart_item, $cart_item_key ) {
		if ( isset( $cart_item['is_tour'] ) ) {

			$html_item_name = '';

			if ( isset( $cart_item['date_booking'] ) && isset( $cart_item['tour_date_end'] ) ) {
				$date_check_in  = $cart_item['date_booking'];
				$date_check_out = $cart_item['tour_date_end'];

				$html_item_name .= apply_filters( 'html_check_in_cart', '<div class="cart-item-date-check-in"><strong>' . __( 'Check in', 'travel-booking' ) . '</strong>: ' . $date_check_in . '</div>', $date_check_in );
				$html_item_name .= apply_filters( 'html_check_out_cart', '<div class="cart-item-date-check-out"><strong>' . __( 'Check out', 'travel-booking' ) . '</strong>: ' . $date_check_out . '</div>', $date_check_out );
			}

			if ( isset( $cart_item['date_check_in'] ) && isset( $cart_item['date_check_out'] ) ) {
				$date_check_in  = $cart_item['date_check_in'];
				$date_check_out = $cart_item['date_check_out'];

				$html_item_name .= apply_filters( 'html_check_in_cart', '<div class="cart-item-date-check-in"><strong>' . __( 'Check in', 'travel-booking' ) . '</strong>: ' . $date_check_in . '</div>', $date_check_in );
				$html_item_name .= apply_filters( 'html_check_out_cart', '<div class="cart-item-date-check-out"><strong>' . __( 'Check out', 'travel-booking' ) . '</strong>: ' . $date_check_out . '</div>', $date_check_out );
			}

			$html_item_name = apply_filters( 'html_checkout_check_in_out_after', $html_item_name, $cart_item, $cart_item_key );

			if ( isset( $cart_item['number_children'] ) ) {
				$html_item_name .= '<div class="cart-item-number-adult"><strong>' . __( 'Adults', 'travel-booking' ) . '</strong>&nbsp;<span>(' . $cart_item['data']->get_price_html() . ')</span> &times; <span>' . $cart_item['quantity'] . '</span></div>';
				$html_item_name .= '<div class="cart-item-number-child"><strong>' . __( 'Children', 'travel-booking' ) . '</strong>&nbsp;<span>(' . TravelPhysUtility::tour_format_price( $cart_item['price_children'] ) . ')</span> &times; <span>' . $cart_item['number_children'] . '</span></div>';
			} else {
				$html_item_name .= '<div class="cart-item-number-ticket"><strong>' . __( 'Tickets', 'travel-booking' ) . '</strong>&nbsp;<span>(' . $cart_item['data']->get_price_html() . ')</span> &times; <span>' . $cart_item['quantity'] . '</span></div>';
			}

			/*** Variation */
			if ( isset( $cart_item['tour_variations'] ) ) {
				$tour_variations         = $cart_item['tour_variations'];
				$tour_variations_options = $cart_item['tour_variations_options'];
				$price_dates_tour        = $cart_item['price_dates_tour'];

				$html_item_name .= TravelPhysVariation::view_variation_detail( $tour_variations, $tour_variations_options, $price_dates_tour );
			}

			/*** Group discount */
			if ( isset( $cart_item['tour_group_discount'] ) ) {
				$html_item_name .= apply_filters( 'html_tour_group_discount', $cart_item['tour_group_discount'] );
			}

			do_action( 'html_checkout_review_order_tour_phys', $html_item_name, $cart_item, $cart_item_key );
		}

		return $html_item_name;
	}

	public static function personal_information() {
		tb_get_file_template( 'checkout/personal-information.php' );
	}

	public static function personal_information_fields( $fields ) {
		$travel_personal_information_option = get_option( Tour_Settings_Tab_Phys::$_personal_information );

		if ( $travel_personal_information_option !== '' ) {
			$travel_personal_information_option_obj = json_decode( $travel_personal_information_option );

			if ( ! is_null( $travel_personal_information_option_obj ) && count( (array) $travel_personal_information_option_obj ) > 0 ) {
				$cart = WC()->cart->get_cart();

				// var_dump( $travel_personal_information_option_obj );

				$fields['personal_information'] = array();

				foreach ( $cart as $cart_item ) {
					$tour      = $cart_item['data'];
					$qty_adult = (int) $cart_item['quantity'];
					$key       = $tour->get_id() . '_';

					if ( array_key_exists( 'is_tour', $cart_item ) ) {
						for ( $i = 1; $i <= $qty_adult; $i++ ) {
							foreach ( $travel_personal_information_option_obj as $k_field => $v_field ) {
								if ( $v_field->enable ) {
									$key_field                                    = $key . $k_field . '_adult_' . $i;
									$fields['personal_information'][ $key_field ] = array(
										'type'     => $v_field->type,
										'label'    => __( $v_field->label, 'travel-booking' ),
										'required' => $v_field->required,
									);
								}
							}
						}

						if ( array_key_exists( 'number_children', $cart_item ) ) {
							$qty_children = (int) $cart_item['number_children'];

							for ( $i = 1; $i <= $qty_children; $i++ ) {
								foreach ( $travel_personal_information_option_obj as $k_field => $v_field ) {
									if ( $v_field->enable ) {
										$key_field                                    = $key . $k_field . '_child_' . $i;
										$fields['personal_information'][ $key_field ] = array(
											'type'     => $v_field->type,
											'label'    => __( $v_field->label, 'travel-booking' ),
											'required' => $v_field->required,
										);
									}
								}
							}
						}
					}
				}
			}
		}

		// var_dump( $fields );

		return $fields;
	}

	public static function save_order_personal_information( $cart_item, $order, $travel_personal_information_option ) {
		$tour      = $cart_item['data'];
		$qty_adult = (int) $cart_item['quantity'];
		$key       = $tour->get_id() . '_';
		$order_id  = $order->get_id();

		$travel_personal_information_option_obj = json_decode( $travel_personal_information_option );
		for ( $i = 1; $i <= $qty_adult; $i++ ) {
			foreach ( $travel_personal_information_option_obj as $k_field => $v_field ) {
				if ( $v_field->enable ) {
					$key_field = $key . $k_field . '_adult_' . $i;

					if ( ! empty( $_POST[ $key_field ] ) ) {
						$value = wc_clean( $_POST[ $key_field ] );
						if ( is_array( $value ) ) {
							$value = implode( ',', $value );
						}
						update_post_meta( $order_id, $key_field, $value );
					}
				}
			}
		}

		if ( ! empty( $cart_item['number_children'] ) ) {
			$qty_children = (int) $cart_item['number_children'];

			for ( $i = 1; $i <= $qty_children; $i++ ) {
				foreach ( $travel_personal_information_option_obj as $k_field => $v_field ) {
					if ( $v_field->enable ) {
						$key_field = $key . $k_field . '_child_' . $i;

						if ( ! empty( $_POST[ $key_field ] ) ) {
							$value = wc_clean( $_POST[ $key_field ] );
							if ( is_array( $value ) ) {
								$value = implode( ',', $value );
							}
							update_post_meta( $order_id, $key_field, $value );
						}
					}
				}
			}
		}
		update_post_meta( $order->get_id(), 'travel_personal_information_option', sanitize_text_field( $_POST['travel_personal_information_option'] ) );
	}
}

TravelPhysCheckout::init();
