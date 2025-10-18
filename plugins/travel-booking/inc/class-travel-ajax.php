<?php
/**
 * Class TravelPhysAjax
 * @version 1.2.5
 * @author  physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TravelPhysAjax {
	public static function init() {
		if ( is_admin() ) {
			// ajax backend
			add_action( 'wp_print_scripts', array( __CLASS__, 'tour_booking_phys_ajax_url' ), 11 );
		} else {
			// ajax frontend
			add_action( 'template_redirect', array( __CLASS__, 'do_tb_ajax' ), 11 );
			add_action( 'tb_ajax_add_tour_to_cart_phys', array( __CLASS__, 'add_tour_to_cart_phys' ) );
		}
	}

	public static function tour_booking_phys_ajax_url() {
		if ( is_plugin_active( 'polylang/polylang.php' ) ) {
			$optional     = 'slug';
			$current_lang = pll_current_language( $optional );

			echo '<script type="text/javascript">';
			echo 'var tb_phys_ajax_url = "' . get_site_url() . '/' . $current_lang . '/"';
			echo '</script>';
		} else {
			echo '<script type="text/javascript">
			var tb_phys_ajax_url ="' . get_site_url() . '/";
			</script>';
		}
	}

	public static function do_tb_ajax() {
		global $wp_query;
		if ( ! empty( $_GET['tb-ajax'] ) ) {
			$wp_query->set( 'tb-ajax', sanitize_text_field( $_GET['tb-ajax'] ) );
		}

		$action = $wp_query->get( 'tb-ajax' );
		if ( $action ) {
			do_action( 'tb_ajax_' . sanitize_text_field( $action ) );
			die();
		}
	}

	public static function add_tour_to_cart_phys() {
		$message = array(
			'status'  => 'error',
			'message' => '',
		);
		//  if ( isset( $_REQUEST['nonce'] ) && wp_verify_nonce( $_REQUEST['nonce'], 'tb_booking_nonce_action' ) ) {
		//$date_now = date( 'Y-m-d' );

		if ( ! isset( $_POST['tour_id'] ) ) {
			$message['message'] = 'invalid tour';
			wp_send_json( $message );
		}

		if ( ! wc_get_product( $_POST['tour_id'] ) ) {
			$message['message'] = 'invalid tour';
			wp_send_json( $message );
		}

		do_action( 'woocommerce_set_cart_cookies', true );

		$tour_id         = (int) $_POST['tour_id'];
		$cart_tour       = WC()->cart;
		$basket_item_key = $cart_tour->generate_cart_id( $tour_id );
		$tour            = wc_get_product( $tour_id );

		$cart_tour->cart_contents[ $basket_item_key ] = array(
			'product_id'   => $tour_id,
			'is_tour'      => true,
			'key'          => $basket_item_key,
			'variation_id' => 0,
			'variation'    => 0,
			'data'         => $tour,
		);

		$cart_tour->cart_contents[ $basket_item_key ]['tour_origin_price'] = $tour->get_price();

		// Dates book
		$show_date_book = apply_filters( 'tb_show_date_book', true );
		if ( $show_date_book ) {
			if ( isset( $_POST['date_booking'] ) ) {
				$date_format_config = get_option( 'date_format_tour', TravelPhysUtility::$_date_format_default );
				$duration_number    = (int) get_post_meta( $tour_id, '_phys_tour_duration_number', true ) - 1;
				$date_booking       = wc_clean( $_POST['date_booking'] );

				if ( $duration_number < 0 ) {
					$duration_number = 0;
				}

				$date_book_format_date_default = TravelPhysUtility::convert_date_to_format_default( $date_booking );
				$date_book_obj                 = new DateTime( $date_book_format_date_default );
				$date_end_obj                  = $date_book_obj->add( new DateInterval( 'P' . $duration_number . 'D' ) );

				$cart_tour->cart_contents[ $basket_item_key ]['date_booking']  = $date_booking;
				$cart_tour->cart_contents[ $basket_item_key ]['tour_date_end'] = $date_end_obj->format( $date_format_config );
			} else {
				if ( isset( $_POST['date_check_in'] ) ) {
					$cart_tour->cart_contents[ $basket_item_key ]['date_check_in'] = wc_clean( $_POST['date_check_in'] );
				} else {
					$message['message'] = 'invalid date check in';
					wp_send_json( $message );
				}

				if ( isset( $_POST['date_check_out'] ) ) {
					$cart_tour->cart_contents[ $basket_item_key ]['date_check_out'] = wc_clean( $_POST['date_check_out'] );
				} else {
					$message['message'] = 'invalid date check out';
					wp_send_json( $message );
				}
			}
		}
		$number_ticket = $_POST['number_ticket'] ?? 1;

		$number_ticket          = intval( $number_ticket );
		$tour_number_ticket_max = (int) get_post_meta( $tour_id, '_tour_max_number_ticket_per_booking', true );

		if ( $number_ticket > $tour_number_ticket_max && $tour_number_ticket_max > 0 ) {
			$number_ticket = $tour_number_ticket_max;
		}

		$cart_tour->cart_contents[ $basket_item_key ]['quantity'] = $number_ticket;

		if ( isset( $_POST['number_children'] ) ) {
			$number_children = max( (int) $_POST['number_children'], 0 );

			$cart_tour->cart_contents[ $basket_item_key ]['number_children'] = $number_children;

			$price_children = (float) TravelPhysTab::instance()->get_tour_meta( $tour_id, '_price_child' );

			$cart_tour->cart_contents[ $basket_item_key ]['price_children'] = $price_children;
		}

		// Variations
		if ( isset( $_POST['tour_variations'] ) ) {
			$tour_variations = json_decode( str_replace( '\\', '', wc_clean( $_POST['tour_variations'] ) ) );

			if ( json_last_error() == JSON_ERROR_NONE ) {
				$cart_tour->cart_contents[ $basket_item_key ]['tour_variations'] = $tour_variations;
			}
		}

		/*** Hook action add data cart tour ***/
		do_action( 'handle_add_tour_to_cart_set_data_phys', $cart_tour, $basket_item_key );

		$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $tour_id, $number_ticket );
		if ( ! $passed_validation ) {
			// notice_type is 'tour_phys_error', example wc_add_notice( $string, 'tour_phys_error' );
			$notices = wc_get_notices( 'tour_phys_error' );
			if ( ! empty( $notices ) ) {
				// get newest message
				$error_notice       = end( $notices );
				$message['message'] = $error_notice['notice'];
			}
			wp_send_json( $message );
		}
		// Save data cart
		//$cart_tour->calculate_totals();
		$cart_tour->set_session();

		$message['status'] = 'success';
		//  }
		wp_send_json( $message );
	}

	public static function notify_new_order() {
		$message = array( 'status' => 'error' );

		if ( isset( $_POST['limit'] ) ) {
			$limit = wc_clean( $_POST['limit'] );
		} else {
			$message['status'] = 'error';
			wp_send_json( $message );
		}

		$args = array(
			'status'      => 'wc-completed',
			'type'        => wc_get_order_types( 'view-orders' ),
			'parent'      => null,
			'customer'    => null,
			'email'       => '',
			'limit'       => $limit,
			'offset'      => null,
			'exclude'     => array(),
			'orderby'     => 'rand',
			//'order'       => 'DESC',
			'return'      => 'objects',
			'paginate'    => false,
			'date_before' => '',
			'date_after'  => '',
		);

		$orders_tour = wc_get_orders( $args );

		$html = '';
		if ( count( $orders_tour ) > 0 ) {
			foreach ( $orders_tour as $order_tour ) {
				$items    = $order_tour->get_items();
				$key_item = array_keys( $items );
				$rand     = 0;
				if ( count( $key_item ) > 1 ) {
					$rand = rand( 0, count( $key_item ) - 1 );
				}
				$product = $items[ $key_item[ $rand ] ];
				$html   .= '<div class="item-order"><div class="inner-content"><a href="' . get_permalink( $product->get_product_id() ) . '">';
				$html   .= '<img src="' . get_the_post_thumbnail_url( $product->get_product_id(), 'thumbnail' ) . '" /></a>';
				$html   .= '<span>' . esc_html__( 'Someone in ', 'travel-booking' ) . $order_tour->get_billing_address_1() . ', ' . $order_tour->get_billing_city() . ', ' . $order_tour->get_billing_country() . esc_html__( ' purchased a ', 'travel-booking' ) . '</span>';
				$html   .= '<a href="' . get_permalink( $product->get_product_id() ) . '" class="title">' . $product->get_name() . '</a>';
				$html   .= '<span class="date">' . caculate_about_time( $order_tour->get_date_created() ) . '</span>';
				$html   .= '</div> </div>';
			}

			$message['status'] = 'success';
			$message['html']   = $html;
		}

		wp_send_json( $message );
	}
}

TravelPhysAjax::init();
