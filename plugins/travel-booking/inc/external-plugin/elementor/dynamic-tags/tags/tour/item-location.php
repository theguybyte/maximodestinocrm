<?php

namespace Tours\Elementor\DynamicTags\Tags;

use Elementor\Controls_Manager;
use Elementor\Core\DynamicTags\Tag as Tag_Base;
use Elementor\Modules\DynamicTags\Module as TagsModule;

defined( 'ABSPATH' ) || exit;

class Item_Location extends Tag_Base {

	public function get_name() {
		return 'tours-item-location';
	}

	public function get_categories() {
		return array(
			TagsModule::TEXT_CATEGORY,
			TagsModule::URL_CATEGORY,
			TagsModule::POST_META_CATEGORY,
			TagsModule::COLOR_CATEGORY,
			TagsModule::DATETIME_CATEGORY,
		);
	}

	public function get_group() {
		return array( 'travel-tour' );
	}

	public function get_title() {
		return 'Item Address';
	}

	public function render() {
		$settings = $this->get_settings_for_display();
		global $product, $post;
		if ( ! $product ) {
			return '';
		}
		$location_opt = get_option( 'location_option' );
		if ( $location_opt == 'google_api' ) {
			$location_address = get_post_meta( $post->ID, '_tour_location_address', true );
		} else {
			$google_map_iframe = get_post_meta( $post->ID, '_google_map_iframe', true );
			if ( $google_map_iframe ) {
				preg_match( '/<iframe.*?src=["\'](.*?)["\'].*?>/', $google_map_iframe, $matches );
				if ( $matches ) {
					$iframe_src = $matches[1];
					preg_match( '/!2z([^!]+)!/', $iframe_src, $address_matches );
					if ( $address_matches ) {
						$encoded_address  = $address_matches[1];
						$location_address = base64_decode( strtr( $encoded_address, '-_', '+/' ) );
					}
				} else {
					$location_address = $google_map_iframe;
				}
			}
		}

		echo $location_address;
	}
}
