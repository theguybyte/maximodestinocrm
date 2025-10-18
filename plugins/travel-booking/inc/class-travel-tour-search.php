<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Tour_Travel_Tour_Search {
	/**
	 * @param bool $include
	 *
	 * @return Tour_Travel_Tour_Search
	 */
	protected $min = '.min';
	protected $include;

	public static function instance( bool $include = true ) {
		$self          = new self();
		$self->include = $include;

		return $self;
	}

	public function __construct() {
		if ( defined( 'TRAVEL_TOUR_DEBUG' ) ) { 
			$this->min = '';
		}

		add_shortcode( 'tour_travel_tour_search', array( $this, 'tour_travel_tour_search' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
	}

	public function wp_enqueue_scripts() {
		//date time

		wp_register_script( 'tour-search', TOUR_BOOKING_PHYS_URL . 'assets/dist/js/frontend/tour-search' . $this->min . '.js',
			array( 'jquery', 'wp-api-fetch' ),
			uniqid(),
			true
		);
		wp_localize_script('tour-search', 'travel_booking_search', array(
			'duration' 			 => __('Duration:', 'travel-booking'),
			'price' 			 => __('Price:', 'travel-booking'),	
		));
	}

	/**
	 * @param $attrs
	 *
	 * @return false|string
	 */
	public function tour_travel_tour_search( $attrs ) {
		ob_start();
		$data = shortcode_atts(
			self::get_default(),
			$attrs
		);

		include TB_PHYS_TEMPLATE_PATH_DEFAULT . "tour-search/index.php";

		return ob_get_clean();
	}

	/**
	 * @return mixed|null
	 */
	public static function get_default() {
		return apply_filters( 'shortcode/tour-travel-tour-search/default-args', array(
			'fields' => array(
				'destination',
				'date-time',
				'selection',
				'duration',
				'type',
				'rating',
				'price',
				'search-button'
			)
		) );
	}
}

Tour_Travel_Tour_Search::instance();

