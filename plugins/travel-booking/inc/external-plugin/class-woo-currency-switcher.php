<?php
/**
 * Class TravelPhysWooCurrencySwitcher
 * @version 1.0.1
 * @author  physcode
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TravelPhysWooCurrencySwitcher {

	public static function init() {
		if ( is_plugin_active( 'woocommerce-currency-switcher/index.php' ) ) {
			// Convert price child
			add_filter( 'phys/travel/_price_child', array( __CLASS__, 'convert_child_price_with_currency' ), 11 );
			// set value convert
			add_filter(
				'woocommerce_product_get_price',
				array(
					__CLASS__,
					'convert_ticket_price_with_currency',
				),
				99999
			);
			// Fix when don't set multiple, will display not set value so we need set value for theme
			add_filter( 'raw_woocommerce_price', array( __CLASS__, 'fix_display_price_tour_apply_currency' ), 99999 );
			/**
			 * Convert price variations
			 *
			 * @see convert_variations_price_with_currency
			 */
			add_filter(
				'phys/travel/tour_variations_options_obj',
				array(
					__CLASS__,
					'convert_variations_price_with_currency',
				),
				11
			);
		}
	}

	/**
	 * Price of tour by currency
	 *
	 * @param $price
	 *
	 * @return string
	 */
	public static function fix_display_price_tour_apply_currency( $price ) {
		$woocs = $GLOBALS['WOOCS'];

		if ( ! $woocs->is_multiple_allowed ) {

			$currencies = $woocs->get_currencies();

			if ( in_array(
				$woocs->current_currency,
				$woocs->no_cents
			)/* OR $currencies[$this->current_currency]['hide_cents'] == 1 */ ) {
				$precision = 0;
			} else {
				if ( $woocs->current_currency != $woocs->default_currency ) {
					$precision = $woocs->get_currency_price_num_decimals(
						$woocs->current_currency,
						$woocs->price_num_decimals
					);
				} else {
					$precision = $woocs->get_currency_price_num_decimals(
						$woocs->default_currency,
						$woocs->price_num_decimals
					);
				}
			}

			if ( isset( $currencies[ $woocs->current_currency ] ) and $currencies[ $woocs->current_currency ] != null ) {
				$price = number_format(
					floatval( (float) $price / (float) $currencies[ $woocs->current_currency ]['rate'] ),
					$precision,
					$woocs->decimal_sep,
					''
				);
			} else {
				$price = number_format(
					floatval( (float) $price / (float) $currencies[ $woocs->default_currency ]['rate'] ),
					$precision,
					$woocs->decimal_sep,
					''
				);
			}
		}

		return $price;
	}

	/**
	 * Convert adult price
	 *
	 * @param $price
	 *
	 * @return string
	 */
	public static function convert_ticket_price_with_currency( $price ) {
		$woocs = $GLOBALS['WOOCS'];

		$currencies = $woocs->get_currencies();

		if ( ! $woocs->is_multiple_allowed ) {

			if ( in_array(
				$woocs->current_currency,
				$woocs->no_cents
			)/* OR $currencies[$this->current_currency]['hide_cents'] == 1 */ ) {
				$precision = 0;
			} else {
				if ( $woocs->current_currency != $woocs->default_currency ) {
					$precision = $woocs->get_currency_price_num_decimals(
						$woocs->current_currency,
						$woocs->price_num_decimals
					);
				} else {
					$precision = $woocs->get_currency_price_num_decimals(
						$woocs->default_currency,
						$woocs->price_num_decimals
					);
				}
			}

			if ( isset( $currencies[ $woocs->current_currency ] ) and $currencies[ $woocs->current_currency ] != null ) {
				$price = number_format(
					floatval( (float) $price * (float) $currencies[ $woocs->current_currency ]['rate'] ),
					$precision,
					$woocs->decimal_sep,
					''
				);
			} else {
				$price = number_format(
					floatval( (float) $price * (float) $currencies[ $woocs->default_currency ]['rate'] ),
					$precision,
					$woocs->decimal_sep,
					''
				);
			}
		}

		return $price;
	}

	/**
	 * Convert child price
	 *
	 * @param $price_child
	 *
	 * @return string
	 */
	public static function convert_child_price_with_currency( $price_child ) {
		$woocs = $GLOBALS['WOOCS'];

		//		if($woocs->is_multiple_allowed) {
		$currencies = $woocs->get_currencies();

		if ( in_array(
			$woocs->current_currency,
			$woocs->no_cents
		)/* OR $currencies[$this->current_currency]['hide_cents'] == 1 */ ) {
			$precision = 0;
		} else {
			if ( $woocs->current_currency != $woocs->default_currency ) {
				$precision = $woocs->get_currency_price_num_decimals(
					$woocs->current_currency,
					$woocs->price_num_decimals
				);
			} else {
				$precision = $woocs->get_currency_price_num_decimals(
					$woocs->default_currency,
					$woocs->price_num_decimals
				);
			}
		}

		if ( isset( $currencies[ $woocs->current_currency ] ) and $currencies[ $woocs->current_currency ] != null ) {
			$price_child = number_format(
				floatval( (float) $price_child * (float) $currencies[ $woocs->current_currency ]['rate'] ),
				$precision,
				$woocs->decimal_sep,
				''
			);
		} else {
			$price_child = number_format(
				floatval( (float) $price_child * (float) $currencies[ $woocs->default_currency ]['rate'] ),
				$precision,
				$woocs->decimal_sep,
				''
			);
		}

		//		}

		return $price_child;
	}

	/**
	 * Convert variations price
	 *
	 * @param $tour_variations_options_obj
	 *
	 * @return mixed
	 */
	public static function convert_variations_price_with_currency( $tour_variations_options_obj ) {
		$woocs = $GLOBALS['WOOCS'];

		$currencies = $woocs->get_currencies();

		if ( in_array(
			$woocs->current_currency,
			$woocs->no_cents
		)/* OR $currencies[$this->current_currency]['hide_cents'] == 1 */ ) {
			$precision = 0;
		} else {
			if ( $woocs->current_currency != $woocs->default_currency ) {
				$precision = $woocs->get_currency_price_num_decimals(
					$woocs->current_currency,
					$woocs->price_num_decimals
				);
			} else {
				$precision = $woocs->get_currency_price_num_decimals(
					$woocs->default_currency,
					$woocs->price_num_decimals
				);
			}
		}

		if ( isset( $currencies[ $woocs->current_currency ] ) and $currencies[ $woocs->current_currency ] != null ) {
			$currency_rate = (float) $currencies[ $woocs->current_currency ]['rate'];
		} else {
			$currency_rate = (float) $currencies[ $woocs->default_currency ]['rate'];
		}

		foreach ( $tour_variations_options_obj as $k => $variations ) {

			if ( $variations->set_price == 1 ) {
				foreach ( $variations->variation_attr as $k_attr => $v_attr ) {
					$price_attr = number_format(
						floatval( (float) $v_attr->price * $currency_rate ),
						$precision,
						$woocs->decimal_sep,
						''
					);

					$tour_variations_options_obj->{$k}->variation_attr->{$k_attr}->price = $price_attr;
				}
			}
		}

		return $tour_variations_options_obj;
	}
}

TravelPhysWooCurrencySwitcher::init();
