<?php
namespace Tours\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Duration extends Tag_Base {

	public function get_name() {
		return 'travelwp-item-duration';
	}

	public function get_categories() {
		return array( TagsModule::TEXT_CATEGORY );
	}

	public function get_group() {
		return array( 'travel-tour' );
	}

	public function get_title() {
		return 'Item Duration';
	}
	public function render() {
		$settings = $this->get_settings_for_display();
		global $product, $post;
		if ( ! $product ) {
			return '';
		}
		$tour_duration = get_post_meta( $post->ID, '_tour_duration', true );
		if ( ! empty( $tour_duration ) ) {
			echo '<span>' . $tour_duration . '</span>';
		}
	}
}
