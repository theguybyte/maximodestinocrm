<?php
/**
 * @author      physcode
 * @version     1.0.0
 * @description Model get quantity can book of room
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class ModelDataToGetPriceDatesTour {
	/**
	 * @var int
	 */
	public $tour_id = 0;
	/**
	 * @var float
	 */
	public $regular_price = 0;
	/**
	 * @var float
	 */
	public $child_price = 0;
	/**
	 * @var object json
	 */
	public $tour_variations;
	/**
	 * @var object json
	 */
	public $tour_variations_options;
	/**
	 * @var object json
	 */
	public $price_dates_tour_option;
	/**
	 * @var string
	 */
	public $date_booking;
	public $date_check_in;
	public $date_check_out;

	public function __construct() {

	}


}
