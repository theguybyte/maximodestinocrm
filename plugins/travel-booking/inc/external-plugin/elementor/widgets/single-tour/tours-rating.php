<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;
if ( ! class_exists( '\Elementor\Thim_Ekit_Widget_Product_Rating' ) ) {
	include THIM_EKIT_PLUGIN_PATH . 'inc/elementor/widgets/single-product/product-rating.php';
}
class Thim_Ekit_Widget_Tours_Rating extends Thim_Ekit_Widget_Product_Rating {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-tours-rating';
	}

	public function get_title() {
		return esc_html__( 'Tours Rating', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-product-rating';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_help_url() {
		return '';
	}
}
