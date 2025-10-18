<?php

namespace Elementor;

use Elementor\Plugin;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

class Thim_Ekit_Widget_Tours_Weather extends Widget_Base {

	public function __construct( $data = array(), $args = null ) {
		parent::__construct( $data, $args );
	}

	public function get_name() {
		return 'thim-ekits-tours-weather';
	}

	public function get_title() {
		return esc_html__( 'Tours Weather', 'travel-booking' );
	}

	public function get_icon() {
		return 'eicon-cloud-check';
	}

	public function get_categories() {
		return array( \TravelBooking\Tour_Elementor::CATEGORY_SINGLE_TOUR );
	}

	public function get_help_url() {
		return '';
	}
	public function get_style_depends() {
		return array( 'travel-tour-weather' );
	}
	public function get_script_depends() {
		return array( 'flatWeatherJqueryPlugin' );
	}
	public function render() {
		$settings = $this->get_settings_for_display();
		do_action( 'thim-ekit/modules/single-tour/before-preview-query' );
		$lat = get_post_meta( get_the_ID(), '_tour_location_lat', true );
		$lng = get_post_meta( get_the_ID(), '_tour_location_long', true );
		if ( ! empty( $lat ) && ! empty( $lng ) ) :
			?>
		<div class="thim-kits-tours-weather">
			<div class="box-widget-item fl-wrap" id="listing-weather-widget">                                
				<div class="gradient-bg lweather-widget" data-lat="<?php echo esc_attr( $lat ); ?>" data-lng="<?php echo esc_attr( $lng ); ?>"></div>
			</div>
		</div>  
			<?php
		endif;
		do_action( 'thim-ekit/modules/single-tour/after-preview-query' );
	}
}
