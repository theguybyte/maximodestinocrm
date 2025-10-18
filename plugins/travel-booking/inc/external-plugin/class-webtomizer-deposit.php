<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * TravelPhysWebtomizerDeposit
 *
 * @author   physcode
 * @link     https://codecanyon.net/item/woocommerce-deposits-partial-payments-plugin/9249233
 * @version  1.0.0
 */
class TravelPhysWebtomizerDeposit {
	protected static $instance;

	private function __construct() {
		add_action( 'tmpl_element_before_total_price_tour_booking_form', array( $this, 'tmpl_deposit' ) );
		add_action( 'handle_add_tour_to_cart_set_data_phys', array( $this, 'add_data_to_cart_tour' ), 10, 2 );
		add_filter( 'wc_deposits_cart_item_deposit_data', array( $this, 'calculate_cart_item_deposit' ), 10, 2 );
		//add_filter( 'woocommerce_deposits_cart_deposit_amount', array( $this, 'calculate_deposit' ), 10, 2 );
	}

	public function tmpl_deposit() {
		tb_get_file_template( 'single-tour' . DIRECTORY_SEPARATOR . 'webtomizer-deposit.php' );
	}

	/**
	 * @return array
	 */
	public static function getOptionDepoist() {
		$option = array(
			'webtomizer_deposit_disable'         => get_option( 'wc_deposits_site_wide_disable', 'no' ),
			'webtomizer_deposit_checkout_enable' => get_option( 'wc_deposits_checkout_mode_enabled', 'no' ),
			'webtomizer_deposit_text'            => get_option( 'wc_deposits_button_deposit' ),
			'webtomizer_full_text'               => get_option( 'wc_deposits_button_full_amount' ),
			'webtomizer_deposit_option_text'     => get_option( 'wc_deposits_deposit_option_text' ),
		);

		return $option;
	}

	/**
	 * @param int $tour_id
	 *
	 * @return array
	 */
	public static function getOptionTourDeposit( $tour_id = 0 ) {
		$option = array(
			'webtomizer_tour_deposit_enable' => get_post_meta( $tour_id, '_wc_deposits_enable_deposit', true ),
			'webtomizer_tour_deposit_type'   => get_post_meta( $tour_id, '_wc_deposits_amount_type', true ),
			'webtomizer_tour_deposit_amount' => get_post_meta( $tour_id, '_wc_deposits_deposit_amount', true ),
			'webtomizer_tour_deposit_force'  => get_post_meta( $tour_id, '_wc_deposits_force_deposit', true ),
		);

		return $option;
	}

	public function add_data_to_cart_tour( $cart_tour, $cart_item_key ) {
		// Deposit
		if ( is_plugin_active( 'woocommerce-deposits/woocommerce-deposits.php' ) ) {
			$tour_id = $cart_tour->cart_contents[ $cart_item_key ]['product_id'];

			// initialize
			$webtomizer_deposit_disable     = $webtomizer_deposit_checkout_enable = '';
			$webtomizer_tour_deposit_enable = $webtomizer_tour_deposit_force = '';

			extract( TravelPhysWebtomizerDeposit::getOptionDepoist() );
			extract( TravelPhysWebtomizerDeposit::getOptionTourDeposit( $tour_id ) );

			if ( $webtomizer_deposit_disable == 'no' && $webtomizer_deposit_checkout_enable == 'no' && $webtomizer_tour_deposit_enable == 'yes' ) {
				if ( $webtomizer_tour_deposit_force == 'yes' ||
					( isset( $_POST['webtomizer_tour_deposit'] ) && $_POST['webtomizer_tour_deposit'] == 'deposit' ) ) {

					$cart_tour->cart_contents[ $cart_item_key ]['line_subtotal']           = $cart_tour->cart_contents[ $cart_item_key ]['line_total'];
					$cart_tour->cart_contents[ $cart_item_key ]['line_subtotal_tax']       = $cart_tour->cart_contents[ $cart_item_key ]['line_total_tax'];
					$cart_tour->cart_contents[ $cart_item_key ]['deposit']                 = array();
					$cart_tour->cart_contents[ $cart_item_key ]['deposit']['enable']       = 'yes';
					$cart_tour->cart_contents[ $cart_item_key ]['webtomizer_tour_deposit'] = 'yes';
				}
			}
		}
	}

	public function calculate_cart_item_deposit( $cart_item_data_deposit, $cart_item ) {
		if ( ( isset( $cart_item['date_booking'] ) ||
				( isset( $cart_item['date_check_in'] ) && isset( $cart_item['date_check_out'] ) ) )
			&& isset( $cart_item['deposit'] ) && isset( $cart_item['webtomizer_tour_deposit'] ) ) {

			$tour_id                        = $cart_item['data']->get_id();
			$webtomizer_tour_deposit_type   = '';
			$webtomizer_tour_deposit_amount = '';

			extract( TravelPhysWebtomizerDeposit::getOptionTourDeposit( $tour_id ) );

			if ( $webtomizer_tour_deposit_type == 'percent' ) {
				try {
					$subtotal = wc_remove_number_precision( TravelPhysCalculate::get_subtotal_item_tour( $cart_item ) );

					$cart_item_data_deposit['enable']    = 'yes';
					$cart_item_data_deposit['deposit']   = $deposit = $subtotal * $webtomizer_tour_deposit_amount / 100;
					$cart_item_data_deposit['remaining'] = $subtotal - $deposit;
					$cart_item_data_deposit['total']     = $subtotal;
				} catch ( Exception $e ) {
				}
			}
		}

		return $cart_item_data_deposit;
	}

	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
}

TravelPhysWebtomizerDeposit::getInstance();
