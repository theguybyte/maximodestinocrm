<?php
/**
 * TravelPhysCart
 *
 * @author  Physcode
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TravelPhysCart {
	private static $instance;

	protected function __construct() {
		add_filter( 'woocommerce_cart_item_name', array( $this, 'html_cart_tour' ), 12, 3 );
		add_filter( 'woocommerce_cart_item_price', array( __CLASS__, 'html_cart_tour_price' ), 12, 3 );
		add_filter( 'woocommerce_cart_item_quantity', array( __CLASS__, 'html_cart_tour_quantity' ), 12, 3 );
		add_filter( 'woocommerce_cart_item_subtotal', array( $this, 'update_subtotal' ), 10, 2 );
		add_action( 'woocommerce_after_calculate_totals', array( $this, 'update_price_for_tour' ), 11 );
	}

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function html_cart_tour( $html_item_name, $cart_item, $cart_item_key ) {
		// Check is cart page
		if ( get_the_ID() == get_option( 'woocommerce_cart_page_id' ) ) {
			if ( array_key_exists( 'date_booking', $cart_item ) && array_key_exists( 'tour_date_end', $cart_item ) ) {
				$date_obj_check_in  = $cart_item['date_booking'];
				$date_obj_check_out = $cart_item['tour_date_end'];

				$html_item_name .= apply_filters( 'html_check_in_cart', '<div class="cart-item-date-check-in"><strong>' . __( 'Check in', 'travel-booking' ) . '</strong>: ' . $date_obj_check_in . '</div>', $date_obj_check_in );
				$html_item_name .= apply_filters( 'html_check_out_cart', '<div class="cart-item-date-check-out"><strong>' . __( 'Check out', 'travel-booking' ) . '</strong>: ' . $date_obj_check_out . '</div>', $date_obj_check_out );
			}

			if ( array_key_exists( 'date_check_in', $cart_item ) && array_key_exists( 'date_check_out', $cart_item ) ) {
				$date_obj_check_in  = $cart_item['date_check_in'];
				$date_obj_check_out = $cart_item['date_check_out'];

				$html_item_name .= apply_filters( 'html_check_in_cart', '<div class="cart-item-date-check-in"><strong>' . __( 'Check in', 'travel-booking' ) . '</strong>: ' . $date_obj_check_in . '</div>', $date_obj_check_in );
				$html_item_name .= apply_filters( 'html_check_out_cart', '<div class="cart-item-date-check-out"><strong>' . __( 'Check out', 'travel-booking' ) . '</strong>: ' . $date_obj_check_out . '</div>', $date_obj_check_out );
			}

			/*** Variation ***/
			if ( isset( $cart_item['tour_variations'] ) ) {
				$tour_variations         = $cart_item['tour_variations'];
				$tour_variations_options = $cart_item['tour_variations_options'];
				$price_dates_tour        = $cart_item['price_dates_tour'];

				$html_item_name .= TravelPhysVariation::view_variation_detail( $tour_variations, $tour_variations_options, $price_dates_tour );
			}

			/*** Group discount ***/
			if ( array_key_exists( 'tour_group_discount', $cart_item ) ) {
				$html_item_name .= apply_filters( 'html_tour_group_discount', $cart_item['tour_group_discount'] );
			}
		}

		return $html_item_name;
	}

	/**
	 * @param string $html_item_price
	 * @param array $cart_item
	 * @param string $cart_item_key
	 *
	 * @return mixed|void
	 */
	public static function html_cart_tour_price( $html_item_price, $cart_item, $cart_item_key ) {
		if ( array_key_exists( 'number_children', $cart_item ) && array_key_exists( 'price_children', $cart_item ) ) {
			$price_ticket = wc_price( $cart_item['data']->get_price() );
			$price_child  = wc_price( $cart_item['price_children'] );

			$tour_item_price_content  = '<p class="tour-price-item-cart"><span>' . __( 'Adult', 'travel-booking' ) . '</span>:&nbsp;' . $price_ticket . '</p>';
			$tour_item_price_content .= '<p class="tour-price-item-cart"><span>' . __( 'Child', 'travel-booking' ) . '</span>:&nbsp;' . $price_child . '</p>';

			$html_item_price = apply_filters( 'html_tour_item_price', $tour_item_price_content );
		}

		return $html_item_price;
	}

	public static function html_cart_tour_quantity( $tour_quantity, $cart_item_key, $cart_item ) {
		$class = 'input-quantity-tour-item';

		if ( isset( $cart_item['number_children'] ) ) {
			$number_children = $cart_item['number_children'];
			$name_input      = 'cart[' . $cart_item_key . '][number_children]';

			$tour_quantity_content = '<p class="tour-price-item-cart"><input type="number" class="' . $class . '" name="' . $name_input . '" value="' . $number_children . '"></p>';

			$tour_quantity .= apply_filters( 'html_tour_quantity_children', $tour_quantity_content );
		}

		return $tour_quantity;
	}

	/**
	 * Show price subtotal for tour
	 *
	 * @param $value
	 * @param $cart_item
	 *
	 * @return string
	 */
	public function update_subtotal( $value, $cart_item ) {
		if ( ! empty( $cart_item['is_tour'] ) ) {
			$value = wc_price( $cart_item['line_subtotal'] );
		}

		return $value;
	}

	public function update_price_for_tour( $cart_object ) {
		new TravelPhysCartTotal( $cart_object );
	}
}

TravelPhysCart::getInstance();
