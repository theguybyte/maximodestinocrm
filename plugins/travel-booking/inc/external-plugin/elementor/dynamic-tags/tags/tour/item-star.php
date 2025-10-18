<?php
namespace Tours\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Star extends Tag_Base {

	public function get_name() {
		return 'travelwp-item-star';
	}

	public function get_categories() {
		return array(
			TagsModule::NUMBER_CATEGORY,
			TagsModule::TEXT_CATEGORY,
		);
	}

	public function get_group() {
		return array( 'travel-tour' );
	}

	public function get_title() {
		return 'Item Ratting';
	}

	protected function register_controls() {

		$this->add_control(
			'value',
			array(
				'label'   => esc_html__( 'Value', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'options' => array(
					'average_rating' => esc_html__( 'Average Rating', 'travel-booking' ),
					'rating_count'   => esc_html__( 'Rating Count', 'travel-booking' ),
					'review_count'   => esc_html__( 'Review Count', 'travel-booking' ),
				),
				'default' => 'average_rating',
			)
		);
	}
	public function render() {
		$settings = $this->get_settings_for_display();
		global $product;
		if ( ! $product ) {
			return;
		}
		$field = $settings['value'];
		$value = '';
		switch ( $field ) {
			case 'average_rating':
				$value = $product->get_average_rating();
				break;
			case 'rating_count':
				$value = $product->get_rating_count();
				break;
			case 'review_count':
				$value = $product->get_review_count();
				break;
		}
		echo $value;
	}
}
