<?php
/**
 * Class TravelPhysCalculate
 *
 * @author  physcode
 * @version 1.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class TravelPhysCalculate {
	protected static $instance;

	public static function getInstance(): TravelPhysCalculate {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {

	}

	/**
	 * Get price dates tour default
	 *
	 * @param ModelDataToGetPriceDatesTour $data
	 *
	 * @return object
	 */
	public static function getPriceDatesTourDefault( $data ) {
		/* data tour variations option example
		{"1582079716806":{"label_variation":"Choose","enable":"1","type_variation":"checkbox","set_price":"1","required":"0","variation_attr":{"1582079716806":{"label":"Checkbox","price":"100","des":""}}},"1581998465087":{"label_variation":"Variation","enable":"1","type_variation":"quantity","set_price":"1","required":"0","variation_attr":{"1582079658779":{"label":"Car","price":"100","des":""},"1581998465087":{"label":"Quantity","price":"100","des":""}}}}
		 */
		$price_dates_tour                      = new stdClass();
		$price_dates_tour->regular_price_dates = $data->regular_price;

		if ( $data->child_price > 0 ) {
			$price_dates_tour->child_price_dates = $data->child_price;
		}

		if ( isset( $data->tour_variations ) ) {
			$tour_variations        = $data->tour_variations;
			$tour_variations_option = $data->tour_variations_options;

			foreach ( $tour_variations as $k_variation => $variation ) {
				if ( ! isset( $price_dates_tour->{$k_variation} ) && $tour_variations_option->{$k_variation}->set_price == 1 ) {
					$price_dates_tour->{$k_variation} = new stdClass();
				}

				foreach ( $variation as $k_attribute => $attribute ) {
					if ( ! isset( $price_dates_tour->{$k_variation}->{$k_attribute} ) && $tour_variations_option->{$k_variation}->set_price == 1 ) {
						$price_dates_tour->{$k_variation}->{$k_attribute} = (float) $tour_variations_option->{$k_variation}->variation_attr->{$k_attribute}->price;
					}
				}
			}
		}

		//echo '<pre>' . print_r( $price_dates_tour, true ) . '</pre>';

		return apply_filters( 'price_dates_tour_default_phys', $price_dates_tour, $data );
	}

	/**
	 * @param ModelDataToGetPriceDatesTour $data
	 *
	 * @return object
	 * @throws Exception
	 */
	public static function getPriceDatesTourByDates_bk( ModelDataToGetPriceDatesTour $data ) {
		/* data price dates tour example
		{"price_dates_range":{"1582087337976":{"start_date":"2020/02/24","end_date":"2020/02/29","prices":{"regular_price_dates":{"label":"Regular Price","price":"50"},"child_price_dates":{"label":"Child Price","price":""},"1582079716806":{"1582079716806":{"label":"Checkbox","price":"50"}},"1581998465087":{"1582079658779":{"label":"Car","price":"50"},"1581998465087":{"label":"Quantity","price":""}}}}}}
		 */

		$price_dates_tour        = self::getPriceDatesTourDefault( $data );
		$price_dates_tour_option = $data->price_dates_tour_option;

		$check_exists_any_key = array();

		if ( isset( $price_dates_tour_option->price_dates_range ) ) {
			$check_exists_any_key = (array) $price_dates_tour_option->price_dates_range;
		}

		$has_price_dates_tour_option = isset( $price_dates_tour_option ) && isset( $price_dates_tour_option->price_dates_range ) && ! empty( $check_exists_any_key ) ? 1 : 0;

		if ( isset( $data->date_booking ) ) { // is Fixed date
			if ( $has_price_dates_tour_option ) {
				foreach ( $price_dates_tour_option->price_dates_range as $price_dates_range ) {
					$start_date = new DateTime( $price_dates_range->start_date );
					$end_date   = new DateTime( $price_dates_range->end_date );

					$dateBook = new DateTime( TravelPhysUtility::convert_date_to_format_default( $data->date_booking ) );

					if ( $dateBook >= $start_date && $dateBook <= $end_date ) {
						foreach ( $price_dates_range->prices as $k_price => $v_price ) {

							if ( isset( $price_dates_tour->{$k_price} ) ) { // check have key
								if ( $k_price == 'regular_price_dates' || $k_price == 'child_price_dates' ) {
									$price_attr = (float) $v_price->price;

									if ( $price_attr > 0 ) {
										$price_dates_tour->{$k_price} = $price_attr;
									}
								} else {
									// Variations
									foreach ( $v_price as $k_variation => $variation_attr ) {

										if ( isset( $price_dates_tour->{$k_price} ) ) { // check have key
											$price_attr = (float) $variation_attr->price;

											if ( $price_attr > 0 ) {
												$price_dates_tour->{$k_price}->{$k_variation} = $price_attr;
											}
										}
									}
								}
							}
						}
					}
				}
			}
		} elseif ( isset( $data->date_check_in ) && isset( $data->date_check_out ) ) {
			$date_check_in        = new DateTime( TravelPhysUtility::convert_date_to_format_default( $data->date_check_in ) );
			$date_check_out       = new DateTime( TravelPhysUtility::convert_date_to_format_default( $data->date_check_out ) );
			$date_next            = $date_check_in;
			$price_dates_tour_tmp = new stdClass();

			while ( $date_next < $date_check_out ) {
				if ( $has_price_dates_tour_option ) {
					$dateExistsOption = [];
					foreach ( $price_dates_tour_option->price_dates_range as $price_dates_range ) {
						$start_date = new DateTime( $price_dates_range->start_date );
						$end_date   = new DateTime( $price_dates_range->end_date );

						if ( $date_next >= $start_date && $date_next <= $end_date ) {
							$dateExistsOption[ $date_next->format( 'Y-m-d' ) ] = $date_next;
							foreach ( $price_dates_range->prices as $k_price => $v_price ) {
								if ( isset( $price_dates_tour->{$k_price} ) ) {
									if ( $k_price == 'regular_price_dates' || $k_price == 'child_price_dates' ) {
										$price_attr = (float) $v_price->price;

										if ( $price_attr <= 0 ) {
											$price_attr = $price_dates_tour->{$k_price};
										}

										if ( ! isset( $price_dates_tour_tmp->{$k_price} ) ) {
											$price_dates_tour_tmp->{$k_price} = $price_attr;
										} else {
											$price_dates_tour_tmp->{$k_price} += $price_attr;
										}
									} else {
										// Variations
										foreach ( $v_price as $k_variation => $variation_attr ) {
											if ( isset( $price_dates_tour->{$k_price}->{$k_variation} ) ) {
												$price_attr = (float) $variation_attr->price;

												if ( $price_attr <= 0 ) {
													$price_attr = $price_dates_tour->{$k_price}->{$k_variation};
												}

												if ( ! isset( $price_dates_tour_tmp->{$k_price} ) ) {
													$price_dates_tour_tmp->{$k_price} = new stdClass();
												}

												if ( ! isset( $price_dates_tour_tmp->{$k_price}->{$k_variation} ) ) {
													$price_dates_tour_tmp->{$k_price}->{$k_variation} = $price_attr;
												} else {
													$price_dates_tour_tmp->{$k_price}->{$k_variation} += $price_attr;
												}
											}
										}
									}
								}
							}
						}
					}

					if ( ! array_key_exists( $date_next->format( 'Y-m-d' ), $dateExistsOption ) ) {
						self::getPriceDatesDefaultByDateRange( $price_dates_tour_tmp, $price_dates_tour );
					}
				} else {
					self::getPriceDatesDefaultByDateRange( $price_dates_tour_tmp, $price_dates_tour );
				}

				$date_next = $date_next->add( new DateInterval( 'P1D' ) );
			}

			/*** Set total price days ***/
			$price_dates_tour->regular_price_dates = $price_dates_tour_tmp->regular_price_dates;
			$price_dates_tour->child_price_dates   = $price_dates_tour_tmp->child_price_dates ?? 0;

			// Variations
			foreach ( $price_dates_tour as $k_price => $v_price ) {
				if ( $k_price != 'regular_price_dates' && $k_price != 'child_price_dates' ) {
					foreach ( $v_price as $k_attr => $v_attr ) {
						$price_dates_tour->{$k_price}->{$k_attr} = $price_dates_tour_tmp->{$k_price}->{$k_attr};
					}
				}
			}
			/*** End set total price days ***/
		}

		//echo '<pre>' . print_r( $price_dates_tour, true ) . '</pre>';

		return apply_filters( 'price_dates_tour_phys', $price_dates_tour, $data );
	}

	/**
	 * @param ModelDataToGetPriceDatesTour $data
	 *
	 * @return object
	 * @throws Exception
	 */
	public static function getPriceDatesTourByDates( ModelDataToGetPriceDatesTour $data ) {
		/* data price dates tour example
		{"price_dates_range":{"1582087337976":{"start_date":"2020/02/24","end_date":"2020/02/29","prices":{"regular_price_dates":{"label":"Regular Price","price":"50"},"child_price_dates":{"label":"Child Price","price":""},"1582079716806":{"1582079716806":{"label":"Checkbox","price":"50"}},"1581998465087":{"1582079658779":{"label":"Car","price":"50"},"1581998465087":{"label":"Quantity","price":""}}}}}}
		 */

		$price_dates_tour        = self::getPriceDatesTourDefault( $data );
		$price_dates_tour_option = $data->price_dates_tour_option;

		$check_exists_any_key = array();

		if ( isset( $price_dates_tour_option->price_dates_range ) ) {
			$check_exists_any_key = (array) $price_dates_tour_option->price_dates_range;
		}

		$has_price_dates_tour_option = isset( $price_dates_tour_option ) && isset( $price_dates_tour_option->price_dates_range ) && ! empty( $check_exists_any_key ) ? 1 : 0;

		if ( isset( $data->date_booking ) ) { // is Fixed date
			$date_check_in = new DateTime( TravelPhysUtility::convert_date_to_format_default( $data->date_booking ) );
			$date_clone    = clone $date_check_in;
			$duration      = (float) get_post_meta( $data->tour_id, '_phys_tour_duration_number', true );

			if ( $duration > 1 ) {
				$date_check_out = $date_clone->add( new DateInterval( 'P' . $duration . 'D' ) );
			} else {
				$date_check_out = $date_clone->add( new DateInterval( 'P1D' ) );
			}
		} elseif ( isset( $data->date_check_in ) && isset( $data->date_check_out ) ) {
			$date_check_in  = new DateTime( TravelPhysUtility::convert_date_to_format_default( $data->date_check_in ) );
			$date_check_out = new DateTime( TravelPhysUtility::convert_date_to_format_default( $data->date_check_out ) );
		}

		$date_next            = clone $date_check_in;
		$price_dates_tour_tmp = new stdClass();

		// If check in date equal check out date
		if ( $date_check_in == $date_check_out ) {
			$date_check_out = $date_check_out->add( new DateInterval( 'P1D' ) );
		}

		while ( $date_next < $date_check_out ) {
			if ( $has_price_dates_tour_option ) {
				$dateExistsOption = [];
				foreach ( $price_dates_tour_option->price_dates_range as $price_dates_range ) {
					$start_date = new DateTime( $price_dates_range->start_date );
					$end_date   = new DateTime( $price_dates_range->end_date );

					if ( $date_next >= $start_date && $date_next <= $end_date ) {
						$dateExistsOption[ $date_next->format( 'Y-m-d' ) ] = $date_next;
						foreach ( $price_dates_range->prices as $k_price => $v_price ) {
							if ( isset( $price_dates_tour->{$k_price} ) ) {
								if ( $k_price == 'regular_price_dates' || $k_price == 'child_price_dates' ) {
									$price_attr = (float) $v_price->price;

									if ( $price_attr <= 0 ) {
										$price_attr = $price_dates_tour->{$k_price};
									}

									if ( ! isset( $price_dates_tour_tmp->{$k_price} ) ) {
										$price_dates_tour_tmp->{$k_price} = $price_attr;
									} else {
										$price_dates_tour_tmp->{$k_price} += $price_attr;
									}
								} else {
									// Variations
									foreach ( $v_price as $k_variation => $variation_attr ) {
										if ( isset( $price_dates_tour->{$k_price}->{$k_variation} ) ) {
											$price_attr = (float) $variation_attr->price;

											if ( $price_attr <= 0 ) {
												$price_attr = $price_dates_tour->{$k_price}->{$k_variation};
											}

											if ( ! isset( $price_dates_tour_tmp->{$k_price} ) ) {
												$price_dates_tour_tmp->{$k_price} = new stdClass();
											}

											if ( ! isset( $price_dates_tour_tmp->{$k_price}->{$k_variation} ) ) {
												$price_dates_tour_tmp->{$k_price}->{$k_variation} = $price_attr;
											} else {
												$price_dates_tour_tmp->{$k_price}->{$k_variation} += $price_attr;
											}
										}
									}
								}
							}
						}
					}
				}

				if ( ! array_key_exists( $date_next->format( 'Y-m-d' ), $dateExistsOption ) ) {
					self::getPriceDatesDefaultByDateRange( $price_dates_tour_tmp, $price_dates_tour );
				}
			} else {
				self::getPriceDatesDefaultByDateRange( $price_dates_tour_tmp, $price_dates_tour );
			}

			$date_next = $date_next->add( new DateInterval( 'P1D' ) );
		}

		/*** Set total price days ***/
		$price_dates_tour->regular_price_dates = $price_dates_tour_tmp->regular_price_dates;
		$price_dates_tour->child_price_dates   = $price_dates_tour_tmp->child_price_dates ?? 0;

		// Variations
		foreach ( $price_dates_tour as $k_price => $v_price ) {
			if ( $k_price != 'regular_price_dates' && $k_price != 'child_price_dates' ) {
				foreach ( $v_price as $k_attr => $v_attr ) {
					$price_dates_tour->{$k_price}->{$k_attr} = $price_dates_tour_tmp->{$k_price}->{$k_attr};
				}
			}
		}
		/*** End set total price days ***/

		//echo '<pre>' . print_r( $price_dates_tour, true ) . '</pre>';

		return apply_filters( 'price_dates_tour_phys', $price_dates_tour, $data );
	}

	protected static function getPriceDatesDefaultByDateRange( &$price_dates_tour_tmp, $price_dates_tour ) {
		if ( ! isset( $price_dates_tour_tmp->regular_price_dates ) ) {
			$price_dates_tour_tmp->regular_price_dates = $price_dates_tour->regular_price_dates;
		} else {
			$price_dates_tour_tmp->regular_price_dates += $price_dates_tour->regular_price_dates;
		}

		if ( ! isset( $price_dates_tour_tmp->child_price_dates ) ) {
			$price_dates_tour_tmp->child_price_dates = $price_dates_tour->child_price_dates ?? 0;
		} else {
			$price_dates_tour_tmp->child_price_dates += $price_dates_tour->child_price_dates ?? 0;
		}

		// Variation
		foreach ( $price_dates_tour as $k_price => $v_price ) {
			if ( $k_price != 'regular_price_dates' && $k_price != 'child_price_dates' ) {
				foreach ( $v_price as $k_attr => $v_attr ) {
					if ( ! isset( $price_dates_tour_tmp->{$k_price} ) ) {
						$price_dates_tour_tmp->{$k_price} = new stdClass();
					}

					if ( ! isset( $price_dates_tour_tmp->{$k_price}->{$k_attr} ) ) {
						$price_dates_tour_tmp->{$k_price}->{$k_attr} = $price_dates_tour->{$k_price}->{$k_attr};
					} else {
						$price_dates_tour_tmp->{$k_price}->{$k_attr} += $price_dates_tour->{$k_price}->{$k_attr};
					}
				}
			}
		}
	}

	/**
	 * @param array $cart_item
	 *
	 * @return array|float|int
	 * @throws Exception
	 */
	public static function get_subtotal_item_tour( array $cart_item ) {
		/** @var WC_Product $tour */
		$tour          = $cart_item['data'];
		$tour_id       = (int) $tour->get_id();
		$total_person  = 0;
		$subtotal      = 0;
		$cart          = WC()->cart;
		$cart_item_key = $cart->generate_cart_id( $tour_id );
		//$subtotal     += wc_add_number_precision_deep( $cart_item['data']->get_price() ) * $cart_item['quantity'];

		if ( isset( $_POST['cart'] ) ) {
			if ( isset( $_POST['cart'][ $cart_item_key ]['number_children'] ) ) {
				$cart->cart_contents[ $cart_item_key ]['number_children'] = $_POST['cart'][ $cart_item_key ]['number_children'];
				$cart_item['number_children']                             = $_POST['cart'][ $cart_item_key ]['number_children'];
			}
		}

		/*** Set data to get price dates tour ***/
		$modelDataToGetPriceDatesTour                = new ModelDataToGetPriceDatesTour();
		$modelDataToGetPriceDatesTour->tour_id       = $tour_id;
		$modelDataToGetPriceDatesTour->regular_price = (float) $cart_item['tour_origin_price'] ?? 0;

		if ( isset( $cart_item['date_booking'] ) ) {
			$modelDataToGetPriceDatesTour->date_booking = $cart_item['date_booking'];
		} elseif ( isset( $cart_item['date_check_in'] ) && isset( $cart_item['date_check_out'] ) ) {
			$modelDataToGetPriceDatesTour->date_check_in  = $cart_item['date_check_in'];
			$modelDataToGetPriceDatesTour->date_check_out = $cart_item['date_check_out'];
		}

		if ( isset( $cart_item['number_children'] ) ) {
			$price_children                            = (float) TravelPhysTab::instance()->get_tour_meta( $tour_id, '_price_child' );
			$modelDataToGetPriceDatesTour->child_price = $price_children;
		}

		// Variation
		if ( isset( $cart_item['tour_variations'] ) ) {
			$tour_variations_option_obj = TravelPhysVariation::getInstance()->get_variation_options( $tour_id );

			if ( json_last_error() == JSON_ERROR_NONE ) {
				$cart->cart_contents[ $cart_item_key ]['tour_variations_options'] = $tour_variations_option_obj;

				$modelDataToGetPriceDatesTour->tour_variations         = $cart_item['tour_variations'];
				$modelDataToGetPriceDatesTour->tour_variations_options = $tour_variations_option_obj;
			}
		}

		// Price dates option
		$price_dates_tour_option = json_decode( get_post_meta( $tour_id, '_phys_price_of_dates_option', true ) );

		if ( json_last_error() == JSON_ERROR_NONE ) {
			$cart->cart_contents[ $cart_item_key ]['price_dates_tour_option'] = $price_dates_tour_option;

			$modelDataToGetPriceDatesTour->price_dates_tour_option = $price_dates_tour_option;
		}
		/*** End set data to get price dates tour ***/

		// get price dates tour
		$price_dates_tour = TravelPhysCalculate::getPriceDatesTourByDates( $modelDataToGetPriceDatesTour );

		$cart->cart_contents[ $cart_item_key ]['price_dates_tour'] = $price_dates_tour;

		//echo '<pre>' . print_r( $price_dates_tour, true ) . '</pre>';
		//echo '<pre>' . print_r( $cart_item, true ) . '</pre>';

		/*** Get subtotal tour ***/
		foreach ( $price_dates_tour as $k_price => $v_price ) {
			if ( $k_price == 'regular_price_dates' ) {
				//$cart->cart_contents[$cart_item_key]['data']->set_price($v_price);
				$tour->set_price( $v_price ); // save cart data;

				$quantity          = $cart_item['quantity'];
				$total_person     += $quantity;
				$total_price_adult = $v_price * $quantity;
				$subtotal         += wc_add_number_precision( $total_price_adult );
			} elseif ( $k_price == 'child_price_dates' ) {
				$cart->cart_contents[ $cart_item_key ]['price_children'] = $v_price; // save cart data

				$number_chidren       = $cart_item['number_children'] ?? 0;
				$total_person        += $number_chidren;
				$total_price_children = $v_price * $number_chidren;
				$subtotal            += wc_add_number_precision( $total_price_children );
			} else {
				// Variation
				foreach ( $v_price as $k_attr => $v_attr ) {
					$quantity_attr = 1;

					// Add the price of the variant that was added. .
					if ( isset( $cart_item['tour_variations']->{$k_price}->{$k_attr} ) ) {
						if ( isset( $cart_item['tour_variations']->{$k_price}->{$k_attr}->quantity ) ) {
							$quantity_attr = $cart_item['tour_variations']->{$k_price}->{$k_attr}->quantity;
						}

						$total_price_variation = $v_attr * $quantity_attr;
						$subtotal             += wc_add_number_precision( $total_price_variation );
					}
				}
			}
		}

		// Group discount
		$tour_group_discount_enable = get_post_meta( $tour_id, '_tour_group_discount_enable', true );

		if ( $tour_group_discount_enable == 1 ) {
			$tour_group_discount_data = json_decode( get_post_meta( $tour_id, '_tour_group_discount_data', true ) );

			if ( json_last_error() == JSON_ERROR_NONE ) {
				if ( $tour_group_discount_data !== null && json_last_error() === JSON_ERROR_NONE ) {
					$customer_number_match = 0;
					$discount_val          = 0;

					foreach ( $tour_group_discount_data as $item_discount ) {
						$number_customer = (int) ( $item_discount->number_customer );

						if ( $number_customer <= $total_person && $customer_number_match < $number_customer ) {
							$customer_number_match = $number_customer;
							$discount_val          = $item_discount->discount;
						}
					}

					if ( $customer_number_match !== 0 ) {
						$pattern = '/\%$/';

						if ( preg_match( $pattern, $discount_val ) ) {
							WC()->cart->cart_contents[ $cart_item_key ]['tour_group_discount'] = '<strong>' . __( 'Group discount' ) . '</strong>: ' . $discount_val . ' (' . $total_person . ' ' . __( 'People' ) . ')';

							$discount_val = (float) ( $discount_val );
							$subtotal     = $subtotal - ( $subtotal * $discount_val / 100 );
						} else {
							WC()->cart->cart_contents[ $cart_item_key ]['tour_group_discount'] = '<strong>' . __( 'Group discount' ) . '</strong> ' . wc_price( $discount_val ) . ' (' . $total_person . ' ' . __( 'People' ) . ')';

							$subtotal -= wc_add_number_precision_deep( $discount_val );
						}
					} else {
						unset( WC()->cart->cart_contents[ $cart_item_key ]['tour_group_discount'] );
					}
				}
			}
		}

		return apply_filters( 'get_subtotal_item_tour_phys', $subtotal, $cart_item, $cart_item_key );
	}
}

TravelPhysCalculate::getInstance();
