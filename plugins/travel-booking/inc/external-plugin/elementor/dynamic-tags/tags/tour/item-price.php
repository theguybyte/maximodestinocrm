<?php
namespace Tours\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Price extends Tag_Base {

	public function get_name() {
		return 'travelwp-item-price';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'travel-tour' );
	}

	public function get_title() {
		return 'Item Price';
	}

	protected function register_controls() {
		$this->add_control(
			'value',
			array(
				'label'   => esc_html__( 'Value', 'travel-booking' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'regular',
				'options' => array(
					'regular' => esc_html__( 'Regular', 'travel-booking' ),
					'sale'    => esc_html__( 'Sale', 'travel-booking' ),
					'both'    => esc_html__( 'Both', 'travel-booking' ),
					'from_to' => esc_html__( 'From to', 'travel-booking' ),
				),
			)
		);
	}
	public function render() {
		$settings = $this->get_settings_for_display();
		global $product;
		if ( ! $product ) {
			return '';
		}
		$value = '';
		switch ( $settings['value'] ) {
			case 'regular':
				$value = wc_price( $product->get_regular_price() ) . $product->get_price_suffix();
				break;
			case 'sale':
				$value = wc_price( $product->get_sale_price() ) . $product->get_price_suffix();
				break;
			case 'from_to':
				if ( ! empty( $product->get_sale_price() ) ) {
					$value .= '<span class="item-price-from">' . wc_price( $product->get_regular_price() ) . $product->get_price_suffix() . '</span>';
					$value .= '<span class="item-price-to">' . esc_html__( 'From', 'travel-booking' ) . wc_price( $product->get_sale_price() ) . $product->get_price_suffix() . '</span>';
				} else {
					$value .= '<span class="item-price-to" >' . esc_html__( 'From', 'travel-booking' ) . wc_price( $product->get_regular_price() ) . $product->get_price_suffix() . '</span>';
				}
				break;
			default:
				$value = $product->get_price_html();
				break;
		}
		echo $value;
	}
}
